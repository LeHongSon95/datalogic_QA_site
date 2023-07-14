<?php
namespace App\Repositories\Answer;

use App\Repositories\RepositoryInterface;

/**
 * Interface AnswerRepository.
 *
 * @package namespace App\Repositories\Answer;
 */
interface AnswerRepository extends RepositoryInterface
{
	/**
     * get user list
     */
    public function getList($request);
    public function store($request) ;
}
