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

    Route::get('/', function () {
        return view('welcome');
    });

    Route::auth();


    /*
     * App Setup
     */
    Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'PagesController@showDashboard']);
    Route::get('/company', 'CompanyController@registerCompany');
    Route::post('/company', ['as' => 'saveCompany', 'uses' => 'CompanyController@saveCompany']);
    Route::get('/desk', ['as' => 'desk', 'uses' => 'PagesController@showDesk']);

    /**
     * Project - Main
     */
    Route::get('/projects', ['as' => 'allProjects', 'uses' => 'ProjectsController@showAll']);
    Route::get('/projects/start', 'ProjectsController@getProjectForm');
    Route::post('/projects/start', ['as' => 'startProject', 'uses' => 'ProjectsController@startProject']);
    Route::get('/projects/{project}', ['as' => 'singleProject', 'uses' => 'ProjectsController@single']);

    /**
     * Project - Team Management
     */
    Route::get('/projects/{project}/team/add', ['as' => 'addTeamMember', 'uses' => 'ProjectsController@addTeamMember']);
    Route::post('/projects/{project}/team/add', ['as' => 'saveTeamMember', 'uses' => 'ProjectsController@saveTeamMember']);

    /**
     * User Invitation
     */
    Route::get('/accept_invitation/{invite_key}', 'UsersController@showInvitation');
    Route::post('/accept_invitation/{invite_key}', ['as' => 'acceptInvitation', 'uses' => 'UsersController@acceptInvitation']);

    /**
     * Purchase Requests
     */
    Route::get('/purchase_requests', ['as' => 'showAllPurchaseRequests', 'uses' => 'PurchaseRequestController@all']);
    Route::get('/purchase_requests/add', ['as' => 'makePurchaseRequest', 'uses' => 'PurchaseRequestController@make']);
    Route::post('/purchase_requests/add', ['as' => 'savePurchaseRequest', 'uses' => 'PurchaseRequestController@save']);
    Route::get('/purchase_requests/single/{purchaseRequest}', ['as' => 'singlePurchaseRequest', 'uses' => 'PurchaseRequestController@single']);
    Route::get('/api/purchase_requests/available', 'PurchaseRequestController@available');
    Route::post('/purchase_requests/cancel', ['as' => 'cancelPurchaseRequest', 'uses' => 'PurchaseRequestController@cancel']);
    Route::get('/api/purchase_requests', 'PurchaseRequestController@apiAll');

    /**
     * Items
     */
    Route::get('/items', ['as' => 'showAllItems', 'uses' => 'ItemsController@all']);
    Route::get('/api/items', 'ItemsController@apiAll');
    Route::post('/api/items/{item}/photo', ['as' => 'addItemPhoto', 'uses' => 'ItemsController@addPhoto']);
    Route::get('/items/{item}', ['as' => 'getSingleItem', 'uses' => 'ItemsController@single']);
    Route::get('/api/items/name/{name}', 'ItemsController@getName');

    /**
     * Purchase Orders & Line Items
     */
    Route::get('/purchase_orders', ['as' => 'showAllPurchaseOrders', 'uses' => 'PurchaseOrdersController@all']);
    Route::get('/purchase_orders/submit', ['as' => 'submitPurchaseOrder', 'uses' => 'PurchaseOrdersController@submit']);
    Route::post('/purchase_orders/submit/step_1', ['as' => 'savePOStep1', 'uses' => 'PurchaseOrdersController@step1']);
    Route::post('/purchase_orders/submit/step_2', ['as' => 'savePOStep2', 'uses' => 'PurchaseOrdersController@step2']);
    Route::get('/purchase_orders/add_line_item', ['as' => 'addLineItem', 'uses' => 'PurchaseOrdersController@addLineItem']);
    Route::post('/purchase_orders/remove_line_item/{lineItem}', ['as' => 'removeLineItem', 'uses' => 'PurchaseOrdersController@removeLineItem']);
    Route::post('/purchase_orders/add_line_item', 'PurchaseOrdersController@saveLineItem');
    Route::get('/purchase_orders/cancel_unsubmitted', ['as' => 'cancelUnsubmittedPO', 'uses' => 'PurchaseOrdersController@cancelUnsubmitted']);
    Route::post('/purchase_orders/submit', ['as' => 'completePurchaseOrder', 'uses' => 'purchaseOrdersController@complete']);
    Route::get('/purchase_orders/single/{purchaseOrder}', ['as' => 'singlePurchaseOrder', 'uses' => 'PurchaseOrdersController@single']);
    Route::post('/purchase_orders/approve', ['as' => 'approvePurchaseOrder' , 'uses' => 'PurchaseOrdersController@approve']);
    Route::post('/purchase_orders/reject', ['as' => 'rejectPurchaseOrder' , 'uses' => 'PurchaseOrdersController@reject']);
    Route::post('/purchase_orders/line_item/paid', ['as' => 'markLineItemPaid', 'uses' => 'PurchaseOrdersController@markPaid']);
    Route::post('/purchase_orders/line_item/delivered', ['as' => 'markLineItemDelivered', 'uses' => 'PurchaseOrdersController@markDelivered']);
    Route::get('/api/purchase_orders', 'PurchaseOrdersController@apiAll');

    /**
     * Settings
     */
    Route::get('/settings', 'SettingsController@show');
    Route::get('/api/settings', 'SettingsController@apiShow');
    Route::post('/settings', ['as' => 'saveSettings', 'uses' => 'SettingsController@save']);

    /**
     * Roles
     */
    Route::get('/api/roles', 'RolesController@getRoles');
    Route::post('/api/roles', 'RolesController@postNewRole');
    Route::post('/api/roles/delete', 'RolesController@removeRole');
    Route::post('/api/roles/remove_permission', 'RolesController@postRemovePermission');
    Route::post('/api/roles/give_permission', 'RolesController@postGivePermission');
    Route::put('/api/roles/{role}', 'RolesController@update');

    /**
     * Vendors
     */
    Route::get('/vendors', ['as' => 'showVendors', 'uses' => 'VendorsController@showAll']);


});
