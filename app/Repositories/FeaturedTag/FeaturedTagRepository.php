<?php
namespace App\Repositories\FeaturedTag;

interface FeaturedTagRepository
{
	public function getAllOrderBy();
	public function store($request);
	public function delete($id);
	public function updateSortFeaturedTag($data);
}