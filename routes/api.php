<?php
use Illuminate\Support\Carbon;

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
Route::get('verifyEmailFirst', 'Auth\RegisterController@verifyEmailFirst')->name('verifyEmailFirst');
Route::get('/verify/{verifyToken}', 'EmailController@verify')->name('verify');

// Route::get('/map', function () {
// 	return view('csv.map');
// });

Route::get('/', function () {
	return redirect('login');
});
Route::get('signup/activate/{token}', 'AuthController@signupActivate');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/logoutOther', 'UserController@logoutOther')->name('logoutOther');
    Route::post('/logOtherDevices', 'UserController@logOtherDevices')->name('logOtherDevices');
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/courier', 'HomeController@courier')->name('courier');
	Route::get('/courier/{name}', 'HomeController@courierHome')->name('courierHome');
	Route::resource('users', 'UserController');
	Route::resource('roles', 'RoleController');
	Route::resource('shipreports', 'ReportController');
	Route::resource('companies', 'CompanyController');
	Route::resource('email', 'EmailController');
	Route::resource('calls', 'CallController');





    Route::post('permisions/{id}', 'UserController@permisions')->name('permisions');
	Route::get('getUsers', 'UserController@getUsers')->name('getUsers');
	Route::get('getDrivers', 'UserController@getDrivers')->name('getDrivers');
	Route::get('getCustomer', 'UserController@getCustomer')->name('getCustomer');
	Route::get('getLogedinUsers', 'UserController@getLogedinUsers')->name('getLogedinUsers');
	Route::post('profile/{id}', 'UserController@profile')->name('profile');
	Route::post('getSorted', 'UserController@getSorted')->name('getSorted');
	Route::post('getUserPro/{id}', 'UserController@getUserPro')->name('getUserPro');
	Route::post('getUserPerm/{id}', 'UserController@getUserPerm')->name('getUserPerm');
	Route::post('password', 'UserController@password')->name('password');
	Route::patch('AuthUserUp/{id}', 'UserController@AuthUserUp')->name('AuthUserUp');
	Route::post('UserShip', 'UserController@UserShip')->name('UserShip');


	Route::get('getUsersRole', 'RoleController@getUsersRole')->name('getUsersRole');
	Route::get('getRoles', 'RoleController@getRoles')->name('getRoles');
	Route::get('getPermissions', 'RoleController@getPermissions')->name('getPermissions');
	Route::post('getRolesPerm/{id}', 'RoleController@getRolesPerm')->name('getRolesPerm');
	Route::get('getRoles', 'RoleController@getRoles')->name('getRoles');




	Route::post('getCompanies', 'CompanyController@getCompanies')->name('getCompanies');
	Route::post('getCompanyAdmin', 'CompanyController@getCompanyAdmin')->name('getCompanyAdmin');
	Route::post('companupdate/{id}', 'CompanyController@companupdate')->name('companupdate');
	Route::post('logo/{id}', 'CompanyController@logo')->name('logo');
	Route::post('getLogo', 'CompanyController@getLogo')->name('getLogo');
	Route::post('getLogoOnly', 'CompanyController@getLogoOnly')->name('getLogoOnly');

	// Reports
	Route::post('shipmentExpo', 'ReportController@shipmentExpo')->name('shipmentExpo');
	Route::post('userExpo', 'ReportController@userExpo')->name('userExpo');
	Route::post('deriverdExpo', 'ReportController@deriverdExpo')->name('deriverdExpo');
	Route::post('customersExpo', 'ReportController@customersExpo')->name('customersExpo');
	Route::post('branchesExpo', 'ReportController@branchesExpo')->name('branchesExpo');
	Route::post('agentsExpo', 'ReportController@agentsExpo')->name('agentsExpo');
	Route::post('cancledExpo', 'ReportController@cancledExpo')->name('cancledExpo');
	Route::post('pendingExpo', 'ReportController@pendingExpo')->name('pendingExpo');
	Route::post('bookingExpo', 'ReportController@bookingExpo')->name('bookingExpo');
	Route::post('approvedExpo', 'ReportController@approvedExpo')->name('approvedExpo');
	Route::post('dispatchedExpo', 'ReportController@dispatchedExpo')->name('dispatchedExpo');
	Route::post('userDateExpo', 'ReportController@userDateExpo')->name('userDateExpo');
	Route::post('DriverReport', 'ReportController@DriverReport')->name('DriverReport');

	Route::post('generate_pdf', 'ReportController@generate_pdf')->name('generate_pdf');
	Route::post('DelivReport', 'ReportController@DelivReport')->name('DelivReport');

	Route::post('pod/{id}', 'ReportController@pod')->name('pod');

	Route::post('ProdReport', 'ReportController@ProdReport')->name('ProdReport');
	
	// Dashboard
	Route::any('getUsersCount', 'PermissionController@getUsersCount')->name('getUsersCount');
	Route::any('getDashCount', 'PermissionController@getDashCount')->name('getDashCount');
	
	// E-MAILS
	Route::post('/sendmail', 'EmailController@sendmail')->name('sendmail');
	Route::get('/getsubscribers', 'EmailController@getsubscribers')->name('getsubscribers');
	Route::post('/subscribe', 'EmailController@subscribe')->name('subscribe');
	Route::post('/refresh/{id}', 'EmailController@refresh')->name('refresh');
	Route::patch('/updateSubscribers/{id}', 'EmailController@updateSubscribers')->name('updateSubscribers');

	Route::get('/getunsubscribed', 'EmailController@getunsubscribed')->name('getunsubscribed');

	// Date test
	Route::post('/carbon', 'RoleController@carbon')->name('carbon');

	// Reports
	Route::post('/displayReport', 'ReportController@displayReport')->name('displayReport');

	// Notifications
	Route::post('/Notyshpments/{id}', 'NotificationController@Notyshpments')->name('Notyshpments');
	Route::post('/read', 'NotificationController@read')->name('read');
	Route::get('/Chattynoty', 'NotificationController@Chattynoty')->name('Chattynoty');
	Route::get('/notifications', 'NotificationController@notifications')->name('notifications');

	// Uploads
	Route::get('/upload', 'HomeController@upload')->name('upload');
	Route::get('/categories', 'HomeController@categories')->name('categories');
	Route::get('/getDocs', 'HomeController@getDocs')->name('getDocs');
	Route::get('/getClientsDocs', 'HomeController@getClientsDocs')->name('getClientsDocs');

	Route::post('/assign/{id}', 'HomeController@assign')->name('assign');
	Route::post('/getDocsSort', 'HomeController@getDocsSort')->name('getDocsSort');
	

	Route::any('getBranchShipments', 'DashboardController@getBranchShipments')->name('getBranchShipments');
	Route::any('getChartScheduled', 'DashboardController@getChartScheduled')->name('getChartScheduled');
	Route::any('getChartDelivered', 'DashboardController@getChartDelivered')->name('getChartDelivered');
	Route::any('getChartData', 'DashboardController@getChartData')->name('getChartData');
	Route::any('getChartCancled', 'DashboardController@getChartCancled')->name('getChartCancled');
	Route::any('getChartBranch', 'DashboardController@getChartBranch')->name('getChartBranch');

	Route::any('getCountCount', 'DashboardController@getCountCount')->name('getCountCount');
	Route::any('getBranchCount', 'DashboardController@getBranchCount')->name('getBranchCount');
	Route::any('getChartCount', 'DashboardController@getChartCount')->name('getChartCount');
	Route::any('getCountryhipments', 'DashboardController@getCountryhipments')->name('getCountryhipments');
	Route::any('getChartCountry', 'DashboardController@getChartCountry')->name('getChartCountry');
	Route::any('countCountShipments', 'DashboardController@countCountShipments')->name('countCountShipments');

	Route::any('countDelivered', 'DashboardController@countDelivered')->name('countDelivered');
	Route::any('countPending', 'DashboardController@countPending')->name('countPending');
	Route::any('countOrders', 'DashboardController@countOrders')->name('countOrders');

	Route::any('schedulepct', 'CslogController@schedulepct')->name('schedulepct');
	Route::any('allLogs', 'CslogController@allLogs')->name('allLogs');


	Route::post('Filterlogs', 'CallController@Filterlogs')->name('Filterlogs');
	Route::get('callcount', 'CallController@callcount')->name('callcount');

	 
});
