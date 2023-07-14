<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use Exception;
use App\Http\Controllers\Controller;
use App\Repositories\Favorite\FavoriteRepositoryEloquent;
use Illuminate\Http\Request;
use App\Repositories\Question\QuestionRepository;
use Illuminate\Support\Facades\Config;

class FavoriteController extends Controller
{
    protected $question;
    protected $questionRepository;
    const PAGE_SIZE_DEFAULT = 10;
    const ORDER_BY = 'registration_asc';
    public function __construct(
        FavoriteRepositoryEloquent $question,
        QuestionRepository $questionRepository
    ) {
        $this->question = $question;
        $this->questionRepository = $questionRepository;
    }
    public function index(Request $request)
    {
        try {
            $cookiesName = config('constants.nameCookieSetFavorite');
            $qaFavoriteCookies = Helper::getCookie($cookiesName);
            if (!$qaFavoriteCookies) {
                $qaFavoriteCookies =  [];
            }
            $question = $this->question->getListQA(
                $qaFavoriteCookies,
                $request->items_per_page ?? self::PAGE_SIZE_DEFAULT,
                $request->order_by ?? self::ORDER_BY
            );
            return view('frontend.favorite.index', ['data' => $question, 'qaFavoriteCookies' => $qaFavoriteCookies]);
        } catch (\Exception $e) {
            return view('errors.error', ['message' => trans('text.error')]);
        }
    }
}
