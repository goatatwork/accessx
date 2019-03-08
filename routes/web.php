<?php
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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'provisioning'], function() {
    Route::get('/', 'ProvisioningRecordController@index');
    Route::get('{provisioning_record}', 'ProvisioningRecordController@show');
    Route::get('{provisioning_record}/edit', 'ProvisioningRecordController@edit');
    Route::patch('{provisioning_record}/suspend', 'ProvisioningRecordController@suspend');
    Route::patch('{provisioning_record}/unsuspend', 'ProvisioningRecordController@unsuspend');
    Route::delete('{provisioning_record}', 'ProvisioningRecordController@destroy');
    Route::get('service_locations/{service_location}/show', 'ServiceLocationProvisioningController@show');
    Route::get('service_locations/{service_location}/create', 'ServiceLocationProvisioningController@create');
});
Route::patch('/service_locations/{service_location}', 'ServiceLocationsController@update')->middleware('auth');

Route::group(['middleware' => 'auth', 'prefix' => 'customers'], function() {
    Route::get('/', 'CustomersController@index');
    Route::post('/', 'CustomersController@store');
    Route::get('create', 'CustomersController@create');
    Route::get('{customer}', 'CustomersController@show');
    Route::get('{customer}', 'CustomersController@show');
});
Route::patch('/billing_records/{billing_record}', 'BillingRecordsController@update')->middleware('auth');

Route::group(['middleware' => 'auth', 'prefix' => 'onts'], function() {
    Route::get('/', 'OntsController@index');
    Route::post('/', 'OntsController@store');
    Route::get('create', 'OntsController@create');
    Route::get('{ont}', 'OntsController@show')->name('ont.show');
    Route::patch('{ont}', 'OntsController@update');
    Route::delete('{ont}', 'OntsController@destroy');
    Route::get('{ont}/edit', 'OntsController@edit');

    Route::delete('ont_software/{ont_software}', 'OntSoftwareController@destroy');
    Route::patch('ont_software/{ont_software}/update', 'OntSoftwareController@update')->name('ontsoftware.update');


    Route::delete('ont_profiles/{ont_profile}', 'OntProfilesController@destroy');
    Route::patch('ont_profiles/{ont_profile}/update', 'OntProfilesController@update')->name('ontprofile.update');

});

Route::group(['middleware' => 'auth', 'prefix' => 'dhcp'], function() {
    Route::get('/', 'DhcpSharedNetworksController@index');
    Route::post('shared_networks', 'DhcpSharedNetworksController@store');
    Route::get('shared_networks/create', 'DhcpSharedNetworksController@create');
    Route::get('shared_networks/{dhcp_shared_network}', 'DhcpSharedNetworksController@show');
    Route::delete('shared_networks/{dhcp_shared_network}', 'DhcpSharedNetworksController@destroy');
    Route::patch('shared_networks/{dhcp_shared_network}', 'DhcpSharedNetworksController@update');
    Route::get('shared_networks/{dhcp_shared_network}/edit', 'DhcpSharedNetworksController@edit');
    Route::get('leases', 'DhcpLeasesFileController@index')->name('dhcp.leases');
    Route::get('{dhcp_shared_network}', 'DhcpSharedNetworksController@show');
});

Route::group(['middleware' => 'auth', 'prefix' => 'infrastructure'], function() {
    Route::get('/aggregators', 'AggregatorsController@index');
    Route::post('/aggregators', 'AggregatorsController@store');
    Route::get('/aggregators/create', 'AggregatorsController@create');
    Route::get('/aggregators/{aggregator}', 'AggregatorsController@show');
    Route::delete('/aggregators/{aggregator}', 'AggregatorsController@destroy');
    Route::get('/aggregators/{aggregator}/edit', 'AggregatorsController@edit');
    Route::patch('/aggregators/{aggregator}', 'AggregatorsController@update');

    Route::post('slots/{slot}/populate', 'SlotPopulationController@store');
    Route::post('slots/{slot}/unpopulate', 'SlotPopulationController@destroy');
});

Route::get('activity_logs', 'ActivityLogsController@index')->middleware('auth');

Route::get('users', 'UsersController@index')->middleware('auth');

Route::group(['middleware' => 'auth', 'prefix' => 'settings'], function() {
    Route::get('/', 'GaSettingsController@index');
});
