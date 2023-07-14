<?php

namespace App\Repositories\Favorite;

use App\Models\Question;
use App\Repositories\BaseRepository;
use App\Repositories\Favorite\FavoriteRepository;
use Illuminate\Support\Facades\Config;

/**
 * Class QuestionRepositoryEloquent.
 *
 * @package namespace App\Repositories\User;
 */
class FavoriteRepositoryEloquent extends BaseRepository implements FavoriteRepository
{
	public function getModel()
	{
		return Question::class;
	}
	public function getListQA($ids, $paginate, $order)
	{
		$result = $this->model
		->select(['id', 'title', 'answer_id', 'note', 'view_count','favorite_count'])
		->with('categories', 'answers:id,content')
		->active()
		->whereIn('id', $ids);
		if ($order && in_array($order, Config::get('constants.orders'))) {
			// order questions by question id asc
			if ($order == Config::get('constants.orders.registration_asc')) {
				$result->orderBy('questions.id', 'asc');
			}
			// order questions by question id desc

			if ($order == Config::get('constants.orders.registration_desc')) {
				$result->orderBy('questions.id', 'desc');
			}

			// order questions by view count desc
			if ($order == Config::get('constants.orders.reading')) {
				$result->orderBy('questions.view_count', 'asc')
					->orderBy('questions.id', 'asc');
			}

			// order questions by favorites
			if ($order == Config::get('constants.orders.number_of_favorites')) {
				$result->orderBy('questions.favorite_count', 'asc')
					->orderBy('questions.id', 'asc');
			}
		}
		
		return $result->paginate($paginate);
	}
}
