<?php

namespace App\Imports;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\Setting;
use App\Models\Tag;
use App\Rules\QuestionItemNotNull;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\HeadingRowImport;

class QAImport implements ToCollection, WithBatchInserts, WithHeadingRow, SkipsEmptyRows
{
    protected $zipPath;
    protected $adminId = 1;
    public $errors;
    public $message;
    public $status;
    const PATTERN = "/(?<=\[!)(.*?)(?=\])/";
    const PATTERN_LINK = "/(?<=\[@)(.*?)(?=\])/";
    const IMAGE_TYPE = ['gif', 'jpg', 'jpeg', 'jfif', 'pjpeg', 'pjp', 'png', 'svg'];
    const VIDEO_TYPE = ['mp4'];
    const DOCS_TYPE = ['docx', 'doc', 'txt', 'xlsx', 'pdf'];

    public function __construct($zipPath)
    {
        $this->zipPath = $zipPath;
    }

    /**,
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        try {
            DB::beginTransaction();
            $options = [];
            // check empty file
            if (count($rows) == 0) {
                return throw new Exception("File import is Null!");
            }

            $checkErrors = [
                'status' => false,
                'errors' => [],
                'message' => ''
            ];

            $qa = Question::select('id', 'title')->with('tags:id')
                ->get();
            $qaWithTag = [];
            $titleDB = [];
            foreach ($qa as $item) {
                $title = trim(strip_tags(html_entity_decode($item->title)));
                $qaWithTag[$title] = [
                    $item->id => $item->tags->pluck('id')->toArray(),
                ];

                $titleDB[] = $title;
            }
            $qaList = [];
            $i = 2;
            foreach ($rows as $key => $row) {
                if ($row->filter()->isNotEmpty()) {
                    $result = $this->convertData($row, $i, $titleDB, $qaWithTag);
                    $options[$i] = $result['data'];
                    if (isset($result['data']['questions']))
                        $qaList = array_merge($qaList, $result['data']['questions']);
                    if (isset($result['data']['similar_question']))
                        $qaList = array_merge($qaList, $result['data']['similar_question']);
                    if (count($result['errors']) > 0) {
                        $checkErrors['errors'][$i] = $result['errors'];
                    }
                }
                $i++;
            }
            $qaList = array_map(function ($item) {
                return trim(strip_tags($item));
            }, $qaList);
            foreach ($options as $index => $option) {
                if (isset($option['questions'])) {
                    foreach ($option['questions'] as $key => $qa) {
                        $qa = trim(strip_tags($qa));
                        $result = array_filter($qaList, function ($item) use ($qa, $key) {
                            return $item == $qa && $qa;
                        });

                        if (count($result) >= 2) {
                            $name = trans('messages.question');
                            $checkErrors['errors'][$index][] = trans('messages.error_import', ['key' => $index, 'content' => trans('validation.custom.is_similar_not_in_db', ['attribute' => $name])]);
                        }
                    }
                }
                if (isset($option['similar_question'])) {
                    $checkInvalid = array_filter($option['similar_question'], function ($item) {
                        return !$item;
                    });

                    if (count($checkInvalid) == 0) {
                        foreach ($option['similar_question'] as $key => $qa) {
                            $qa = trim(strip_tags($qa));
                            $result = array_filter($qaList, function ($item) use ($qa, $key) {
                                return $item == $qa && $qa;
                            });

                            if (count($result) >= 2) {
                                $name = count($option['similar_question'])  == 1 ? trans('messages.same_question') : trans('messages.same_question') . ($key + 1);
                                $checkErrors['errors'][$index][] = trans('messages.error_import', ['key' => $index, 'content' => trans('validation.custom.is_similar_not_in_db', ['attribute' => $name])]);
                            }
                        }
                    }
                    $options[$index]['questions'] = array_merge($option['questions'], $option['similar_question']);
                }
            }

            if (count($checkErrors['errors']) > 0) {
                ksort($checkErrors['errors']);
                $this->status = false;
                $this->message = 'Import QA error!';
                $this->errors = $checkErrors['errors'];
                return;
            }
            foreach ($options as $key => $option) {
                $answer = Answer::create([
                    'content' => htmlentities($option['content'] ?? ""),
                    'created_by' => $this->adminId,
                    'updated_by' => $this->adminId
                ]);
                $options[$key]['answer_id'] = $answer->id;
            }
            foreach ($options as $option) {
                $data = $this->fomatQuestionData($option);
                foreach ($data as $item) {
                    $question = Question::create($item);
                    $question->tags()->attach($option['hashtag']);
                    $question->categories()->attach($option['categories']);
                    $item['question_related'] = array_map(
                        function ($item) use ($question) {
                            return [
                                'question_related_id' => $item,
                                'question_id' => $question->id,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                        },
                        $item['question_related']
                    );

                    $question->questionRelateds()->insert($item['question_related']);
                }
            }
            DB::commit();
            $this->status = true;
            $this->message = trans('messages.import_success');
            return;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            throw new Exception(trans('messages.import_error'));
        }
    }

    public function headings(): array
    {
        //Put Here Header Name That you want in your excel sheet 
        return [
            'status',
            'question',
            'similar_question',
            'answer',
            'hashtag',
            'category',
            'meno',
            'question_related'
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function convertData($row, $key, $titleDB, $qaWithTag)
    {
        $data = [];
        $errors = [];
        $maxLengthQuestion = 255;
        $maxLengthAnswer = 4294967295;
        $setting = Setting::first();
        if ($setting) {
            if ($setting->question_characters) {
                $maxLengthQuestion = $setting->question_characters;
            }

            if ($setting->answer_characters) {
                $maxLengthAnswer = $setting->answer_characters;
            }
        }

        // status
        $data['status'] = 0;
        if (!empty($row['status'])) {
            $data['status'] = $row['status'] == 'ON' ? 1 : 0;
            $errors = $this->checkValueStatus($key, $row['status'], $errors);
        } else {
            $errors = $this->checkRequired(
                $key,
                'status',
                trans('messages.status_item'),
                $errors,
                $row['status']
            );
        }

        // question
        $data['questions'] = [];
        if (!empty($row['question'])) {
            $question = trim($this->replaceFile($row['question']));
            $data['questions'][] = $question;
            $textContent = trim(strip_tags($question));
            $errors = $this->checkMaxLength(
                $key,
                'question',
                trans('messages.question'),
                $errors,
                $maxLengthQuestion,
                $textContent
            );
            $isExist = $this->checkQATitle($textContent, $titleDB);
            if ($isExist) {
                $errors[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.exist_in_db', ['attribute' => trans('messages.question')])]);
            }
        } else {
            $errors = $this->checkRequired(
                $key,
                'question',
                trans('messages.question_item'),
                $errors,
                $row['question']
            );
        }

        // similar question
        if (!empty($row['similar_question'])) {
            $similarQuestions = explode(",", $row['similar_question']);
            $checkInvalid = array_filter($similarQuestions, function ($item) {
                return !$item;
            });

            if (count($checkInvalid) > 0) {
                $errors[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.invalid', ['attribute' => trans('messages.similar_question')])]);
            } else {
                if (count($similarQuestions) > 4) {

                    $errors[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.max_length_string', ['attribute' => trans('messages.similar_question'), 'max' => 4])]);;
                } else {
                    foreach ($similarQuestions as $index => $item) {
                        $nameColum = count($similarQuestions) > 1 ?  trans('messages.similar_question') . ($index + 1) : trans('messages.similar_question');
                        $item = trim($this->replaceFile($item));
                        $similarQuestions[$index] = $item;
                        $textContent = trim(strip_tags($item));
                        $errors = $this->checkMaxLength(
                            $key,
                            'similar_question',
                            $nameColum,
                            $errors,
                            $maxLengthQuestion,
                            $textContent
                        );
                        $isExist = $this->checkQATitle($textContent, $titleDB);

                        if ($isExist) {
                            $errors[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.exist_in_db', ['attribute' => $nameColum])]);
                        }
                    }
                }
            }
            $data['similar_question'] = $similarQuestions;
        }
        $data['content'] = '';
        if (!empty($row['answer'])) {
            $data['content'] = $this->replaceFile($row['answer']);
            $textContent = trim(strip_tags($data['content']));
            $errors = $this->checkMaxLength(
                $key,
                'answer',
                trans('messages.answer'),
                $errors,
                $maxLengthAnswer,
                $textContent
            );
        } else {
            $errors = $this->checkRequired(
                $key,
                'answer',
                trans('messages.answer_item'),
                $errors,
                $data['content']
            );
        }

        // hashTag
        $dataHashTag = [];
        $dataHashTagText = [];
        $dataHashtagInvalid = [];

        if (!empty($row['hashtag'])) {
            $nameColum = trans('messages.hashtag');
            $row['hashtag'] = trim($row['hashtag']);

            $hashTag = explode(",", $row['hashtag']);
            $checkInvalid = array_filter($hashTag, function ($item) {
                return !$item;
            });

            if (count($checkInvalid) > 0) {
                $errors[] = trans('messages.error_import', ['key' => $key, 'content' =>  trans('validation.custom.invalid', ['attribute' => $nameColum])]);
            } else {

                foreach ($hashTag as $index => $item) {
                    $item = trim($item);
                    if ($item) {
                        $tag = Tag::firstOrCreate(['title' => trim($item)]);
                        if ($tag) {
                            $errors = $this->checkCoincidented(
                                $key,
                                'hashtag',
                                $nameColum,
                                $errors,
                                $dataHashTagText,
                                $item
                            );
                            $dataHashTag[] = $tag->id;
                            $dataHashTagText[] = $tag->title;
                        }
                    }
                }
                if (count($dataHashtagInvalid) > 0) {
                    $errors[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.invalid', ['attribute' => $nameColum])]);
                }
            }
        }
        $data['hashtag'] = $dataHashTag;

        // note
        $data['note'] = '';
        if(!empty($row['meno'])) {
            $data['note'] = trim($row['meno']);
            $errors = $this->checkMaxLength(
                $key,
                'note',
                trans('messages.note'),
                $errors,
                100,
                $data['note']
            );
        }

        // category
        $data['categories'] = [];
        if (!empty($row['category'])) {
            $nameColum = trans('messages.category');
            $category = explode(",", $row['category']);
            $checkInvalid = array_filter($category, function ($item) {
                return !$item;
            });
            if (count($checkInvalid) > 0) {
                $errors[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.invalid', ['attribute' => $nameColum])]);
            } else {
                $dataCategory = [];
                $dataCategoryText = [];
                $dataCategoryInvalid = [];
                foreach ($category as $index => $item) {
                    $item = trim($item);
                    if ($item) {
                        $cate = Category::where('title', $item)->first();
                        if ($cate) {
                            $dataCategory[] = $cate->id;
                        } else {
                            $dataCategoryInvalid[] = $item;
                        }
                        $errors = $this->checkCoincidented(
                            $key,
                            'category',
                            $nameColum,
                            $errors,
                            $dataCategoryText,
                            $item
                        );
                        $dataCategoryText[] = $item;
                    }
                }
                if (count($dataCategoryInvalid) > 0) {
                    $errors[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.invalid', ['attribute' => $nameColum])]);
                }
                $data['categories'] = $dataCategory;
            }
        } else {
            $nameColum = trans('messages.category');
            $errors = $this->checkRequired(
                $key,
                'answer',
                $nameColum,
                $errors,
                $data['category']
            );
        }

        $dataQaRelated = [];
        // question related
        if (!empty($row['question_related'])) {
            $row['question_related'] = trim($row['question_related']);

            $nameColum = trans('messages.question_related');
            $questionRelated = explode(",", $row['question_related']);
            $checkInvalid = array_filter($questionRelated, function ($item) {
                return !$item;
            });

            if (count($checkInvalid) > 0) {
                $errors[] = trans('messages.error_import', ['key' => $key, 'content' =>  trans('validation.custom.invalid', ['attribute' => $nameColum])]);
            } else {
                if (count($questionRelated) > 3) {

                    $errors[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.max_length_string', ['attribute' => trans('messages.similar_question'), 'max' => 3])]);
                } else {
                    foreach ($questionRelated as $index => $item) {
                        $nameColum = trans('messages.question_related');
                        $item = trim($this->replaceFile($item));
                        $similarQuestions[$index] = $item;
                        $textContent = trim(strip_tags($item));

                        if (isset($qaWithTag[$textContent]) && array_intersect($dataHashTag, array_values($qaWithTag[$textContent])[0])) {
                            $dataQaRelated[] = array_key_first($qaWithTag[$textContent]);
                        } else {
                            $errors[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.invalid', ['attribute' => $nameColum])]);
                            break;
                        }
                    }
                }
            }
        }

        $data['question_related'] =  $dataQaRelated;

        return [
            'data' => $data,
            'errors' => $errors
        ];
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
        $titles = $request['questions'];
        for ($i = 0; $i < count($titles); $i++) {
            $data[$i]['title'] =  $titles[$i];
            $data[$i]['status'] =  $request['status'];
            $data[$i]['note'] =  trim($request['note'] ?? "");
            $data[$i]['answer_id'] =  $request['answer_id'];
            $data[$i]['question_related'] = $request['question_related'];
            $data[$i]['created_by'] =  $this->adminId;
            $data[$i]['updated_by'] = $this->adminId;
        }
        return $data;
    }

    public function replaceFile($data)
    {
        preg_match_all(self::PATTERN, $data, $arr);
        preg_match_all(self::PATTERN_LINK, $data, $arrLink);
        if (count($arr[0]) == 0 && count($arrLink[0]) == 0) {
            return $data;
        }

        foreach ($arrLink[0] as $strReplace) {
            $arrString =  explode("|", $strReplace);
            $textLink = $arrString[0];
            $url = $arrString[1];
            $data = str_replace(
                '[@' . $textLink . '|' . $url . ']',
                '<p><a href="' . $url . '" target="_blank">' . $textLink . '</a></p>',
                $data
            );
        }

        foreach ($arr[0] as $strReplace) {
            $arrString =  explode(".", $strReplace);
            if (in_array(strtolower($arrString[count($arrString) - 1]), self::IMAGE_TYPE)) {
                $data = str_replace('[!' . $strReplace . ']', '<p><img style="width: 70%; display:block" src="' . $this->zipPath . $strReplace . '" > </p>', $data);
            } else if (in_array(strtolower($arrString[count($arrString) - 1]), self::VIDEO_TYPE)) {
                $data = str_replace(
                    '[!' . $strReplace . ']',
                    '<p><video width="70%" height="300" controls style="display:block">
                    <source src="' . $this->zipPath . $strReplace . '" type="video/mp4">
                    </video></p>',
                    $data
                );
            } else {
                // if (in_array($arrString[count($arrString) - 1], self::DOCS_TYPE)) {
                $data = str_replace(
                    '[!' . $strReplace . ']',
                    '<p><a href="' . $this->zipPath . $strReplace . '" target="_blank">' . $strReplace . '</a></p>',
                    $data
                );
            }
        }
        return $data;
    }

    public function checkQATitle($title, $titleDB)
    {
        return in_array($title, $titleDB);
    }

    public function checkMaxLength($key, $column, $name, $error, $maxLength, $text)
    {
        if (mb_strlen($text) > $maxLength) {
            $error[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.max_length_string', ['attribute' => $name, 'max' => $maxLength])]);
        }
        return $error;
    }

    public function checkRequired($key, $column, $name, $error, $text)
    {
        if (!$text) {
            $error[] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.import_required', ['attribute' => $name])]);
        }
        return $error;
    }

    public function checkCoincidented($key, $column, $name, $error, $arr, $value)
    {
        if (in_array($value, $arr)) {
            if ($column == 'hashtag' || $column == 'category')
                $error[$value] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.same_data_category_and_hashtag', ['attribute' => $name])]);
            else
                $error[$value] = trans('messages.error_import', ['key' => $key, 'content' => trans('validation.custom.exist_in_db', ['attribute' => $name])]);
        }
        return $error;
    }

    public function checkValueStatus($key, $value, $errors)
    {
        $nameColum = trans('messages.status_item');
        $data = ['ON', 'OFF'];
        if (!in_array($value, $data)) {
            $errors[] = trans('messages.error_import', ['key' => $key, 'content' =>  trans('validation.custom.invalid', ['attribute' => $nameColum])]);
        }
        return $errors;
    }
}
