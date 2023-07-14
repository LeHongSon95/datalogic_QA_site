<?php
namespace App\Repositories\Favorite;

use App\Repositories\RepositoryInterface;

/**
 * Interface FavoriteRepository.
 *
 * @package namespace App\Repositories\User;
 */
interface FavoriteRepository extends RepositoryInterface
{
	/**
     * get user list
     */
    public function getListQA($ids, $paginate, $order);
}