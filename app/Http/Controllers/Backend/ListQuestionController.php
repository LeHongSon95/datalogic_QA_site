<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Helper;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Question;
use App\Repositories\Answer\AnswerRepositoryEloquent;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\FeaturedTag\FeaturedTagRepository;
use App\Repositories\Question\QuestionRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ListQuestionController extends Controller
{

    /**
     * Create a new controller instance.
     */
    protected $categoryRepository, $answerRepository;
    protected $questionRepository;
    protected $featuredTagRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        QuestionRepository $questionRepository,
        FeaturedTagRepository $featuredTagRepository,
        AnswerRepositoryEloquent $answerRepository

    ) {
        $this->categoryRepository = $categoryRepository;
        $this->questionRepository = $questionRepository;
        $this->featuredTagRepository = $featuredTagRepository;
        $this->answerRepository = $answerRepository;
    }

    /**
     * Show data for home page.
     */
    public function index(Request $request)
    {
        try {
            // get data question
            $getQuestions = $this->questionRepository->getQuestionPaginate($request, Config::get('constants.paginate'), true);

            $data['searchKeywordHistory'] = $getQuestions['searchKeywordHistory'];

            $data['countQuestions'] = $getQuestions['countQuestions'];

            $data['questions'] = $getQuestions['dataQuestions'];

            $questionRecommend = $this->questionRepository->getQuestionHasRecommend();
            $data['questionRecommend'] = $questionRecommend;
            // get categories show sidebar
            $data['categories'] = $this->categoryRepository->getAllCategoryWithCountQuestions();
            // get featured keywords show sidebar
            $data['featured_keywords'] = $this->featuredTagRepository->getAllOrderBy();
            $data['isSearch'] =  $getQuestions['isSearch'];
            $setting = Setting::first();
            $data['setting'] =  $setting;

            $cookiesName = config('constants.nameCookieSetFavorite');
            $data['qaFavoriteCookies'] = [];
            if (Helper::issetCookies($cookiesName)) {
                $data['qaFavoriteCookies'] = Helper::getCookie($cookiesName);
            }
            // show home page
            return view('backend.dashboard.index')->with($data);
        } catch (Exception $e) {
            Log::error($e);
            abort(404);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Question::find($id)->delete($id);

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }
}
