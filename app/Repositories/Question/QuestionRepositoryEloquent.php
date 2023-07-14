<?php
namespace App\Repositories\Question;

use App\Helpers\Helper;
use App\Models\Question;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class QuestionRepositoryEloquent.
 *
 * @package namespace App\Repositories\Question;
 */
class QuestionRepositoryEloquent extends BaseRepository implements QuestionRepository
{
	/**
	 * Specify Model class name
	 *
	 * @return string
	 */
	public function getModel()
	{
		return Question::class;
	}

	/**
	 * api download question
	 * @param Request $request
	 * @return Response
	 */
	public function getList($request)
	{
		$result = $this->model->active()->get();
		return $result;
	}

	/**
	 * api create question
	 * @param Request array $request
	 * @return Response
	 */
	public function store($request)
	{
		$result = $this->create($request);
		return $result;
	}

	/**
	 * api create question
	 * @param Request $request
	 * @return Response
	 */
	public function detail($id)
	{
		$result = $this->model->with(['answers', 'tags', 'categories', 'questionRelateds'])->find($id);
		return $result;
	}

	/**
	 * get data question paginate for view
	 * @param $request
	 * @param $paginate
	 * @return array
	 */
	public function getQuestionPaginate($request, $paginate, $isAdmin = false): array
	{
		try {
			// query questions
			$questions = $this->model
				->select(['id', 'title', 'answer_id', 'note', 'view_count', 'favorite_count'])
				->with('categories', 'answers');
			if (!$isAdmin) {
				$questions = $questions->active();
			}
			$check = $questions;
			$result['countQuestions'] = $check->count();
			$isSearch = false;

			// check has request
			if (count($request->all())) {
				// query key word
				$s = $request->input('s');
				if ($s) {
					$isSearch = true;
					// replace space
					$newKeyWords = Helper::pregReplaceSingleSpace($s);

					if (!empty($newKeyWords)) {
						$newKeyWordsExplodeArr = array();

						// array with array as or query
						$newKeyWordsExplodeOr = array_filter(array_map('trim', explode("|", $newKeyWords)));

						if ($newKeyWordsExplodeOr) {
							foreach ($newKeyWordsExplodeOr as $newKeyWord) {
								// in a query array and
								$newKeyWordsExplodeAnd = explode(" ", $newKeyWord);

								// keyword array value assignment
								$newKeyWordsExplodeArr[] = $newKeyWordsExplodeAnd;
							}

							// query search by key word
							$titleSearch = $request->input('title_search');
							$FullTextSearch = $request->input('full_text_search');

							$questions->where(function ($query) use ($newKeyWordsExplodeArr, $titleSearch, $FullTextSearch) {

								foreach ($newKeyWordsExplodeArr as $itemKeyWords) {
									// orWhere array vs array
									$query->orWhere(function ($query) use ($itemKeyWords, $titleSearch, $FullTextSearch) {

										foreach ($itemKeyWords as $itemKeyWord) {
											// Where value in array
											$query->where(function ($query) use ($itemKeyWord, $titleSearch, $FullTextSearch) {

												if ($titleSearch && $FullTextSearch) {
													$query->where('title', 'like',  '%' . $itemKeyWord . '%')
														->orWhereHas('answers', function (Builder $query) use ($itemKeyWord) {
															$query->where('answers.content', 'like', '%' . $itemKeyWord . '%');
														});
												} else {
													$query->where('title', 'like',  '%' . $itemKeyWord . '%');
												}
											});
										}
									});
								}
							});

							/* set cookie for keywords */
							$keywords = [];
							$oldKeyWords = Helper::getCookie(Config::get('constants.nameCookieSearchKeywordHistory'));

							if ($oldKeyWords) {
								$keywords[] = $oldKeyWords;
							}

							// merge multiple arrays from one array
							$mergeKeywords = call_user_func_array("array_merge", $newKeyWordsExplodeArr);

							// remove duplicate
							$mergeKeywordsUnique = array_unique($mergeKeywords);

							// prepend one or more elements to the beginning of an array
							array_unshift($keywords, $mergeKeywordsUnique);

							// create cookie search keyword history
							$searchKeywordHistory = array_unique(call_user_func_array("array_merge", $keywords));
							Helper::setCookie(Config::get('constants.nameCookieSearchKeywordHistory'), array_values($searchKeywordHistory));
						}
					}
				}

				// query by category id
				$cate_id = $request->input('cate_id');
				if ($cate_id) {
					$isSearch = true;
					$questions->whereHas('categories', function (Builder $query) use ($cate_id) {
						$query->where('categories.id', '=', $cate_id);
					});
				}

				// query orderBy
				$order_by = $request->input('order_by');
				if ($order_by && in_array($order_by, Config::get('constants.orders'))) {
					// order questions by question id asc
					if ($order_by == Config::get('constants.orders.registration_asc')) {
						$questions->orderBy('questions.id', 'asc');
					}

					// order questions by question id desc

					if ($order_by == Config::get('constants.orders.registration_desc')) {
						$questions->orderBy('questions.id', 'desc');
					}

					// order questions by view count desc
					if ($order_by == Config::get('constants.orders.reading')) {
						$questions->orderBy('questions.view_count', 'desc')
							->orderBy('questions.id', 'asc');
					}

					// order questions by favorites
					if ($order_by == Config::get('constants.orders.number_of_favorites')) {
						$questions->orderBy('questions.favorite_count', 'desc')
							->orderBy('questions.id', 'asc');
					}
				}

				// set paginate
				$items_per_page = $request->input('items_per_page');

				if ($items_per_page && in_array($items_per_page, Config::get('constants.itemsPerPage'))) {
					$paginate = $items_per_page;
				}
			}
			// get search keyword cookie
			$result['searchKeywordHistory'] = !empty($searchKeywordHistory) ? $searchKeywordHistory :
				Helper::getCookie(Config::get('constants.nameCookieSearchKeywordHistory'));
			// order default
			if (!count($request->all()) && !$request->input('order_by')) {
				$questions->orderBy('questions.id', 'asc');
			}


			// count all questions

			// get all question ids use when search
			$result['questionIds'] = $questions->pluck('id')->toArray();

			// data paginate
			$result['dataQuestions'] = $questions->paginate($paginate);

			$result['isSearch'] = $isSearch;



			return $result;
		} catch (Exception $e) {
			Log::error($e);
			abort(404);
		}
	}
	public function getQuestionPaginateQA($request, $paginateQA, $isAdmin = false): array
	{
		try {
			// query questions
			$questions = $this->model
				->select(['id', 'title', 'answer_id', 'note', 'view_count', 'favorite_count'])
				->with('categories', 'answers');

			if (!$isAdmin) {
				$questions = $questions->active();
			}
			$check = $questions;
			$result['countQuestions'] = $check->count();
			$isSearch = false;

			$order_by = $request->input('order_by');
				if ($order_by && in_array($order_by, Config::get('constants.orders'))) {
					// order questions by question id asc
					if ($order_by == Config::get('constants.orders.registration_asc')) {
						$questions->orderBy('questions.id', 'asc');
					}

					// order questions by question id desc

					if ($order_by == Config::get('constants.orders.registration_desc')) {
						$questions->orderBy('questions.id', 'desc');
					}

					// order questions by view count desc
					if ($order_by == Config::get('constants.orders.reading')) {
						$questions->orderBy('questions.view_count', 'desc')
							->orderBy('questions.id', 'asc');
					}

					// order questions by favorites
					if ($order_by == Config::get('constants.orders.number_of_favorites')) {
						$questions->orderBy('questions.favorite_count', 'desc')
							->orderBy('questions.id', 'asc');
					}
				}
			
			$itemsPage = $request->input('itemsPage');

			if ($itemsPage && in_array($itemsPage, Config::get('constants.itemsPerPageQA'))) {
				$paginateQA = $itemsPage;
			}
			// data paginate
			$result['dataQuestions'] = $questions->limit($paginateQA)->get();

			$result['isSearch'] = $isSearch;

			return $result;
		} catch (Exception $e) {
			Log::error($e);
			abort(404);
		}
	}

