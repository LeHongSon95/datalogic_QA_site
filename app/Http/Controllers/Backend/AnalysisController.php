<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Helper;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Repositories\Favorite\FavoriteRepositoryEloquent;
use App\Http\Controllers\Controller;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Questionnaire\QuestionnaireRepository;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    protected $question;
    protected $questionRepository;
    protected $questionnaire;
    const PAGE_SIZE_DEFAULT = 3;
    const PAGE_SIZE_DEFAULT_S = 3;
    const ORDER_BY = 'registration_asc';

    public function __construct(
        QuestionRepository $questionRepository,
        FavoriteRepositoryEloquent $question,
        QuestionnaireRepository $questionnaire
    ) {
        $this->questionRepository = $questionRepository;
        $this->question = $question;
        $this->questionnaire = $questionnaire;
    }
    public function index(Request $request)
    {
        try {
            $cookiesName = config('constants.nameCookieSetFavorite');
            $qaFavoriteCookies = Helper::getCookie($cookiesName);
            if (!$qaFavoriteCookies) {
                $qaFavoriteCookies =  [];
            }
            $question = $this->question->getListQA(
                $qaFavoriteCookies,
                $request->items_per_page ?? self::PAGE_SIZE_DEFAULT,
                $request->order_by ?? self::ORDER_BY

            );
            $questionnaire = $this->questionnaire->getList(
                $request,
                $request->items_per_page ?? self::PAGE_SIZE_DEFAULT_S,
                $request->order_by ?? self::ORDER_BY
            );

            $getQuestionsQA = $this->questionRepository->getQuestionPaginateQA(
                $request,
                Config::get('constants.itemsPerPageQA')
            );
            $data['questions'] = $getQuestionsQA['dataQuestions'];

            return view('backend.analysis.index', [
                'questionnaire' => $questionnaire, 
                'data' => $question, 
                'qaFavoriteCookies' => $qaFavoriteCookies])
                ->with($data);
        } catch (Exception $e) {
            Log::error($e);
            abort(404);
        }
    }
}
