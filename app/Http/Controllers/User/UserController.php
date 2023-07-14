<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;

/**
 * Class UserController
 */
class UserController extends Controller
{
    /**
     * @var $userRepository
     */
    public $userRepository;

    /**
     * function constructor.
     *
     * @param UserManageService $userService
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * get list users
     * @return resource
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->getUserList($request);
            return view('user.index', compact('users'));
        } catch (\Exception $e) {
            return view('errors.error', ['message' => trans('text.error')]);
        }
    }
}
