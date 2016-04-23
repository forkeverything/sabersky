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
    
    /*
     * Company
     */
    Route::post('/api/company', 'CompanyController@postRegisterCompany');
    Route::get('/api/company', 'CompanyController@getOwn');
    Route::get('/api/company/currency', 'CompanyController@getCurrency');
    Route::put('/api/company', 'CompanyController@putUpdate');
    Route::get('/api/company/profile/{term}', 'CompanyController@getPublicProfile');

    /**
     * Project - Main
     */
    Route::get('/projects', ['as' => 'allProjects', 'uses' => 'ProjectsController@getAll']);
    Route::get('/api/projects', 'ProjectsController@apiGetAll');
    Route::get('/projects/start', 'ProjectsController@getNewProjectForm');
    Route::post('/projects/start', ['as' => 'startProject', 'uses' => 'ProjectsController@postStartProject']);
    Route::get('/projects/{project}', ['as' => 'singleProject', 'uses' => 'ProjectsController@getSingle']);
    Route::get('/projects/{project}/edit', 'ProjectsController@getEditForm');
    Route::post('/projects/{project}/edit', ['as' => 'updateProject', 'uses' => 'ProjectsController@postUpdateProject']);

    /**
     * Project - Team Management
     */
    Route::delete('/api/projects/{project}', 'ProjectsController@apiDelete');
    Route::get('/api/projects/{project}/team', 'ProjectsController@apiGetTeamMembers');
    Route::get('/projects/{project}/team/add', ['as' => 'addTeamMember', 'uses' => 'ProjectsController@getAddTeamMember']);
    Route::post('/projects/{project}/team/add', ['as' => 'saveTeamMember', 'uses' => 'ProjectsController@postSaveTeamMember']);

    /**
     * User
     */
    Route::get('/accept_invitation/{invite_key}', 'UsersController@getAcceptView');
    Route::post('/accept_invitation/{invite_key}', ['as' => 'acceptInvitation', 'uses' => 'UsersController@postAcceptInvitation']);
    Route::get('/api/me', 'UsersController@apiGetLoggedUser');
    Route::get('/api/user/email/{email}/check', 'UsersController@getCheckEmailAvailability');
    Route::get('/team', 'UsersController@getTeam');
    Route::get('/api/team', 'UsersController@apiGetTeam');
    Route::get('/api/team/members/search/{query}', 'UsersController@apiGetSearchTeamMembers');
    Route::get('/team/add', 'UsersController@getAddStaffForm');
    Route::post('/team/add', 'UsersController@postSaveStaff');
    Route::get('/team/user/{user}', 'UsersController@getSingleUser');
    Route::put('/team/user/{user}/role', 'UsersController@putChangeRole');
    Route::delete('/team/user/{user}', 'UsersController@deleteUser');
    Route::get('/api/user/projects', 'UsersController@apiGetAllProjects');

    /**
     * Purchase Requests
     */
    Route::get('/purchase_requests', ['as' => 'showAllPurchaseRequests', 'uses' => 'PurchaseRequestController@getAll']);
    Route::get('/purchase_requests/make', ['as' => 'makePurchaseRequest', 'uses' => 'PurchaseRequestController@getMakePRForm']);
    Route::post('/purchase_requests/make', ['as' => 'savePurchaseRequest', 'uses' => 'PurchaseRequestController@postMakePR']);
    Route::get('/purchase_requests/{purchaseRequest}', 'PurchaseRequestController@getSingle');
    Route::get('/api/purchase_requests/available', 'PurchaseRequestController@apiGetAvailable');
    Route::post('/purchase_requests/cancel', ['as' => 'cancelPurchaseRequest', 'uses' => 'PurchaseRequestController@postCancel']);
    Route::get('/api/purchase_requests', 'PurchaseRequestController@apiGetAll');

    /**
     * Items
     */
    Route::get('/items', ['as' => 'showAllItems', 'uses' => 'ItemsController@getAll']);
    Route::get('/api/items', 'ItemsController@apiGetAll');
    Route::get('/api/items/brands', 'ItemsController@apiGetAllBrands');
    Route::get('/api/items/brands/search/{query}', 'ItemsController@apiGetSearchBrands');
    Route::get('/api/items/names/search/{query}', 'ItemsController@apiGetSearchNames');
    Route::get('/api/items/find', 'ItemsController@apiGetSingleBy');
    Route::get('/api/items/search/{query}', 'ItemsController@getSearchItems');
    Route::post('/api/items', 'ItemsController@postAddNew');
    Route::post('/api/items/{item}/photo', ['as' => 'addItemPhoto', 'uses' => 'ItemsController@postAddPhoto']);
    Route::get('/items/{item}', ['as' => 'getSingleItem', 'uses' => 'ItemsController@getSingle']);
    Route::get('/api/items/{item}', 'ItemsController@apiGetSingle');
    Route::delete('/api/items/{item}/photo/{photo}', 'ItemsController@apiDeleteItemPhoto');

    /**
     * Purchase Orders & Line Items
     */
    Route::get('/purchase_orders', ['as' => 'showAllPurchaseOrders', 'uses' => 'PurchaseOrdersController@getAll']);
    Route::get('/purchase_orders/submit', ['as' => 'submitPurchaseOrder', 'uses' => 'PurchaseOrdersController@getSubmitForm']);
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
    Route::get('/settings', 'SettingsController@getShow');
    Route::post('/settings', ['as' => 'saveSettings', 'uses' => 'SettingsController@save']);

    /**
     * Roles
     */
    Route::get('/api/roles', 'RolesController@apiGetRoles');
    Route::post('/api/roles', 'RolesController@postNewRole');
    Route::post('/api/roles/delete', 'RolesController@postRemoveRole');
    Route::post('/api/roles/remove_permission', 'RolesController@postRemovePermission');
    Route::post('/api/roles/give_permission', 'RolesController@postGivePermission');
    Route::put('/api/roles/{role}', 'RolesController@putUpdatePosition');

    /**
     * Rules
     */
    Route::get('/api/rules', 'RulesController@getRules');
    Route::get('/api/rules/properties_triggers', 'RulesController@getPropertiesTriggers');
    Route::post('/api/rules', 'RuleScontroller@postNewRule');
    Route::delete('/api/rules/{rule}/remove', 'RulesController@delete');

    /**
     * Vendors
     */
    Route::get('/vendors', ['as' => 'showVendors', 'uses' => 'VendorsController@getAll']);
    
});
