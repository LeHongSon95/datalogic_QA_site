<?php

namespace App\Repositories\FeaturedTag;

use App\Models\FeaturedTag;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeaturedTagRepositoryEloquent extends BaseRepository implements FeaturedTagRepository
{
	/**
	 * Specify Model class name
	 *
	 * @return string
	 */
	public function getModel(): string
	{
		return FeaturedTag::class;
	}

	/**
	 * get featured keywords
	 *
	 * @return mixed
	 */
	public function getAllOrderBy()
	{
		return $this->model
			->select(['id', 'title', 'order', 'is_featured'])
			->where('is_featured', FeaturedTag::FEATURED)
			->orderBy('order')
			->get();
	}

	// create tag
	public function store($request)
	{
		DB::beginTransaction();
		try {
			$maxOrder = FeaturedTag::max('order');
			$this->model->create([
				'title' => $request->featured_tag_name,
				'is_featured' => FeaturedTag::FEATURED,
				'order' => $maxOrder + 1
			]);
			DB::commit();
			return true;
		} catch (\Throwable $th) {
			Log::error($th);
			DB::rollBack();
			return false;
		}
	}

	// remove tag
	public function delete($id)
	{
		DB::beginTransaction();
		try {
			$featuredTag = $this->model->whereIn('id', json_decode($id));

			if ($featuredTag) {
				$featuredTag->delete();
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

	public function updateSortFeaturedTag($data)
	{
		try {
			DB::beginTransaction();
			$result = $this->model->upsert($data, ['id', 'title', 'is_featured'], ['order']);
			DB::commit();
			return $result;
		} catch (\Throwable $th) {
			DB::rollBack();
			return false;
		}
	}
}