	/**
	 * update view count QA
	 * @param int $id
	 * @return Response
	 */
	public function updateView($id)
	{
		$result = $this->model
			->select(['id', 'view_count'])
			->find($id)->increment('view_count', 1);
		return $result;
	}

	/**
	 * Store questionnaire
	 * 
	 * @param StoreQuestionnaireRequest $request
	 * @param int $id
	 * 
	 * @return void
	 */
	public function storeQuestionnaire($id, $request)
	{
		try {
			$question = $this->getById($id);
			if ($question) {
				$question->questionnaires()->create([
					'question_id' => $id,
					'content' => htmlentities($request->content ?? ""),
					'status' => $request->status,
				]);
				return redirect()->back()->withSuccess('Send questionnaire success!');
			}
			return redirect()->route('frontend.home.index')->withErrors('This QA not exist!');
		} catch (\Throwable $th) {
			Log::error($th);
			return redirect()->back()->withErrors('Send questionnaire error!');
		}
	}

	// create question recommend
	public function storeRecommend($request)
	{
		$question = $this->getById($request->qa_recomended);
		$question->questionRecommend()->create(['question_id' => $request->qa_recomended]);
		return true;
	}

	// remove question recommend
	public function deleteRecommend($id)
	{
		try {
			DB::beginTransaction();
			$questions = $this->model
				->with('questionRecommend')
				->whereIn('id', json_decode($id))
				->get();
			if ($questions) {
				foreach ($questions as $question) {
					$question->questionRecommend()->delete();
				}
				DB::commit();
				return true;
			}

			return false;
		} catch (\Throwable $th) {
			Log::error($th);
			DB::rollBack();
			return false;
		}
	}

	// get question has recommend
	public function getQuestionHasRecommend()
	{
		$question = $this->model
			->with('categories')
			->whereHas('questionRecommend')
			->select(['id', 'title', 'answer_id', 'note'])
			->active()
			->orderByDesc('id')
			->get();
		return $question;
	}

	// get question has recommend
	public function getQuestionRelateds($questionIds)
	{
		$questions = $this->model->active()->whereIn('id', $questionIds)->get();
		return $questions;
	}
}
