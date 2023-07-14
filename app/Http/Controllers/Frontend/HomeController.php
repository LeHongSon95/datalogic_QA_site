<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\FeaturedTag\FeaturedTagRepository;
use App\Repositories\Question\QuestionRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 */
	protected $categoryRepository;
	protected $questionRepository;
	protected $featuredTagRepository;

	public function __construct(
		CategoryRepository $categoryRepository,
		QuestionRepository $questionRepository,
		FeaturedTagRepository $featuredTagRepository
	) {
		$this->categoryRepository = $categoryRepository;
		$this->questionRepository = $questionRepository;
		$this->featuredTagRepository = $featuredTagRepository;
	}

	/**
	 * Show data for home page.
	 */
	public function index(Request $request)
	{
		try {
			// get data question
			$getQuestions = $this->questionRepository->getQuestionPaginate($request, Config::get('constants.paginate'));
			$getQuestionsQA = $this->questionRepository->getQuestionPaginateQA($request, Config::get('constants.itemsPerPageQA'));
			
			$data['searchKeywordHistory'] = $getQuestions['searchKeywordHistory'];

			$data['countQuestions'] = $getQuestions['countQuestions'];

			$data['questions'] = $getQuestions['dataQuestions'];
			$dataAQ['questions'] = $getQuestionsQA['dataQuestions'];
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
			return view('frontend.home.index')->with($data,$dataAQ);
		} catch (Exception $e) {
			Log::error($e);
			abort(404);
		}
	}

	/**
	 * Show dashboard for home page.
	 */
	public function dashboard()
	{
		return view('frontend.pages.dashboard');
	}

	/**
	 * Show detail for home page.
	 */
	public function detail()
	{
		return view('frontend.pages.detail');
	}
}
