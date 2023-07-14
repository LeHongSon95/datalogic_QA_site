<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Questionnaire\QuestionnaireRepository;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    /**
     * @var $questionnaireRepository
     */
    protected $questionnaireRepository;

    /**
     * function constructor.
     *
     * @param QuestionnaireManageService $questionnaireService
     */
    public function __construct(QuestionnaireRepository $questionnaireRepository)
    {
        $this->questionnaireRepository = $questionnaireRepository;
    }

    public function index(Request $request)
    {
        $questionnaires = $this->questionnaireRepository->getList($request, $paginate = '');
        return view('backend.questionnaire.index', compact('questionnaires'));
    }
}
