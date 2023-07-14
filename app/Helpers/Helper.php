<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class Helper
{
	// replace single space
	public static function pregReplaceSingleSpace($string)
	{
		$newString = '';
		
		if ( $string ) {
			$newString = preg_replace('/\s+/', ' ', $string);
		}
		
		return $newString;
	}
	
	// set cookie
	public static function setCookie($name, $value, $minutes = 2628000)
	{
		Cookie::queue($name, json_encode( $value ), $minutes);
	}
	
	// get cookie
	public static function getCookie($name)
	{
		return json_decode( Cookie::get($name), true );
	}
	
	// delete cookie
	public static function deleteCookie($name): bool
	{
		Cookie::queue( Cookie::forget($name) );
		
		return true;
	}
	
	// check custom font size
	public static function getCookieSetFontSize(): array
	{
		// get page prefix not admin
		$getPrefix = request()->route()->getPrefix();
		
		// get font size change
		$type_font_size = self::getCookie( Config::get('constants.nameCookieSetFontSize') );
		$result['type'] = Config::get('constants.optionFontSize.normal');
		$result['class'] = '';
		
		if ( empty( $getPrefix ) &&
			!empty( $type_font_size ) &&
			$type_font_size !== Config::get('constants.optionFontSize.normal') &&
			in_array( $type_font_size, Config::get('constants.optionFontSize') )
		) {
			$result['type'] = $type_font_size;
			$result['class'] = 'font-size-' . $type_font_size;
		}
		
		return $result;
	}

	// check issetCookies
	public static function issetCookies($name): bool
	{
		if (Cookie::get($name) !== null) {
			return true;
		}
		return false;
	}
}