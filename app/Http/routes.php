<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');

    Route::get('/dashboard', 'PagesController@showDashboard');

    Route::get('/company', 'CompanyController@registerCompany');
    Route::post('/company', ['as' => 'saveCompany', 'uses' => 'CompanyController@saveCompany']);

    Route::get('/projects', ['as' => 'allProjects', 'uses' => 'ProjectsController@showAll']);
    Route::get('/projects/start', 'ProjectsController@getProjectForm');
    Route::post('/projects/start', ['as' => 'startProject', 'uses' => 'ProjectsController@startProject']);
    Route::get('/projects/{project}', ['as' => 'singleProject', 'uses' => 'ProjectsController@single']);

    Route::get('/projects/{project}/team/add', ['as' => 'addTeamMember', 'uses' => 'ProjectsController@addTeamMember']);
    Route::post('/projects/{project}/team/add', ['as' => 'saveTeamMember', 'uses' => 'ProjectsController@saveTeamMember']);
});
