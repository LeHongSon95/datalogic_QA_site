<?php
namespace App\Repositories\ChangeFontSize;

use App\Helpers\Helper;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ChangeFontSizeRepository implements ChangeFontSizeRepositoryInterface
{
	// create cookie set font Size
	public function createCookieSetFontSize($request): array
	{
		$type_font_size = $request->type_font_size;
		
		try {
			Helper::setCookie( Config::get('constants.nameCookieSetFontSize'), $type_font_size );
			
			$result['status'] = true;
		} catch (Exception $e) {
			$result['status'] = false;
			Log::error($e);
		}
		
		return $result;
	}
}