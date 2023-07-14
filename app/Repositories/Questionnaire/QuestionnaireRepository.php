<?php

namespace App\Repositories\Questionnaire;

use App\Repositories\RepositoryInterface;

/**
 * Interface QuestionnaireRepository.
 *
 * @package namespace App\Repositories\Questionnaire;
 */
interface QuestionnaireRepository extends RepositoryInterface
{
  public function getList($request, $paginate);
}
