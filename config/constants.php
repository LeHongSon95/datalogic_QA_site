<?php
return [
	'paginate' => 10,
	'itemsPerPage' => [10, 30, 50],
	
	// option font size
	'optionFontSize' => [
		'big' => 'big',
		'normal' => 'normal',
		'small' => 'small'
	],
	
	// order
	'orders' => [
		'registration_asc' => 'registration_asc',
		// 'registration_desc' => 'registration_desc',
		'reading' => 'reading',
		'number_of_favorites' => 'number_of_favorites'
	],
	
	// name cookie search keyword history
	'nameCookieSearchKeywordHistory' => 'searchKeywordHistory',
	
	// limit get cookie search keyword history
	'limitCookieSearchKeywordHistory' => 10,

	// name session view count
	'nameSessionViewCount' => 'viewedQa',
	
	// name cookie set font size
	'nameCookieSetFontSize' => 'type_font_size',

	'nameCookieSetFavorite' => 'qa_user_favorite',
	'nameCookieSetFavoriteAdmin' => 'qa_admin_favorite',

	'questionnaire_status' => [
		'solved' => '1',
		'not_resolved' => '0'
	],
	
	'itemsPerPageAnalysis' => [3, 5, 10, 15, 20, 30],
	'status' => [
		'enable' => 1,
		'disable' => 0
	],
	'itemsPerPageQA' => [5, 10, 25, 50, 100],
];