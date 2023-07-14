<?php

namespace App\Http\Controllers\Frontend;

use App\Events\ViewQAEvent;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionnaireRequest;
use App\Repositories\Answer\AnswerRepository;
use App\Repositories\Question\QuestionRepository;
use Illuminate\Http\Request;

class QAController extends Controller
{
    /**
     * @var $questionRepository
     */
    protected $questionRepository, $answerRepository;

    /**
     * function constructor.
     *
     * @param QuestionManageService $questionService
     * @param AnswerManageService $answerService
     */
    public function __construct(QuestionRepository $questionRepository, AnswerRepository $answerRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
    }

    /**
     * Detail QA
     * 
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        $qa = $this->questionRepository->detail($id);
        if ($qa) {
            $this->increaseView($id);
            $data = $qa->questionRelateds->pluck('question_related_id');
            $questionRelated = $this->questionRepository->getQuestionRelateds($data);
            $cookiesName = config('constants.nameCookieSetFavorite');
            $qaFavoriteCookies = Helper::getCookie($cookiesName);
            if (!$qaFavoriteCookies) {
				$qaFavoriteCookies =  [];
			} 
            return view('frontend.qa.show', compact('qa', 'questionRelated', 'qaFavoriteCookies'));
        }
        return redirect()->back();
    }

    /**
     * increase view count QA
     * 
     * @param int $id
     * @return void
     */
    public function increaseView($id)
    {
        event(new ViewQAEvent($id));
        return;
    }

    /**
     * Store questionnaire
     * 
     * @param StoreQuestionnaireRequest $request
     * @param int $id
     * 
     * @return void
     */
    public function createQuestionnaire($id, StoreQuestionnaireRequest $request)
    {
        return $this->questionRepository->storeQuestionnaire($id, $request);
    }

    public function updateFavoriteCount($id)
    {
        $cookiesName = config('constants.nameCookieSetFavorite');
        $qaFavoriteCookies = [];
        if (Helper::issetCookies($cookiesName)) {
            $qaFavoriteCookies = Helper::getCookie($cookiesName);
        }

        $qa = $this->questionRepository->find($id);
        if ($qa) {
            if (in_array($qa->id, $qaFavoriteCookies)) {
                $qa->decrement('favorite_count');
                $qaFavoriteCookies = array_filter($qaFavoriteCookies, function ($item) use ($id) {
                    return $item != $id;
                });
            } else {
                $qa->increment('favorite_count');
                $qaFavoriteCookies[] = $id;
            }
        }

        Helper::setCookie($cookiesName, $qaFavoriteCookies);
        if(request()->ajax()){
            return response()->json(['qa_favorite' => count($qaFavoriteCookies)]);
        }
        return redirect()->back();
    }
}
