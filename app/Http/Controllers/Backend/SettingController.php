<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteFeaturedTagRequest;
use App\Http\Requests\DeleteQuestionRecommendRequest;
use App\Http\Requests\SettingRequest;
use App\Http\Requests\StoreFeaturedTagRequest;
use App\Http\Requests\StoreQuestionRecommendRequest;
use App\Models\Setting;
use App\Repositories\FeaturedTag\FeaturedTagRepository;
use App\Repositories\Question\QuestionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     */
    protected $featuredTagRepository;
    protected $questionRepository;

    public function __construct(
        FeaturedTagRepository $featuredTagRepository,
        QuestionRepository $questionRepository
    ) {
        $this->featuredTagRepository = $featuredTagRepository;
        $this->questionRepository = $questionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = $this->questionRepository
            ->all('id asc', null, ['id', 'title'])
            ->with('questionRecommend')
            ->get();
        $questionsRecommendeds = $questions->filter(function ($item) {
            return $item->questionRecommend;
        });

        $featured_keywords = $this->featuredTagRepository->getAllOrderBy();
        $setting = Setting::first();
        return view('backend.setting.index', compact('questions', 'featured_keywords', 'questionsRecommendeds', 'setting'));
    }

    /**
     * Store a new feature tag.
     *
     * @param  StoreFeaturedTagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createFeaturedTag(StoreFeaturedTagRequest $request)
    {
        $result = $this->featuredTagRepository->store($request);
        if ($result) {
            return redirect()->back()->withSuccess('Create tag success!');
        }
        return redirect()->back()->withErrors('Create tag error!');
    }

    /**
     * Remove feature tag.
     *
     * @param  DeleteFeaturedTagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteFeaturedTag(DeleteFeaturedTagRequest $request)
    {
        $result = $this->featuredTagRepository->delete($request->featured_tag_id);
        if ($result) {
            return redirect()->back()->withSuccess('Delete tag success!');
        }
        return redirect()->back()->withErrors('Delete tag error!');
    }

    public function updateSortFeaturedTag(Request $request)
    {
        $result = $this->featuredTagRepository->updateSortFeaturedTag($request->data);
        if ($result) {
            return response()->json('success');
        }
        return response()->json('error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Store a new question recommend.
     *
     * @param  StoreQuestionRecommendRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createQuestionRecommend(StoreQuestionRecommendRequest $request)
    {
        $result = $this->questionRepository->storeRecommend($request);
        if ($result) {
            return redirect()->back()->withSuccess('Create question recommend success!');
        }
        return redirect()->back()->withErrors('Create question recommend error!');
    }

    /**
     * Remove question recommend.
     *
     * @param  DeleteQuestionRecommendRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteQuestionRecommend(DeleteQuestionRecommendRequest $request)
    {
        $result = $this->questionRepository->deleteRecommend($request->recomanded_id);
        if ($result) {
            return redirect()->back()->withSuccess('Delete question recommend success!');
        }
        return redirect()->back()->withErrors('Delete question recommend error!');
    }

    /**
     * update setting.
     *
     * @param  DeleteQuestionRecommendRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSetting(SettingRequest $request)
    {
        try {
            DB::beginTransaction();
            $setting = Setting::first();
            if (!$setting) {
                $setting = Setting::create();
            }

            $data = [];
            if ($request->has('question_characters')) {
                $data['question_characters'] = $request->question_characters;
            }

            if ($request->has('answer_characters')) {
                $data['answer_characters'] = $request->answer_characters;
            }

            if ($request->has('number_recently_viewed_keywords')) {
                $data['number_recently_viewed_keywords'] = $request->number_recently_viewed_keywords;
            }
            $setting->update($data);
            DB::commit();
            return redirect()->withSuccess('Update success');
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return redirect()->back()->withErrors('Update error!');
        }
    }
}
