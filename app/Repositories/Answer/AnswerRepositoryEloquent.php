<?php
namespace App\Repositories\Answer;

use App\Models\Answer;
use App\Repositories\BaseRepository;
use App\Repositories\Answer\AnswerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class AnswerRepositoryEloquent.
 *
 * @package namespace App\Repositories\Answer;
 */
class AnswerRepositoryEloquent extends BaseRepository implements AnswerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function getModel()
    {
        return Answer::class;
    }

    /**
     * api download answer
     * @param Request $request
     * @return Response
     */
    public function getList($request)
    {
        $result = $this->all();
        return $result;
    }

    /**
     * api create answer
     * @param Request array $request
     * @return Response
     */
    public function store($request)
    {
        $result = $this->create($request);
        return $result;
    }
}
