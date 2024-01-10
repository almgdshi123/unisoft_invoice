<?php

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




    Route::get('/', function () {
        return view('auth.login');
    });
Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('invoices', 'InvoicesController');
Route::resource('sections', 'SectionsController');
Route::resource('products', 'ProductsController');
Route::resource('customers', 'CustomerController');

Route::get('/section/{id}', 'InvoicesController@getproducts');
Route::get('/InvoicesDetails/{id}', 'InvoicesDetailsController@edit');
Route::get('/customersDetails/{id}', 'CustomerController@show');

Route::get('/InvoicesDetailsArchove/{id}', 'InvoicesDetailsController@show');

Route::get('/edit_invoice/{id}', 'InvoicesController@edit');

Route::get('download/{invoice_number}/{file_name}', 'InvoicesDetailsController@get_file');
Route::get('View_file/{invoice_number}/{file_name}', 'InvoicesDetailsController@open_file');
Route::post('delete_file', 'InvoicesDetailsController@destroy')->name('delete_file');
Route::get('/Status_show/{id}', 'InvoicesController@show')->name('Status_show');
Route::resource('InvoiceAttachments', 'InvoiceAttachmentsController');
Route::resource('Archive', 'InvoiceAchiveController');
Route::post('/Status_Update/{id}', 'InvoicesController@Status_Update')->name('Status_Update');
Route::get('Invoice_Paid','InvoicesController@Invoice_Paid');

Route::get('Invoice_UnPaid','InvoicesController@Invoice_UnPaid');

Route::get('Invoice_Partial','InvoicesController@Invoice_Partial');
Route::get('Print_invoice/{id}','InvoicesController@Print_invoice');


Route::get('export_invoices/{id}', 'InvoicesController@export');
Route::group(['middleware' => ['auth']], function() {
    
    Route::resource('roles','RoleController');
    
    Route::resource('users','UserController');
    
    });
    Route::get('invoices_report', 'Invoices_Report@index');
    Route::post('Search_invoices', 'Invoices_Report@Search_invoices');
    Route::post('Search_Section', 'Invoices_Report@Search_Section');
    Route::get('customers_report', 'Search_Section@index')->name("customers_report");
    
Route::get('MarkAsRead_all','InvoicesController@MarkAsRead_all')->name('MarkAsRead_all');
Route::get('/InvoicesDetailsnotification/{id}', 'InvoicesDetailsController@InvoicesDetailsnotification');

Route::get('unreadNotifications_count', 'InvoicesController@unreadNotifications_count')->name('unreadNotifications_count');

Route::get('unreadNotifications', 'InvoicesController@unreadNotifications')->name('unreadNotifications');






