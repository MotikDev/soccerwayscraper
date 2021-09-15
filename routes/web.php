<?php

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Goutte\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (){
    return view('Predict');
});
Route::get('/all', 'Scrap@index')->name('all');



//Testing other algorithms
Route::get('/scrap/overTest', 'Scrap@overTest')->name('overTest');




Route::get('/scrap/over', 'Scrap@over');
Route::get('/scrap/under', 'Scrap@under');
Route::get('/scrap/btts', 'Scrap@btts');
Route::get('/scrap/nobtts', 'Scrap@noBTTS');
Route::get('/scrap/win', 'Scrap@win');

Route::resource('/scrap', 'Scrap');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
