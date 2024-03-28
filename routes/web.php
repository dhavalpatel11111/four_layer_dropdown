<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/admin', function () {
        return view('admin.all');
    });

    Route::get('/user', function () {
        return view('userform');
    });


    Route::any("/country", [CountryController::class, "index"]);
    Route::any("/add_country", [CountryController::class, "add_country"]);
    Route::any("/country_list", [CountryController::class, "country_list"]);
    Route::any("/delete_country", [CountryController::class, "delete_country"]);
    Route::any("/edit_country", [CountryController::class, "edit_country"]);


    Route::any("/state", [StateController::class, "index"]);
    Route::any("/add_state", [StateController::class, "add_state"]);
    Route::any("/get_Country", [StateController::class, "get_Country"]);
    Route::any("/state_list", [StateController::class, "state_list"]);
    Route::any("/delete_state", [StateController::class, "delete_state"]);
    Route::any("/edit_state", [StateController::class, "edit_state"]);

    
    Route::any("/city", [CityController::class, "index"]);
    Route::any("/get_State", [CityController::class, "get_State"]);
    Route::any("/add_city", [CityController::class, "add_city"]);
    Route::any("/city_list", [CityController::class, "city_list"]);
    Route::any("/delete_city", [CityController::class, "delete_city"]);
    Route::any("/edit_city", [CityController::class, "edit_city"]);


    Route::any("/area", [AreaController::class, "index"]);
    Route::any("/get_City", [AreaController::class, "get_City"]);
    Route::any("/add_area", [AreaController::class, "add_area"]);
    Route::any("/area_list", [AreaController::class, "area_list"]);
    Route::any("/delete_area", [AreaController::class, "delete_area"]);
    Route::any("/edit_area", [AreaController::class, "edit_area"]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
