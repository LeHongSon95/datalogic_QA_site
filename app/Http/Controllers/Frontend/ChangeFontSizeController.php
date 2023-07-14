<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\ChangeFontSize\ChangeFontSizeRepositoryInterface;
use Illuminate\Http\Request;

class ChangeFontSizeController extends Controller
{
	/**
	 * Create a new controller instance.
	 */
	protected $changeFontSizeRepository;
	
	public function __construct(ChangeFontSizeRepositoryInterface $changeFontSizeRepository)
	{
		$this->changeFontSizeRepository = $changeFontSizeRepository;
	}
	
    // set font size when click change option
	public function setFontSize (Request $request)
	{
		return $this->changeFontSizeRepository->createCookieSetFontSize($request);
	}
}
