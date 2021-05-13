<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/test', function (Request $request) {
    return ['answer' => 'yes'];
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api', 'prefix' => 'customers'], function() {
    Route::get('/', 'CustomersApiController@index');
    Route::post('/', 'CustomersApiController@store');
    Route::get('{customer}', 'CustomersApiController@show');
    Route::patch('{customer}', 'CustomersApiController@update');
    Route::delete('{customer}', 'CustomersApiController@destroy');
    Route::post('{customer}/service_locations', 'ServiceLocationsApiController@store');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'billing_records'], function() {
    Route::patch('{billing_record}', 'BillingRecordsApiController@update');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'service_locations'], function() {
    Route::patch('{service_location}', 'ServiceLocationsApiController@update');
    Route::delete('{service_location}', 'ServiceLocationsApiController@destroy');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'dhcp'], function() {
    Route::get('dhcp_shared_networks', 'DhcpSharedNetworksApiController@index');
    Route::post('dhcp_shared_networks', 'DhcpSharedNetworksApiController@store');
    Route::patch('dhcp_shared_networks/{dhcp_shared_network}', 'DhcpSharedNetworksApiController@update');
    Route::delete('dhcp_shared_networks/{dhcp_shared_network}', 'DhcpSharedNetworksApiController@destroy');
    Route::post('dhcp_shared_networks/{dhcp_shared_network}/subnets', 'SubnetsApiController@store');
    Route::get('subnets/{subnet}/ip_addresses', 'IpAddressesApiController@index');
    Route::get('dhcp_shared_networks/{dhcp_shared_network}/ip_addresses', 'DhcpSharedNetworksIpAddressesApiController@index');
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/subnetcalculator', 'SubnetCalculatorApiController@store');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'onts'], function() {
    Route::get('/', 'OntsApiController@index');
    Route::post('/', 'OntsApiController@store');
    Route::get('{ont}', 'OntsApiController@show');
    Route::patch('{ont}', 'OntsApiController@update');
    Route::delete('{ont}', 'OntsApiController@destroy');
    Route::get('{ont}/files', 'OntFilesApiController@index');
    Route::post('{ont}/files', 'OntFilesApiController@store');
    Route::delete('files/{media}', 'OntFilesApiController@destroy');

    Route::get('{ont}/software', 'OntSoftwareApiController@index');
    Route::post('{ont}/software', 'OntSoftwareApiController@store');
    Route::patch('ont_software/{ont_software}', 'OntSoftwareApiController@update');
    Route::delete('ont_software/{ont_software}', 'OntSoftwareApiController@destroy');

    Route::get('ont_software/{ont_software}/ont_profiles', 'OntProfilesApiController@index');
    Route::post('ont_software/{ont_software}/ont_profiles', 'OntProfilesApiController@store');
    Route::patch('ont_profiles/{ont_profile}', 'OntProfilesApiController@update');
    Route::delete('ont_profiles/{ont_profile}', 'OntProfilesApiController@destroy');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'infrastructure'], function() {
    Route::get('platforms', 'PlatformsApiController@index');
    Route::post('platforms', 'PlatformsApiController@store');
    Route::patch('platforms/{platform}', 'PlatformsApiController@update');
    Route::delete('platforms/{platform}', 'PlatformsApiController@destroy');

    Route::get('platforms/{platform}/module_types', 'ModuleTypesApiController@index');
    Route::post('platforms/{platform}/module_types', 'ModuleTypesApiController@store');

    Route::get('module_types/{module_type}', 'ModuleTypesApiController@show');
    Route::patch('module_types/{module_type}', 'ModuleTypesApiController@update');
    Route::delete('module_types/{module_type}', 'ModuleTypesApiController@destroy');

    Route::get('aggregators', 'AggregatorsApiController@index');
    Route::post('aggregators', 'AggregatorsApiController@store');

    Route::get('aggregators/{aggregator}/card', 'AggregatorCardApiController@show');
    Route::get('aggregators/{aggregator}/slots', 'AggregatorSlotsApiController@index');
    Route::get('aggregators/{aggregator}/module_types', 'AggregatorModuleTypesApiController@show');

    Route::patch('aggregators/{aggregator}', 'AggregatorsApiController@update');
    Route::delete('aggregators/{aggregator}', 'AggregatorsApiController@destroy');

    Route::get('slots/{slot}/ports', 'SlotPortsApiController@index');
    Route::post('slots/{slot}/ports', 'SlotPortsApiController@store');
    Route::post('slots/{slot}/populate', 'SlotPopulationApiController@store');
    Route::post('slots/{slot}/unpopulate', 'SlotPopulationApiController@destroy');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'provisioning'], function() {
    Route::post('/', 'ProvisioningRecordsApiController@store');
    Route::get('{provisioning_record}/edit', 'ProvisioningRecordsApiController@edit');
    Route::patch('{provisioning_record}', 'ProvisioningRecordsApiController@update');
    Route::delete('{provisioning_record}', 'ProvisioningRecordsApiController@destroy');
});

Route::post('dnsmasq/events', 'DnsmasqLogsController@store');
// Route::post('dnsmasq/events', function(\Illuminate\Http\Request $request) {
//     \App\DnsmasqLog::create(['event' => $request->getContent()]);
//     app('logbot')->log($request->getContent(), 'notice');
// });

Route::group(['middleware' => 'auth:api', 'prefix' => 'docker'], function() {
    Route::get('services/dhcp/statuscard', 'DnsmasqServerStatusCardApiController@index');
    Route::post('services/dhcp/restart', 'DnsmasqRestartApiServiceController@store');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'authorization'], function() {
    Route::get('users', 'UsersApiController@index');
    Route::post('users', 'UsersApiController@store');
    Route::patch('users/{user}', 'UsersApiController@update');
    Route::delete('users/{user}', 'UsersApiController@destroy');
    Route::get('roles', 'RolesApiController@index');
    Route::get('roles/{role}', 'RolesApiController@show');
    Route::patch('roles/{role}/permissions/{permission}/toggle', 'RolePermissionsApiController@update');
    Route::get('permissions', 'PermissionsApiController@index');

    Route::get('users/{user}/role', function(\App\User $user) {
        return new \App\Http\Resources\UserRoleResource($user);
    });
});

Route::group(['prefix' => 'settings'], function() {
    Route::get('/', 'GaSettingsApiController@index');
    Route::get('{setting}', 'GaSettingsApiController@show');
    Route::patch('{setting}', 'GaSettingsApiController@update');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'dhcpbot'], function() {
    Route::patch('subnet/{subnet}/option43', function(App\Subnet $subnet) {
        if ( app('dhcpbot')->isDeployed($subnet, 'dhcp_subnet_option43') ) {
            app('dhcpbot')->destroy($subnet, 'dhcp_subnet_option43');

            return 'false';
        } else {
            app('dhcpbot')->build($subnet, 'dhcp_subnet_option43');
            app('dhcpbot')->deploy($subnet, 'dhcp_subnet_option43');

            return 'true';
        }
    });
});

Route::get('packages', 'PackageApiController@index')->name('api.packages.index');
Route::post('packages', 'PackageApiController@store')->name('api.packages.store');
Route::patch('packages/{package}', 'PackageApiController@update')->name('api.packages.update');
Route::delete('packages/{package}', 'PackageApiController@destroy')->name('api.packages.destroy');
