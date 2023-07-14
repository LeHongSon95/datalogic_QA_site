<?php
namespace App\Repositories\Category;

interface CategoryRepository
{
	public function getAllCategoryWithCountQuestions();
	public function getCategoryFilter(int $catID, array $questionIDs);
}