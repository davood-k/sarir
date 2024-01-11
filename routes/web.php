<?php

use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

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
Route::get('/b', function () {

    auth()->loginUsingId(2);
});
Route::get('/v', function () {
    $user = Role::find('1');
    return $user->permissions()->get();

    // auth()->loginUsingId(2);
    // dd (Gate::allows('delete-person' , $user));
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'KhademyarController@show')->name('/');
Route::resource('informationOffice', 'InformationOfficeController');
Route::get('/mo', function () {
    dd(auth()->user()->id);
});

Route::get('pagestring', 'KhademyarController@pagestr')->name('pagestring');
Route::get('/information', 'KhademyarController@info')->name('information');
// Route::post('/moarefi/edit/{id}', 'KhademyarController@update');
Route::get('/a', function () {
    $user = \App\User::find(1);
    dd(Gate::allows('add-user', $user));
});

Route::middleware(['auth', 'auth.admin'])->group(function () {

    Route::get('/all', 'KhademController@index')->name('index');
    Route::get('/amaken', 'KhademController@amaken')->name('amaken');
    Route::get('/hamkar', 'KhademController@hamkar')->name('hamkar');
    Route::get('/tablighat', 'KhademController@tablighat')->name('tablighat');
    Route::get('/basij', 'KhademController@basij')->name('basij');

    Route::get('/person/show/{id}', 'KhademController@show');
    Route::get('/person/create/{id}', 'KhademController@edit');
    Route::post('/person/{id}/update/', 'KhademController@update');
    // کنترلر نمره آزمون

    Route::get('/azmoon', 'AzmoonController@index')->name('azmoon');
    Route::get('/taeedeazmoon', 'AzmoonController@taeedsh')->name('taeedeazmoon');
    Route::get('/printazmoon', 'AzmoonController@print')->name('printazmoon');
    Route::get('/infolderpr', 'AzmoonController@infolderpr')->name('infolderpr');
    Route::get('/azmoons/store', 'AzmoonController@bayegan');
    Route::put('/azmoon/{id}', 'AzmoonController@create');
    Route::post('/person/edit/{id}', 'AzmoonController@show');
    Route::post('/azmoon/{user_id}/sabt', 'AzmoonController@store');

    Route::get('khorooj', 'KhademyarController@index')->name('khorooj');
    Route::post('khorooji', 'KhademyarController@export')->name('export');
    Route::get('addperson', 'KhademyarController@add')->name('person');
    Route::get('insert', 'KhademyarController@create')->name('insert');
    Route::post('sendpersons', 'KhademyarController@store')->name('sendpersons');
    // کنترلر کمیسیون
    Route::get('/comision', 'ComisionController@index')->name('comision');
    Route::put('/comision/{id}', 'ComisionController@create');
    Route::get('/comisions/all', 'ComisionController@show');
    Route::post('/comision/{id}/sabt', 'ComisionController@store');
    Route::post('/comision/edit/{id}', 'ComisionController@edit');
    //بایگانی
    Route::get('/bayegani', 'ComisionController@bayegani')->name('bayegani');

    Route::group(['prefix' => 'admin'], function () {
        Route::resource('duty', 'DutyController');
        Route::resource('roles', 'RoleController');
        Route::resource('permissions', 'PermissionController');
        Route::resource('users', 'user\UserController');
        Route::get('/users/{user}/permissions', 'User\PermissionController@create')->name('users.permissions');
        Route::post('/users/{user}/permissions', 'User\PermissionController@store')->name('users.permissions.store');
    });

    /**
     * route admin for excel
     */
    Route::get('/importexcel', 'KhademyarController@importexl')->name('importexcel');
    Route::post('/import', 'KhademyarController@saveImport')->name('import');
    Route::delete('/delete/{id}', 'AzmoonController@destroy');
    Route::put('/delmoarefi/{id}', 'KhademyarController@destroy');
    Route::get('/moarefi/store/{id}', 'KhademyarController@edit');
    Route::put('/moarefi/update/{id}', 'KhademyarController@update')->name('moarefi.update');


    Route::get('/edarat', 'PlaceKhController@index');
    Route::get('edarat/create', 'PlaceKhController@create');
    Route::get('edarat/edit', 'PlaceKhController@edit');
    Route::get('edarat/store/{id}', 'PlaceKhController@store');

});

Route::get('/mizbanan', 'KhademyarController@mizbanan')->name('mizbanan');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');