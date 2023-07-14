<?php
namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
	/**
	 * Specify Model class name
	 *
	 * @return string
	 */
	public function getModel(): string
	{
		return Category::class;
	}
	
	// get all category has total question
	public function getAllCategoryWithCountQuestions()
	{
		try {
			return $this->model
				->select('categories.id', 'categories.title')
				->withCount(['questions as question_count' => function($q) {
					$q->active();
				}])->get();	
		} catch (Exception $e) {
			Log::error($e);
			abort(404);
		}
	}

	// get category filter
	public function getCategoryFilter(int $catID, array $questionIDs)
	{
		try {
			$categories = $this->model
				->select('categories.id', 'categories.title')
				->withCount(['questions as question_count' => function ($query) use( $questionIDs ) {
					$query->select( DB::raw('count( distinct( questions.id ) )') )
						->whereIn('questions.id', $questionIDs)
						->join('answers', 'questions.answer_id', '=', 'answers.id');
				}]);
			
			// filter has cat id
			if ( $catID ) {
				$categories->where('categories.id', '=', $catID);
			}
			
			return $categories->get();
			
		} catch (Exception $e) {
			Log::error($e);
			abort(404);
		}
	}
}