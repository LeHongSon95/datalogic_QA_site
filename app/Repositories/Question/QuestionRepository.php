<?php

namespace App\Repositories\Question;

use App\Repositories\RepositoryInterface;

/**
 * Interface QuestionRepository.
 *
 * @package namespace App\Repositories\Question;
 */
interface QuestionRepository extends RepositoryInterface
{
    public function getList($request);
    public function store($request);
    public function detail($id);
    public function updateView($id);
	public function getQuestionPaginate($request, $paginate);
    public function getQuestionPaginateQA($request, $paginateQA);
    public function storeQuestionnaire($id, $request);
    public function storeRecommend($request);
    public function deleteRecommend($id);
    public function getQuestionHasRecommend();
    public function getQuestionRelateds($questionIds);
}
