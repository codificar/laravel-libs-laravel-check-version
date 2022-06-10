<?php

Route::group(array('namespace' => 'Codificar\CheckVersion\Http\Controllers'), function () {
	Route::post('/api/lib/checkversion/', 'CheckVersionController@getVersion');
});
