<?php

use App\Http\Controllers\Backend\LoginController;
use App\Http\Controllers\Backend\AnalysisController;
use App\Http\Controllers\Frontend\ChangeFontSizeController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Backend\QuestionController;
use App\Http\Controllers\Backend\ListQuestionController;
use App\Http\Controllers\Backend\QuestionnaireController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Frontend\QAController;
use App\Http\Controllers\Frontend\FavoriteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes Backend
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/user', [UserController::class, 'index'])->name('user.list');

Route::prefix('admin')->middleware('basicAuth')->group(function () {
	Route::post('/question/get-list-by-tag', [QuestionController::class, 'getListByTag'])->name('admin.question.get_list_by_tag');
	Route::resource('question', QuestionController::class);
	Route::resource('questionnaire', QuestionnaireController::class, ['as' => 'admin']);
	Route::get('/setting', [SettingController::class, 'index'])->name('admin.setting');
	Route::post('/setting/store-featured-tag', [SettingController::class, 'createFeaturedTag'])->name('admin.setting.create_featured_tag');
	Route::post('/setting/update-sort-featured-tag', [SettingController::class, 'updateSortFeaturedTag'])->name('admin.setting.update_sort_featured_tag');
	Route::post('/setting/store-recommend', [SettingController::class, 'createQuestionRecommend'])->name('admin.setting.create_recommend');
	Route::post('/setting/delete-featured-tag', [SettingController::class, 'deleteFeaturedTag'])->name('admin.setting.delete_featured_tag');
	Route::post('/setting/delete-recommend', [SettingController::class, 'deleteQuestionRecommend'])->name('admin.setting.delete_recommend');
	Route::post('/setting/update-max-characters', [SettingController::class, 'updateSetting'])->name('admin.setting.updateSetting');
	Route::get('/analysis', [AnalysisController::class, 'index'])->name('admin.analysis');
	Route::get('/FAQlist', [ListQuestionController::class, 'index'])->name('admin.FAQlist');
	Route::delete('/delete/{id}', [ListQuestionController::class, 'destroy'])->name('admin.delete');
});

Route::group(['middleware' => 'basicAuth'], function () {
	Route::post('/upload', [QuestionController::class, 'uploadFile'])->name('upload');
	Route::post('/import', [QuestionController::class, 'import'])->name('import');
});

/*
|--------------------------------------------------------------------------
| Web Routes Frontend
|--------------------------------------------------------------------------
*/
Route::group([
	'as' => 'frontend.'
], function () {
	// home page
	Route::get('/', [HomeController::class, 'index'])->name('home.index');

	Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('frontend.dashboard');

	Route::get('/detail', [HomeController::class, 'detail'])->name('frontend.detail');


	Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorite');

	// create cookie change font size
	Route::get('/change-font-size', [ChangeFontSizeController::class, 'setFontSize'])->name('changeFontSize');

	Route::get('/qa/{id}', [QAController::class, 'show'])->name('qa.show')->middleware('view_count');
	Route::post('/qa/{id}/store-questionnaire', [QAController::class, 'createQuestionnaire'])->name('qa.questionnaire.store');
	Route::post('/qa/{id}/update-favorite', [QAController::class, 'updateFavoriteCount'])->name('qa.update_favorite');

	// redirect if route not exist
	Route::any('{query}', function () {
		return redirect()->route('frontend.home.index');
	})->where('query', '.*');
});
