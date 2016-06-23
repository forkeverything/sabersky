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


    Route::get('/', 'PagesController@getHome');
    
    

    /*
     * Out-the-box Authentication Endpoints
     */
    Route::auth();

    /*
     * Countries
     */
    Route::get('/countries', 'CountriesController@getAll');
    Route::get('/countries/search/{term}', 'CountriesController@getSearchCountry');
    Route::get('/countries/{country}', 'CountriesController@getSingleCountry');
    Route::get('/countries/{country}/states', 'CountriesController@getStates');
    Route::get('/countries/currency/search/{query}', 'CountriesController@getSearchCurrency');

    /*
     * Product Categories
     */
    Route::get('/product_categories', 'ProductCategoriesController@getCategories');
    Route::get('/product_categories/{productCategory}/subcategories', 'ProductCategoriesController@getSubcategories');
    Route::get('/product_categories/subcategories/search/{term}', 'ProductCategoriesController@getSearchSubcategories');


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
    Route::post('/company/currencies', 'CompanyController@postAddCurrency');
    Route::delete('/company/currencies/{currency_id}', 'CompanyController@deleteRemoveCurrency');
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
    Route::put('/projects/{project}/team/remove', 'ProjectsController@putRemoveTeamMember');
    // api
    Route::get('/api/projects/{project}/team', 'ProjectsController@apiGetTeamMembers');

    /**
     * User
     */
    Route::get('/accept_invitation/{invite_key}', 'UsersController@getAcceptView');
    Route::post('/accept_invitation/{invite_key}', ['as' => 'acceptInvitation', 'uses' => 'UsersController@postAcceptInvitation']);
    Route::get('/user', 'UsersController@getLoggedUser');
    Route::get('/user/calendar_events', 'UsersController@getCalendarEvents');
    Route::get('/user/profile', 'UsersController@getOwnProfile');
    Route::put('/user/profile', 'UsersController@putUpdateProfile');
    Route::post('/user/profile/photo', 'UsersController@postProfilePhoto');
    Route::delete('/user/profile/photo', 'UsersController@deleteProfilePhoto');
    Route::get('/user/email/{email}/check', 'UsersController@getCheckEmailAvailability');
    Route::get('/staff', 'UsersController@getStaff');
    Route::get('/staff/add', 'UsersController@getAddStaffForm');
    Route::post('/staff/add', 'UsersController@postSaveStaff');
    Route::get('/staff/{user}', 'UsersController@getSingleUser');
    Route::put('/staff/{user}/role', 'UsersController@putChangeRole');
    Route::put('/staff/{user}/active', 'UsersController@toggleActive');
    Route::delete('/admin', 'UsersController@deleteAdmin');
    // api
    Route::get('/api/staff/search/{query}', 'UsersController@apiGetSearchStaff');
    Route::get('/api/staff', 'UsersController@apiGetStaff');
    Route::get('/api/staff/team/search/{query}', 'UsersController@apiGetSearchTeamMembers');
    Route::get('/api/user/projects', 'UsersController@apiGetAllProjects');

    /**
     * Purchase Requests
     */
    Route::get('/purchase_requests', ['as' => 'showAllPurchaseRequests', 'uses' => 'PurchaseRequestController@getAll']);
    Route::get('/purchase_requests/make', ['as' => 'makePurchaseRequest', 'uses' => 'PurchaseRequestController@getMakePRForm']);
    Route::post('/purchase_requests/make', ['as' => 'savePurchaseRequest', 'uses' => 'PurchaseRequestController@postMakePR']);
    Route::get('/purchase_requests/{purchaseRequest}', 'PurchaseRequestController@getSingle');
    Route::delete('/purchase_requests/{purchaseRequest}', 'PurchaseRequestController@deleteCancel');
    Route::get('/purchase_requests/{purchaseRequest}/reopen', 'PurchaseRequestController@getReopen');
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
    Route::get('/purchase_orders/{purchaseOrder}/rule/{rule}/{action}', 'PurchaseOrdersController@getHandlerule');
    Route::get('/purchase_orders/{purchaseOrder}/line_item/{lineItem}/paid', 'PurchaseOrdersController@getMarkLineItemPaid');
    Route::get('/purchase_orders/{purchaseOrder}/line_item/{lineItem}/received/{status}', 'PurchaseOrdersController@getMarkLineItemReceived');
    Route::post('/api/purchase_orders/submit', 'PurchaseOrdersController@apiPostSubmit');
    Route::get('/api/purchase_orders', 'PurchaseOrdersController@apiGetAll');
    Route::get('/api/purchase_orders/{purchaseOrder}', 'PurchaseOrdersController@apiGetSingle');

    /*
     * Line Items
     */
    Route::put('/line_item/paid', ['as' => 'markLineItemPaid', 'uses' => 'LineItemsController@putMarkPaid']);
    Route::put('/line_item/delivered', ['as' => 'markLineItemDelivered', 'uses' => 'LineItemsController@putMarkDelivered']);

    /**
     * Settings
     */
    Route::get('/settings/company', 'SettingsController@getCompany');
    Route::get('/settings/roles', 'SettingsController@getRoles');
    Route::get('/settings/purchasing', 'SettingsController@getPurchasing');

    /**
     * Roles
     */
    //api
    Route::get('/api/roles', 'RolesController@apiGetRoles');
    Route::post('/api/roles', 'RolesController@postNewRole');
    Route::post('/api/roles/remove_permission', 'RolesController@postRemovePermission');
    Route::post('/api/roles/give_permission', 'RolesController@postGivePermission');
    Route::put('/api/roles/{role}', 'RolesController@putUpdatePosition');
    Route::delete('/api/roles/{role}', 'RolesController@deleteRole');

    /**
     * Rules
     */
    //api
    Route::get('/api/rules/properties_triggers', 'RulesController@getPropertiesTriggers');
    Route::post('/api/rules', 'RuleScontroller@postNewRule');
    Route::delete('/api/rules/{rule}', 'RulesController@delete');

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

    /**
     * Reports
     */

    Route::get('/reports', 'ReportsController@getMenu');
    Route::get('/reports/spendings/{category}', 'ReportsController@getSpendingsReport');
    Route::get('/reports/spendings/{category}/currency/{currency}', 'ReportsController@getSpendingsData');

    /**
     * Notes
     */
    Route::get('/notes/{subject}/{subject_id}', 'NotesController@getNotes');
    Route::post('/notes/{subject}/{subject_id}', 'NotesController@postAddNote');
    Route::delete('/notes/{subject}/{subject_id}/{note}', 'NotesController@deleteNote');

    // api
    Route::get('/api/vendors/pending_requests', 'VendorsController@apiGetPendingRequests');
    Route::get('/api/vendors/search/{query}', 'VendorsController@apiGetSearchVendors');
    Route::get('/api/vendors/{vendor}', 'VendorsController@apiGetSingle');

    Route::get('test', function () {
//        return App\PurchaseOrder::all();
        $user = App\User::first();
        return App\PurchaseOrder::join('purchase_order_rule', 'purchase_order_rule.purchase_order_id', '=', 'purchase_orders.id')
                                ->join('role_rule', 'role_rule.rule_id', '=', 'purchase_order_rule.rule_id')
                                ->where('role_rule.role_id', '=', $user->role_id)
                                ->where('status', '=', 'pending')
                                ->select(DB::raw('purchase_orders.*'))
                                ->groupBy('purchase_orders.id')
                                ->get();
    });

});
