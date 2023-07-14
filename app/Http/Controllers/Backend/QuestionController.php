<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\QARequest;
use App\Http\Requests\UploadQARquest;
use App\Imports\QAImport;
use App\Models\Category;
use App\Models\CategoryQuestion;
use App\Models\Question;
use App\Models\QuestionRelated;
use App\Models\Tag;
use App\Models\TagQuestion;
use App\Repositories\Answer\AnswerRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Tag\TagRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class QuestionController extends Controller
{
    protected $adminId = 1;
    protected $urlS3;

    /**
     * @var $questionRepository
     */
    protected $questionRepository, $answerRepository, $tagRepository;

    const IMAGE_TYPE = ['gif', 'jpg', 'jpeg', 'jfif', 'pjpeg', 'pjp', 'png', 'svg'];
    const VIDEO_TYPE = ['mp4'];
    const DOCS_TYPE = ['docx', 'doc', 'txt', 'xlsx', 'pdf'];

    /**
     * function constructor.
     *
     * @param QuestionManageService $questionService
     * @param AnswerManageService $answerService
     */
    public function __construct(QuestionRepository $questionRepository, AnswerRepository $answerRepository, TagRepository $tagRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->tagRepository = $tagRepository;
        $this->urlS3 = 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com';
    }


    /**
     * Upload file
     *
     * @return Respone
     */
    public function uploadFile()
    {
        $file = request()->file('upload');
        $extension = $file->getClientOriginalExtension();
        $name = $file->getClientOriginalName();
        $folder = $this->setFileFolder($extension);
        $imgpath = Storage::disk('s3')->put('upload/' . $folder, $file);
        $url = Storage::disk('s3')->url($imgpath);
        return response()->json(['location' => $url, 'name' =>  $name]);
    }

    /**
     * Set folder save file
     *
     * @param string $fileExtension
     * @return string
     */
    public function setFileFolder($fileExtension)
    {
        if (in_array(strtolower($fileExtension), self::IMAGE_TYPE)) {
            return 'image';
        } else if (in_array(strtolower($fileExtension), self::VIDEO_TYPE)) {
            return 'video';
        } else {
            return 'docs';
        }
    }

    /**
     * Form create QA
     *
     * @return void
     */
    public function create()
    {
        $categories = Category::select('id', 'title')->get();
        $tags = Tag::select('id', 'title')->get();
        return view('backend.dashboard.create', compact('categories', 'tags'));
    }

    /**
     * Create new QA
     *
     * @param QARequest array $request
     * @return void
     */
    public function store(QARequest $request)
    {
        try {
            DB::beginTransaction();

            $answer = $this->answerRepository->store([
                'content' => $request->input('answer'),
                'created_by' => $this->adminId,
                'updated_by' => $this->adminId
            ]);
            $request->merge(['answer_id' => $answer->id]);
            $data = $this->fomatQuestionData($request);

            $hashtag = [];
            if ($request->input('hashtag')) {
                $hashtag = array_filter($request->input('hashtag'), function($k) {
                    return is_numeric($k);
                });
                $newTag = array_filter($request->input('hashtag'), function($k) {
                    return !is_numeric($k);
                });
                foreach ($newTag as $item) {
                    $tag = $this->tagRepository->create(['title' => $item]);
                    $hashtag[] = $tag->id;
                }
            }
            
            foreach ($data as $item) {
                $question = $this->questionRepository->create($item);
                $hashtag && $question->tags()->attach($hashtag);
                $question->categories()->attach($request->input('categories'));

                $dataQuestionRelated = [];
                $questionRelatedIds = $request->related_question_input;
                if ($questionRelatedIds) {
                    $questionRelatedIds = json_decode($questionRelatedIds);
                    $questionRelatedIds = array_keys((array)$questionRelatedIds);
                    foreach ($questionRelatedIds as $key => $questionRelatedId) {
                        $dataQuestionRelated[$key]['question_id'] = $question->id;
                        $dataQuestionRelated[$key]['question_related_id'] = $questionRelatedId;
                        $dataQuestionRelated[$key]['created_at'] = Carbon::now();
                        $dataQuestionRelated[$key]['updated_at'] = Carbon::now();
                    }
                }

                if (count($dataQuestionRelated)) {
                    $question->questionRelateds()->insert($dataQuestionRelated);
                }
            }
            DB::commit();
            return redirect()->route('question.create')->withSuccess('質問追加成功');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return redirect()->route('question.create')->withErrors(trans('messages.error'));
        }
    }

    /**
     * Detail QA
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        $qa = $this->questionRepository->detail($id);
        if ($qa) {
            $categories = Category::select('id', 'title')->get();
            $tags = Tag::select('id', 'title')->get();
            return view('backend.dashboard.edit', compact('qa', 'categories', 'tags'));
        }
        return redirect()->back();
    }

    /**
     * update QA
     *
     * @param int $id
     * @return void
     */
    public function update(QARequest $request)
    {
        try {
            $id = $request->route('question');
            $qa = $this->questionRepository->find($id);
            if ($qa) {
                DB::beginTransaction();
                $answer = $this->answerRepository->store([
                    'content' => $request->input('answer'),
                    'created_by' => $this->adminId,
                    'updated_by' => $this->adminId
                ]);

                $qa->title = $request->input('questions')[0];
                $qa->answer_id = $answer->id;
                $qa->status = $request->input('status') ? $request->input('status') : 0;

                if ($request->input('hashtag')) {
                    TagQuestion::where('question_id', $id)->delete();
                    $hashtag = array_filter($request->input('hashtag'), function($k) {
                        return is_numeric($k);
                    });
                    $newTag = array_filter($request->input('hashtag'), function($k) {
                        return !is_numeric($k);
                    });
            
                    foreach ($newTag as $item) {
                        $tag = $this->tagRepository->create(['title' => $item]);
                        $hashtag[] = $tag->id;
                    }
                    $qa->tags()->attach($hashtag);
                }
                
                CategoryQuestion::where('question_id', $id)->delete();
                $qa->categories()->attach($request->input('categories'));
                QuestionRelated::where('question_id', $id)->delete();
                $dataQuestionRelated = [];
                $questionRelatedIds = $request->related_question_input;
                if ($questionRelatedIds) {
                    $questionRelatedIds = json_decode($questionRelatedIds);
                    $questionRelatedIds = array_keys((array)$questionRelatedIds);
                    foreach ($questionRelatedIds as $key => $item) {
                        $dataQuestionRelated[$key]['question_id'] = $id;
                        $dataQuestionRelated[$key]['question_related_id'] = $item;
                        $dataQuestionRelated[$key]['created_at'] = Carbon::now();
                        $dataQuestionRelated[$key]['updated_at'] = Carbon::now();
                    }
                }

                if (count($dataQuestionRelated)) {
                    $qa->questionRelateds()->insert($dataQuestionRelated);
                }
                $qa->save();
                DB::commit();
                return redirect()->route('question.edit', $id)->withSuccess('質問更新成功');
            } else {
                return redirect()->route('question.edit', $id);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return redirect()->route('question.edit', $id)->withErrors(trans('messages.error'));
        }
    }

    /**
     * Format input
     *
     * @param Request $request
     * @return array
     */
    public function fomatQuestionData($request)
    {
        $data = [];
        $titles = $request->input('questions');
        for ($i = 0; $i < count($titles); $i++) {
            $data[$i]['title'] = $titles[$i];
            $data[$i]['display_for_find'] =  $request->input('display_for_find');
            $data[$i]['note'] =  $request->input('note');
            $data[$i]['answer_id'] =  $request->input('answer_id');
            $data[$i]['status'] =  $request->input('status') ? $request->input('status') : 0;
            $data[$i]['created_by'] =  $this->adminId;
            $data[$i]['updated_by'] = $this->adminId;
        }
        return $data;
    }

    public function import(UploadQARquest $request)
    {
        try {
            $file = $request->file('upload');
            $uploadCode = random_int(1000, 10000) . time();
            $zipPath = $this->urlS3 . '/upload/zip/' . $uploadCode .  '/';
            $path = $file->move('storage/upload/import', $uploadCode);
            $zip = new ZipArchive();
            $zip->open($path);
            $storageDestinationPath = storage_path("app/public/upload/zip/" . $uploadCode);
            $zip->extractTo($storageDestinationPath);
            $zip->close();
            $allFile = File::allFiles($storageDestinationPath);
            $fileImport = '';
            foreach ($allFile as $fileItem) {
                $path = Storage::disk('s3')->put('upload/zip/' . $uploadCode .  '/' .$fileItem->getFilename(), file_get_contents($fileItem->getPathname()));
                if (strpos($fileItem->getFilename(), 'import') !== false && strpos(strtolower($fileItem->getFilename()), 'import') == 0) {
                    $fileImport = $fileItem->getPathname();
                }
            }
            if ($fileImport) {
                $result = new QAImport($zipPath);
                Excel::import($result, $fileImport);
                if ($result->status) {
                    return redirect()->back()->withSuccess(trans('messages.import_success'));
                } else {
                    return redirect()
                        ->back()
                        ->withErrors(['upload' => trans('messages.import_error'), 'errorUpload' => json_encode($result->errors)]);
                }
            }
            return redirect()
                ->back()
                ->withErrors(['upload' => trans('messages.import_error')]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $failures = array_map(function ($item) {
                $data = [];
                foreach ($item->errors() as $option) {
                    $data[] = 'Row ' . ($item->row()) . ': ' . $option;
                }
                return $data;
            }, $failures);
            return redirect()
                ->back()
                ->withErrors(['upload' => trans('messages.import_error'), 'errorUpload' => json_encode($failures)]);
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()
                ->back()
                ->withErrors(['upload' => trans('messages.import_error')]);
        }
    }

    public function getListByTag(Request $request)
    {
        $questions = Question::with(['tags' => function ($q) {
            $q->select('tags.id');
        }])->whereHas('tags', function ($query) use ($request) {
            $query->whereIn('tag_id', $request->hashTag ?? []);
        })
            ->select('id', 'title')
            ->get();
        return response()->json($questions);
    }
}
