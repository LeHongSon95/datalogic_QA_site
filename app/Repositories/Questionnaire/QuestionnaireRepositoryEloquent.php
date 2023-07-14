<?php

namespace App\Repositories\Questionnaire;

use App\Models\Questionnaire;
use App\Repositories\BaseRepository;
use Exception;

/**
 * Class QuestionnaireRepositoryEloquent.
 *
 * @package namespace App\Repositories\Questionnaire;
 */
class QuestionnaireRepositoryEloquent extends BaseRepository implements QuestionnaireRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function getModel()
    {
        return Questionnaire::class;
    }

    public function getList($request,$paginate)
    {

        $result = $this->model
            ->has('question')
            ->orderByDesc('updated_at')
            ->where('status', config('constants.questionnaire_status.not_resolved'));
        if ($paginate) {
            $result->limit($paginate);
        }
        if ($request->s) {
            $result->where('content', 'like', '%' . strip_tags(html_entity_decode($request->s)) . '%')
                ->orWhereHas('question', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->s . '%')
                        ->orWhere('title', 'like', '%' . strip_tags(html_entity_decode($request->s)) . '%');
                });
        }

        return $result->get();
    }
}
