<?php
namespace App\Repositories\User;

use App\Repositories\RepositoryInterface;

/**
 * Interface UserRepository.
 *
 * @package namespace App\Repositories\User;
 */
interface UserRepository extends RepositoryInterface
{
	/**
     * get user list
     */
    public function getUserList($request);
}
