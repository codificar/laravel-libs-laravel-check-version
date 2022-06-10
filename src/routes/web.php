<?php

Route::group(array('namespace' => 'Codificar\CheckVersion\Http\Controllers'), function() {
    Route::get('/api/lib/checkversion/', 'CheckVersionController@getVersion');
});
