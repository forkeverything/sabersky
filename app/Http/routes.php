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
    Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'PagesController@showDashboard']);;
    Route::get('/desk', ['as' => 'desk', 'uses' => 'PagesController@showDesk']);

    /*
     * Countries
     */
    Route::get('/countries', 'CountriesController@getAll');
    Route::get('/countries/search/{term}', 'CountriesController@getSearchCountry');
    Route::get('/countries/{country}', 'CountriesController@getSingleCountry');
    Route::get('/countries/{country}/states', 'CountriesController@getStates');
    Route::get('/countries/currency/search/{query}', 'CountriesController@getSearchCurrency');

    /*
     * Address
     */
    // api
    Route::post('/api/address', 'AddressesController@apiPostAddNew');
    Route::put('/api/address/{address}/set_primary', 'AddressesController@apiPutSetPrimary');
    Route::delete('/api/address/{address}', 'AddressesController@apiDeleteAddress');


    /*
     * Company
     */
    // You can't create / update a Company externally
    Route::post('/company', 'CompanyController@postRegisterCompany');
    Route::put('/company', 'CompanyController@putUpdate');
    // api
    Route::get('/api/company', 'CompanyController@apiGetOwn');
    Route::get('/api/company/profile/{term}', 'CompanyController@apiGetPublicProfile');
    Route::get('/api/company/search/{query}', 'CompanyController@apiGetSearchCompany');

    /**
     * Project - Main
     */
    Route::get('/projects', ['as' => 'allProjects', 'uses' => 'ProjectsController@getAll']);
    Route::get('/projects/start', 'ProjectsController@getNewProjectForm');
    Route::post('/projects/start', ['as' => 'startProject', 'uses' => 'ProjectsController@postStartProject']);
    Route::get('/projects/{project}', ['as' => 'singleProject', 'uses' => 'ProjectsController@getSingle']);
    Route::get('/projects/{project}/edit', 'ProjectsController@getEditForm');
    Route::post('/projects/{project}/edit', ['as' => 'updateProject', 'uses' => 'ProjectsController@postUpdateProject']);
    Route::delete('/projects/{project}', 'ProjectsController@delete');
    // api
    Route::get('/api/projects', 'ProjectsController@apiGetAll');

    /**
     * Project - Team Management
     */
    Route::get('/projects/{project}/team/add', ['as' => 'addTeamMember', 'uses' => 'ProjectsController@getAddTeamMember']);
    Route::post('/projects/{project}/team/add', ['as' => 'saveTeamMember', 'uses' => 'ProjectsController@postSaveTeamMember']);
    // api
    Route::get('/api/projects/{project}/team', 'ProjectsController@apiGetTeamMembers');

    /**
     * User
     */
    Route::get('/accept_invitation/{invite_key}', 'UsersController@getAcceptView');
    Route::post('/accept_invitation/{invite_key}', ['as' => 'acceptInvitation', 'uses' => 'UsersController@postAcceptInvitation']);
    Route::get('/user/email/{email}/check', 'UsersController@getCheckEmailAvailability');
    Route::get('/me', 'UsersController@getLoggedUser');
    Route::get('/team', 'UsersController@getTeam');
    Route::get('/team/add', 'UsersController@getAddStaffForm');
    Route::post('/team/add', 'UsersController@postSaveStaff');
    Route::get('/team/user/{user}', 'UsersController@getSingleUser');
    Route::put('/team/user/{user}/role', 'UsersController@putChangeRole');
    Route::delete('/team/user/{user}', 'UsersController@deleteUser');
    // api
    Route::get('/api/users/company/search/{query}', 'UsersController@apiGetSearchCompanyEmployees');
    Route::get('/api/users/team', 'UsersController@apiGetTeam');
    Route::get('/api/users/team/members/search/{query}', 'UsersController@apiGetSearchTeamMembers');
    Route::get('/api/user/projects', 'UsersController@apiGetAllProjects');

    /**
     * Purchase Requests
     */
    Route::get('/purchase_requests', ['as' => 'showAllPurchaseRequests', 'uses' => 'PurchaseRequestController@getAll']);
    Route::get('/purchase_requests/make', ['as' => 'makePurchaseRequest', 'uses' => 'PurchaseRequestController@getMakePRForm']);
    Route::post('/purchase_requests/make', ['as' => 'savePurchaseRequest', 'uses' => 'PurchaseRequestController@postMakePR']);
    Route::get('/purchase_requests/{purchaseRequest}', 'PurchaseRequestController@getSingle');
    Route::post('/purchase_requests/cancel', ['as' => 'cancelPurchaseRequest', 'uses' => 'PurchaseRequestController@postCancel']);
    //api
    Route::get('/api/purchase_requests', 'PurchaseRequestController@apiGetAll');
    Route::get('/api/purchase_requests/{purchaseRequest}', 'PurchaseRequestController@apiGetSingle');

    /**
     * Items
     */
    Route::get('/items', ['as' => 'showAllItems', 'uses' => 'ItemsController@getAll']);
    Route::get('/items/{item}', ['as' => 'getSingleItem', 'uses' => 'ItemsController@getSingle']);
    Route::get('/api/items', 'ItemsController@apiGetAll');
    Route::get('/api/items/brands', 'ItemsController@apiGetAllBrands');
    Route::get('/api/items/search/brands/{query}', 'ItemsController@apiGetSearchBrands');
    Route::get('/api/items/search/names/{query}', 'ItemsController@apiGetSearchNames');
    Route::get('/api/items/search/sku/{query}', 'ItemsController@apiGetSearchSKU');
    Route::get('/api/items/search/{query}', 'ItemsController@apiGetSearchItems');
    Route::post('/api/items', 'ItemsController@postAddNew');
    Route::post('/api/items/{item}/photo', ['as' => 'addItemPhoto', 'uses' => 'ItemsController@postAddPhoto']);
    Route::get('/api/items/{item}', 'ItemsController@apiGetSingle');
    Route::delete('/api/items/{item}/photo/{photo}', 'ItemsController@apiDeleteItemPhoto');

    /**
     * Purchase Orders
     */
    Route::get('/purchase_orders', ['as' => 'showAllPurchaseOrders', 'uses' => 'PurchaseOrdersController@getAll']);
    Route::get('/purchase_orders/submit', ['as' => 'getSubmitPOForm', 'uses' => 'PurchaseOrdersController@getSubmitForm']);
    Route::get('/purchase_orders/{purchaseOrder}', ['as' => 'singlePurchaseOrder', 'uses' => 'PurchaseOrdersController@getSingle']);
    Route::post('/purchase_orders/approve', ['as' => 'approvePurchaseOrder', 'uses' => 'PurchaseOrdersController@approve']);
    Route::post('/purchase_orders/reject', ['as' => 'rejectPurchaseOrder', 'uses' => 'PurchaseOrdersController@reject']);
    Route::post('/api/purchase_orders/submit', 'PurchaseOrdersController@apiPostSubmit');
    Route::get('/api/purchase_orders', 'PurchaseOrdersController@apiGetAll');

    /*
     * Line Items
     */
    Route::put('/line_item/paid', ['as' => 'markLineItemPaid', 'uses' => 'LineItemsController@putMarkPaid']);
    Route::put('/line_item/delivered', ['as' => 'markLineItemDelivered', 'uses' => 'LineItemsController@putMarkDelivered']);

    /**
     * Settings
     */
    Route::get('/settings', 'SettingsController@getShow');
    Route::post('/settings', ['as' => 'saveSettings', 'uses' => 'SettingsController@save']);

    /**
     * Roles
     */
    //api
    Route::get('/api/roles', 'RolesController@apiGetRoles');
    Route::post('/api/roles', 'RolesController@postNewRole');
    Route::post('/api/roles/delete', 'RolesController@postRemoveRole');
    Route::post('/api/roles/remove_permission', 'RolesController@postRemovePermission');
    Route::post('/api/roles/give_permission', 'RolesController@postGivePermission');
    Route::put('/api/roles/{role}', 'RolesController@putUpdatePosition');

    /**
     * Rules
     */
    //api
    Route::get('/api/rules', 'RulesController@getRules');
    Route::get('/api/rules/properties_triggers', 'RulesController@getPropertiesTriggers');
    Route::post('/api/rules', 'RuleScontroller@postNewRule');
    Route::delete('/api/rules/{rule}/remove', 'RulesController@delete');

    /**
     * Vendors
     */
    Route::get('/vendors', ['as' => 'showVendors', 'uses' => 'VendorsController@getAll']);
    Route::get('/vendors/requests', ['as' => 'showVendorRequests', 'uses' => 'VendorsController@getRequestsPage']);
    Route::get('/vendors/add', ['as' => 'addVendor', 'uses' => 'VendorsController@getAddForm']);
    Route::post('/vendors/link', 'VendorsController@postLinkCompanyToVendor');
    Route::put('/vendors/{vendor}/unlink', 'VendorsController@putUnlinkCompanyToVendor');
    Route::post('/vendors/add', 'VendorsController@postAddCustomVendor');
    Route::get('/vendors/{vendor}', 'VendorsController@getSingle');
    Route::post('/vendors/{vendor}/description', 'VendorsController@postSaveDescription');
    Route::post('/vendors/{vendor}/bank_accounts', 'VendorsController@postAddBankAccount');
    Route::delete('/vendors/{vendor}/bank_accounts/{bank_account_id}', 'VendorsController@deleteBankAccount');
    Route::post('/vendors/{vendor}/bank_accounts/{bank_account_id}/set_primary', 'VendorsController@postBankAccountSetPrimary');
    Route::post('/vendors/{vendor}/request/{action}', 'VendorsController@postVerifyVendor');
    // api
    Route::get('/api/vendors/pending_requests', 'VendorsController@apiGetPendingRequests');
    Route::get('/api/vendors/search/{query}', 'VendorsController@apiGetSearchVendors');
    Route::get('/api/vendors/{vendor}', 'VendorsController@apiGetSingle');

});
