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

Route::get('/token/{token}', 'MemberController@verifyUser')
    ->name('user.verification');

Route::get('/', 'HomeController@home')->name('home');
Route::get('/changecurrency', 'HomeController@changecurrency')->name('changecurrency');
Route::get('/compare', 'HomeController@compare')->name('compare');

Route::get('/collect', 'ScraperController@collectAllMobiles')->name('collect');

Route::get('/phone-finder', 'HomeController@filteredMobiles')->name('newmobile');
Route::get('/setPrice', 'HomeController@setUsdMinPrice')->name('setprice');

Route::get('/filters', 'FilterTabsController@filters')->name('filters');
Route::get('/details/{id}', 'HomeController@viewMobile')->name('mobiledetail');
Route::get('/disclaimer', 'MobileDetailsController@disclaimer')->name('disclaimer');
Route::get('/price-disclaimer', 'MobileDetailsController@priceDisclaimer')->name('priceDisclaimer');
Route::get('/terms', 'FooterController@terms')->name('terms');
Route::get('/privacy', 'FooterController@privacy')->name('privacy');
Route::get('/contact', 'FooterController@contact')->name('contact');
Route::get('/about', 'FooterController@about')->name('about');
Route::get('/news', 'HomeController@news')->name('news.all');
Route::get('/news-view/{id}', 'HomeController@newsView')->name('news.view');

Route::get('/all-brands', 'HomeController@allBrands')->name('all.brands');

Route::get('/pricing', 'HomeController@pricing')->name('pricing');
Route::get('/privacy-policy', 'HomeController@privacyPolicy')->name('home.privacyPolicy');
Route::get('/terms-conditions', 'HomeController@termsConditions')->name('home.termsConditions');
Route::get('/about-us', 'HomeController@aboutUs')->name('home.aboutUs');

Route::post('/contact_us_message', 'ContactUsController@store')->name('contact_us_message.store');

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/help-center/articles', 'HomeController@searchList')
    ->name('mobiles.searchList');

Route::get('/download/{ticketId}', 'DownloadsController@download')
    ->name('downloads.ticket.attachment')
    ->where('ticketId', '[0-9]+')
    ->middleware('auth');

Route::get('/admin/dashboard', 'DashboardController@index')
    ->name('dashboard.index')
    ->middleware(['auth', 'checkPermission']);

Route::get('/media', 'HomeController@media')->name('media')->middleware('auth');

Route::group([
    'prefix' => 'members',
    'middleware' => ['auth'],
], function () {

    Route::get('/my-orders', 'MemberController@myOrders')
        ->name('members.myOrders');

    Route::delete('/order/{order}', 'MemberController@destroyOrder')
        ->name('members.destroyOrder')
        ->where('id', '[0-9]+');

    Route::get('/print-invoice/{orderId}', 'MemberController@printInvoice')
        ->name('members.printInvoice')
        ->where('orderId', '[0-9]+');

    Route::get('/my-tickets', 'MemberController@myTickets')
        ->name('members.myTickets');

    Route::get('/create-ticket', 'MemberController@createTicket')
        ->name('members.createTicket');

    Route::post('/store-ticket', 'MemberController@storeTicket')
        ->name('members.storeTicket');

    Route::get('/view-ticket/{ticketId}', 'MemberController@viewTicket')
        ->name('members.viewTicket')
        ->where('ticketId', '[0-9]+');

    Route::post('/store-comment', 'MemberController@storeComment')
        ->name('members.storeComment');

    Route::get('/create-testimonial', 'MemberController@createTestimonial')
        ->name('members.createTestimonial');

    Route::post('/store-testimonial', 'MemberController@storeTestimonial')
        ->name('members.storeTestimonial');

    Route::get('/subscription-plan/{setupId}', 'MemberController@subscriptionPlanHistory')
        ->name('members.subscriptionPlanHistory');

    Route::get('/bank-transactions/{orderId}/create-or-edit', 'MemberController@createOrEditBankTransaction')
        ->name('members.bankTransaction.createOrEdit');

    Route::post('/bank-transactions', 'MemberController@storeBankTransaction')
        ->name('members.bankTransaction.store');

    Route::put('/bank-transactions/{orderId}', 'MemberController@updateBankTransaction')
        ->name('members.bankTransaction.update');

    Route::get('/mb-transactions/{orderId}/edit', 'MemberController@editMbTransaction')
        ->name('members.mbTransaction.edit');

    Route::put('/mb-transactions/{orderId}', 'MemberController@updateMbTransaction')
        ->name('members.mbTransaction.update');

    Route::get('/download-attachment/{orderId}', 'DownloadsController@downloadAttachment')
        ->name('downloads.downloadAttachment');
});

Route::group([
    'prefix' => 'admin/roles',
    'middleware' => ['auth', 'checkPermission'],
], function () {

    Route::get('/', 'RolesController@index')
        ->name('roles.index');

    Route::get('/create', 'RolesController@create')
        ->name('roles.create');

    Route::get('/show/{role}', 'RolesController@show')
        ->name('roles.show')
        ->where('id', '[0-9]+');

    Route::get('/{role}/edit', 'RolesController@edit')
        ->name('roles.edit')
        ->where('id', '[0-9]+');

    Route::get('/{roleId}/modules/{moduleId?}', 'RolesController@modulePermissions')
        ->name('roles.module-permissions')
        ->where('roleId', '[0-9]+')
        ->where('moduleId', '[0-9]+');

    Route::post('/', 'RolesController@store')
        ->name('roles.store');

    Route::post('/assign_permissions', 'RolesController@assignPermissions')
        ->name('roles.assign_permissions');

    Route::put('role/{role}', 'RolesController@update')
        ->name('roles.update')
        ->where('id', '[0-9]+');

    Route::delete('/role/{role}', 'RolesController@destroy')
        ->name('roles.destroy')
        ->where('id', '[0-9]+');

    Route::get('/export-xlsx', 'RolesController@exportXLSX')
        ->name('roles.exportXLSX');

    Route::get('/print-details/{id}', 'RolesController@printDetails')
        ->name('roles.printDetails');
});

Route::group([
    'prefix' => 'admin/permissions',
    'middleware' => ['auth', 'checkPermission'],
], function () {

    Route::get('/', 'PermissionsController@index')
        ->name('permissions.index');

    Route::get('/create', 'PermissionsController@create')
        ->name('permissions.create');

    Route::get('/show/{permission}', 'PermissionsController@show')
        ->name('permissions.show')
        ->where('id', '[0-9]+');

    Route::get('/{permission}/edit', 'PermissionsController@edit')
        ->name('permissions.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'PermissionsController@store')
        ->name('permissions.store');

    Route::put('permission/{permission}', 'PermissionsController@update')
        ->name('permissions.update')
        ->where('id', '[0-9]+');

    Route::delete('/permission/{permission}', 'PermissionsController@destroy')
        ->name('permissions.destroy')
        ->where('id', '[0-9]+');

    Route::get('/export-xlsx', 'PermissionsController@exportXLSX')
        ->name('permissions.exportXLSX');

    Route::get('/print-details/{id}', 'PermissionsController@printDetails')
        ->name('permissions.printDetails');
});

Route::group([
    'prefix' => 'admin/users',
    'middleware' => ['auth', 'checkPermission'],
], function () {

    Route::get('/', 'UsersController@index')
        ->name('users.index');

    Route::get('/create', 'UsersController@create')
        ->name('users.create');

    Route::get('/show/{user}', 'UsersController@show')
        ->name('users.show')
        ->where('id', '[0-9]+');

    Route::get('/{user}/edit', 'UsersController@edit')
        ->name('users.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'UsersController@store')
        ->name('users.store');

    Route::put('user/{user}', 'UsersController@update')
        ->name('users.update')
        ->where('id', '[0-9]+');

    Route::delete('/user/{user}', 'UsersController@destroy')
        ->name('users.destroy')
        ->where('id', '[0-9]+');

    Route::get('/export-xlsx', 'UsersController@exportXLSX')
        ->name('users.exportXLSX');

    Route::get('/print-details/{id}', 'UsersController@printDetails')
        ->name('users.printDetails');
});

Route::group([
    'prefix' => 'admin/uploaded_files',
    'middleware' => ['auth', 'checkPermission'],
], function () {

    Route::get('/', 'UploadedFilesController@index')
        ->name('uploaded_files.index');

    Route::post('/summer-note-upload', 'UploadedFilesController@summerNoteUpload')
        ->name('uploaded_files.summerNoteUpload');

    Route::get('/create', 'UploadedFilesController@create')
        ->name('uploaded_files.create');

    Route::get('/show/{uploadedFile}', 'UploadedFilesController@show')
        ->name('uploaded_files.show')
        ->where('id', '[0-9]+');

    Route::get('/{uploadedFile}/edit', 'UploadedFilesController@edit')
        ->name('uploaded_files.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'UploadedFilesController@store')
        ->name('uploaded_files.store');

    Route::put('uploaded_file/{uploadedFile}', 'UploadedFilesController@update')
        ->name('uploaded_files.update')
        ->where('id', '[0-9]+');

    Route::delete('/uploaded_file/{uploadedFile}', 'UploadedFilesController@destroy')
        ->name('uploaded_files.destroy')
        ->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'admin/media',
    'middleware' => ['auth', 'checkPermission'],
], function () {
    Route::post('/summer-note-upload', 'MediaController@summerNoteUpload')
        ->name('media.summerNoteUpload');
});

Route::group([
    'prefix' => 'admin/file_types',
    'middleware' => ['auth', 'checkPermission'],
], function () {

    Route::get('/', 'FileTypesController@index')
        ->name('file_types.index');

    Route::get('/create', 'FileTypesController@create')
        ->name('file_types.create');

    Route::get('/show/{fileType}', 'FileTypesController@show')
        ->name('file_types.show')
        ->where('id', '[0-9]+');

    Route::get('/{fileType}/edit', 'FileTypesController@edit')
        ->name('file_types.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'FileTypesController@store')
        ->name('file_types.store');

    Route::put('file_type/{fileType}', 'FileTypesController@update')
        ->name('file_types.update')
        ->where('id', '[0-9]+');

    Route::delete('/file_type/{fileType}', 'FileTypesController@destroy')
        ->name('file_types.destroy')
        ->where('id', '[0-9]+');
});

Route::group(
    [
        'prefix' => 'admin/modules',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'ModulesController@index')
            ->name('modules.index');

        Route::get('/create', 'ModulesController@create')
            ->name('modules.create');

        Route::get('/show/{module}', 'ModulesController@show')
            ->name('modules.show')
            ->where('id', '[0-9]+');

        Route::get('/{module}/edit', 'ModulesController@edit')
            ->name('modules.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'ModulesController@store')
            ->name('modules.store');

        Route::put('module/{module}', 'ModulesController@update')
            ->name('modules.update')
            ->where('id', '[0-9]+');

        Route::delete('/{module}', 'ModulesController@destroy')
            ->name('modules.destroy')
            ->where('id', '[0-9]+');

        Route::get('/export-xlsx', 'ModulesController@exportXLSX')
            ->name('modules.exportXLSX');

        Route::get('/print-details/{id}', 'ModulesController@printDetails')
            ->name('modules.printDetails');
    });

Route::group(
    [
        'prefix' => 'admin/event_logs',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'EventLogsController@index')
            ->name('event_logs.index');

        Route::get('/create', 'EventLogsController@create')
            ->name('event_logs.create');

        Route::get('/show/{eventLog}', 'EventLogsController@show')
            ->name('event_logs.show')
            ->where('id', '[0-9]+');

        Route::get('/{eventLog}/edit', 'EventLogsController@edit')
            ->name('event_logs.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'EventLogsController@store')
            ->name('event_logs.store');

        Route::delete('/event_log/{eventLog}', 'EventLogsController@destroy')
            ->name('event_logs.destroy')
            ->where('id', '[0-9]+');

        Route::get('/export-xlsx', 'EventLogsController@exportXLSX')
            ->name('event_logs.exportXLSX');

        Route::get('/print-details/{id}', 'EventLogsController@printDetails')
            ->name('event_logs.printDetails');
    });

Route::group(
    [
        'prefix' => 'admin/settings',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'SettingsController@index')
            ->name('settings.index');

        Route::get('/create', 'SettingsController@create')
            ->name('settings.create');

        Route::get('/show/{setting}', 'SettingsController@show')
            ->name('settings.show')
            ->where('id', '[0-9]+');

        Route::get('/{setting}/edit', 'SettingsController@edit')
            ->name('settings.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'SettingsController@store')
            ->name('settings.store');

        Route::put('setting/{setting}', 'SettingsController@update')
            ->name('settings.update')
            ->where('id', '[0-9]+');

        Route::get('/all', 'SettingsController@all')
            ->name('settings.all');

        Route::get('/groups/{groupId?}', 'SettingsController@group')
            ->name('settings.group');

        Route::put('/update_batch', 'SettingsController@updateBatch')
            ->name('settings.update_batch');

        Route::delete('/setting/{setting}', 'SettingsController@destroy')
            ->name('settings.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'admin/tags',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'TagsController@index')
            ->name('tags.index');

        Route::get('/create', 'TagsController@create')
            ->name('tags.create');

        Route::get('/show/{tag}', 'TagsController@show')
            ->name('tags.show')
            ->where('id', '[0-9]+');

        Route::get('/{tag}/edit', 'TagsController@edit')
            ->name('tags.edit')
            ->where('id', '[0-9]+');

        Route::post('/{isAjax?}', 'TagsController@store')
            ->name('tags.store');

        Route::put('tag/{tag}', 'TagsController@update')
            ->name('tags.update')
            ->where('id', '[0-9]+');

        Route::delete('/tag/{tag}', 'TagsController@destroy')
            ->name('tags.destroy')
            ->where('id', '[0-9]+');

        Route::get('/export-xlsx', 'TagsController@exportXLSX')
            ->name('tags.exportXLSX');

        Route::get('/print-details/{id}', 'TagsController@printDetails')
            ->name('tags.printDetails');
    });

Route::group(
    [
        'prefix' => 'admin/countries',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'CountriesController@index')
            ->name('countries.index');

        Route::get('/create', 'CountriesController@create')
            ->name('countries.create');

        Route::get('/show/{country}', 'CountriesController@show')
            ->name('countries.show')
            ->where('id', '[0-9]+');

        Route::get('/{country}/edit', 'CountriesController@edit')
            ->name('countries.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'CountriesController@store')
            ->name('countries.store');

        Route::put('country/{country}', 'CountriesController@update')
            ->name('countries.update')
            ->where('id', '[0-9]+');

        Route::delete('/country/{country}', 'CountriesController@destroy')
            ->name('countries.destroy')
            ->where('id', '[0-9]+');

        Route::get('/export-xlsx', 'CountriesController@exportXLSX')
            ->name('countries.exportXLSX');

        Route::get('/print-details/{id}', 'CountriesController@printDetails')
            ->name('countries.printDetails');
    });

Route::group(
    [
        'prefix' => 'admin/testimonials',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'TestimonialsController@index')
            ->name('testimonials.index');

        Route::get('/create', 'TestimonialsController@create')
            ->name('testimonials.create');

        Route::get('/show/{testimonial}', 'TestimonialsController@show')
            ->name('testimonials.show')
            ->where('id', '[0-9]+');

        Route::get('/{testimonial}/edit', 'TestimonialsController@edit')
            ->name('testimonials.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'TestimonialsController@store')
            ->name('testimonials.store');

        Route::put('testimonial/{testimonial}', 'TestimonialsController@update')
            ->name('testimonials.update')
            ->where('id', '[0-9]+');

        Route::delete('/testimonial/{testimonial}', 'TestimonialsController@destroy')
            ->name('testimonials.destroy')
            ->where('id', '[0-9]+');

        Route::get('/export-xlsx', 'TestimonialsController@exportXLSX')
            ->name('testimonials.exportXLSX');

        Route::get('/print-details/{id}', 'TestimonialsController@printDetails')
            ->name('testimonials.printDetails');
    });

Route::group(
    [
        'prefix' => 'admin/tickets',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'TicketsController@index')
            ->name('tickets.index');

        Route::get('/create', 'TicketsController@create')
            ->name('tickets.create');

        Route::get('/show/{ticket}', 'TicketsController@show')
            ->name('tickets.show')
            ->where('id', '[0-9]+');

        Route::get('/{ticket}/edit', 'TicketsController@edit')
            ->name('tickets.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'TicketsController@store')
            ->name('tickets.store');

        Route::put('ticket/{ticket}', 'TicketsController@update')
            ->name('tickets.update')
            ->where('id', '[0-9]+');

        Route::get('/comments/{ticketId}', 'TicketsController@comments')
            ->name('tickets.comments')
            ->where('ticketId', '[0-9]+');

        Route::post('/comments', 'TicketsController@storeComment')
            ->name('tickets.storeComment');

        Route::post('/assign_to', 'TicketsController@storeAssignTo')
            ->name('tickets.storeAssignTo');

        Route::put('/pick_me/{ticketId}', 'TicketsController@storePickMe')
            ->name('tickets.storePickMe');

        Route::delete('/ticket/{ticket}', 'TicketsController@destroy')
            ->name('tickets.destroy')
            ->where('id', '[0-9]+');

        Route::get('/export-xlsx', 'TicketsController@exportXLSX')
            ->name('tickets.exportXLSX');

        Route::get('/print-details/{id}', 'TicketsController@printDetails')
            ->name('tickets.printDetails');
    });

Route::group(
    [
        'prefix' => 'admin/comments',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'CommentsController@index')
            ->name('comments.index');

        Route::get('/create', 'CommentsController@create')
            ->name('comments.create');

        Route::get('/show/{comment}', 'CommentsController@show')
            ->name('comments.show')
            ->where('id', '[0-9]+');

        Route::get('/{comment}/edit', 'CommentsController@edit')
            ->name('comments.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'CommentsController@store')
            ->name('comments.store');

        Route::put('comment/{comment}', 'CommentsController@update')
            ->name('comments.update')
            ->where('id', '[0-9]+');

        Route::delete('/comment/{comment}', 'CommentsController@destroy')
            ->name('comments.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'admin/groups',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'GroupsController@index')
            ->name('groups.index');

        Route::get('/create', 'GroupsController@create')
            ->name('groups.create');

        Route::get('/show/{group}', 'GroupsController@show')
            ->name('groups.show')
            ->where('id', '[0-9]+');

        Route::get('/{group}/edit', 'GroupsController@edit')
            ->name('groups.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'GroupsController@store')
            ->name('groups.store');

        Route::put('group/{group}', 'GroupsController@update')
            ->name('groups.update')
            ->where('id', '[0-9]+');

        Route::delete('/group/{group}', 'GroupsController@destroy')
            ->name('groups.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'admin/contact_us',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'ContactUsController@index')
            ->name('contact_us.index');

        Route::get('/create', 'ContactUsController@create')
            ->name('contact_us.create');

        Route::get('/show/{contactUs}', 'ContactUsController@show')
            ->name('contact_us.show')
            ->where('id', '[0-9]+');

        Route::post('/', 'ContactUsController@storeReply')
            ->name('contact_us.storeReply');

        Route::put('contact_us/{contactUs}', 'ContactUsController@update')
            ->name('contact_us.update')
            ->where('id', '[0-9]+');

        Route::delete('/contact_us/{contactUs}', 'ContactUsController@destroy')
            ->name('contact_us.destroy')
            ->where('id', '[0-9]+');

        Route::get('/export-xlsx', 'ContactUsController@exportXLSX')
            ->name('contact_us.exportXLSX');

        Route::get('/print-details/{id}', 'ContactUsController@printDetails')
            ->name('contact_us.printDetails');
    });

// SSLCOMMERZ Start
Route::post('/sslcommerz/pay', 'SslCommerzPaymentController@pay')->name('sslcommerz.pay');
Route::post('/sslcommerz/success', 'SslCommerzPaymentController@success');
Route::post('/sslcommerz/fail', 'SslCommerzPaymentController@fail');
Route::post('/sslcommerz/cancel', 'SslCommerzPaymentController@cancel');
Route::post('/sslcommerz/ipn', 'SslCommerzPaymentController@ipn');
//SSLCOMMERZ END

Route::group([
    'prefix' => 'admin/mobiles',
    'middleware' => ['auth', 'checkPermission'],
], function () {
    Route::get('/', 'MobilesController@index')
        ->name('mobiles.index');
    Route::get('/mobile-list', 'MobilesController@mobileList')
        ->name('mobile.mobileList');
    Route::get('/create', 'MobilesController@create')
        ->name('mobiles.create');
    Route::get('/show/{mobile}', 'MobilesController@show')
        ->name('mobiles.show')->where('id', '[0-9]+');
    Route::get('/{mobile}/edit', 'MobilesController@edit')
        ->name('mobiles.mobile.edit')->where('id', '[0-9]+');

    Route::get('/{mobile}/quick-update', 'MobilesController@quickUpdate')
        ->name('mobiles.quick.update')->where('id', '[0-9]+');

    Route::put('mobile-quick-update/{mobile}', 'MobilesController@quickUpdateStore')
        ->name('mobiles.quick.update.store')->where('id', '[0-9]+');

    Route::get('/{mobile}/gallery', 'GalleryController@index')
        ->name('mobiles.gallery')->where('id', '[0-9]+');

    Route::post('/', 'MobilesController@store')
        ->name('mobiles.store');
    Route::put('mobile/{mobile}', 'MobilesController@update')
        ->name('mobiles.update')->where('id', '[0-9]+');
    Route::put('/{mobile}/update-price', 'MobilesController@updatePrice')
        ->name('mobiles.updatePrice');
    Route::delete('/mobile/{mobile}', 'MobilesController@destroy')
        ->name('mobiles.destroy')->where('id', '[0-9]+');

    Route::get('/{mobile}/import-price', 'MobilesController@importPrice')
        ->name('mobiles.import_price');

    // for mobile price

    Route::get('/costs/{mobileId}', 'MobilesController@variationPrice')
        ->name('mobile.variationPrices');

    Route::post('/costs', 'MobilesController@storeVariationPrice')
        ->name('mobile.storeVariationPrices');

    Route::put('/costs/{Id}', 'MobilesController@updateVariationPrice')
        ->name('mobile.updateVariationPrices');

    Route::delete('/costs/{Id}', 'MobilesController@destroyVariationPrice')
        ->name('mobile.destroyVariationPrices')
        ->where('Id', '[0-9]+');

    Route::put('opinions-status-update', 'MobilesController@opinionsStatusUpdate')
        ->name('opinions.status.update');

});

Route::group([
    'prefix' => 'admin/gallery',
    'middleware' => ['auth', 'checkPermission'],
], function () {
    Route::post('/upload', 'GalleryController@upload')
        ->name('gallery.upload');

    Route::post('/sorting', 'GalleryController@sorting')
        ->name('gallery.sorting');

    Route::post('/move', 'GalleryController@move')
        ->name('gallery.move');

    Route::post('/delete', 'GalleryController@delete')
        ->name('gallery.delete')->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'admin/prices',
    'middleware' => ['auth', 'checkPermission'],
], function () {
    Route::get('/', 'PricesController@index')
        ->name('prices.index');

    Route::get('/create', 'PricesController@create')
        ->name('prices.create');

    Route::get('/show/{price}', 'PricesController@show')
        ->name('prices.show')
        ->where('id', '[0-9]+');

    Route::get('/{price}/edit', 'PricesController@edit')
        ->name('prices.edit')
        ->where('id', '[0-9]+');

    Route::post('/', 'PricesController@store')
        ->name('prices.store');

    Route::put('price/{price}', 'PricesController@update')
        ->name('prices.update')
        ->where('id', '[0-9]+');

    Route::delete('/price/{price}', 'PricesController@destroy')
        ->name('prices.destroy')
        ->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'admin/price-sources',
    'middleware' => ['auth', 'checkPermission'],
], function () {

    Route::get('/sister-sites', 'PriceSourceController@showSisterSites')
        ->name('price_sources.showSisters');

    Route::post('/sister-sites', 'PriceSourceController@getSisterSites')
        ->name('price_sources.getSisters');

    Route::delete('/{site}', 'PriceSourceController@destroy')
        ->name('price_sources.destroy')
        ->where('id', '[0-9]+');

    Route::post('/', 'PriceSourceController@store')
        ->name('price_sources.store');
});

Route::group([
    'prefix' => 'admin/brands',
    'middleware' => ['auth', 'checkPermission'],
], function () {
    Route::get('/', 'BrandsController@index')
        ->name('brands.index');
    Route::get('/create', 'BrandsController@create')
        ->name('brands.create');
    Route::get('/show/{brand}', 'BrandsController@show')
        ->name('brands.show')->where('id', '[0-9]+');
    Route::get('/{brand}/edit', 'BrandsController@edit')
        ->name('brands.edit')->where('id', '[0-9]+');
    Route::post('/', 'BrandsController@store')
        ->name('brands.store');
    Route::put('brand/{brand}', 'BrandsController@update')
        ->name('brands.update')->where('id', '[0-9]+');
    Route::delete('/brand/{brand}', 'BrandsController@destroy')
        ->name('brands.destroy')->where('id', '[0-9]+');
});

Route::group(
    [
        'prefix' => 'admin/variation_prices',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'VariationPricesController@index')
            ->name('variation_prices.index');

        Route::get('/create', 'VariationPricesController@create')
            ->name('variation_prices.create');

        Route::get('/show/{variationPrice}', 'VariationPricesController@show')
            ->name('variation_prices.show')
            ->where('id', '[0-9]+');

        Route::get('/{variationPrice}/edit', 'VariationPricesController@edit')
            ->name('variation_prices.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'VariationPricesController@store')
            ->name('variation_prices.store');

        Route::put('variation_price/{variationPrice}', 'VariationPricesController@update')
            ->name('variation_prices.update')
            ->where('id', '[0-9]+');

        Route::delete('/variation_price/{variationPrice}', 'VariationPricesController@destroy')
            ->name('variation_prices.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'admin/filter_options',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'FilterOptionsController@index')
            ->name('filter_options.index');

        Route::get('/create', 'FilterOptionsController@create')
            ->name('filter_options.create');

        Route::get('/show/{filterOption}', 'FilterOptionsController@show')
            ->name('filter_options.show')
            ->where('id', '[0-9]+');

        Route::get('/{filterOption}/edit', 'FilterOptionsController@edit')
            ->name('filter_options.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'FilterOptionsController@store')
            ->name('filter_options.store');

        Route::put('filter_option/{filterOption}', 'FilterOptionsController@update')
            ->name('filter_options.update')
            ->where('id', '[0-9]+');

        Route::delete('/filter_option/{filterOption}', 'FilterOptionsController@destroy')
            ->name('filter_options.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'admin/affiliates',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'AffiliatesController@index')
            ->name('affiliates.index');

        Route::get('/create', 'AffiliatesController@create')
            ->name('affiliates.create');

        Route::get('/show/{affiliate}', 'AffiliatesController@show')
            ->name('affiliates.show')
            ->where('id', '[0-9]+');

        Route::get('/{affiliate}/edit', 'AffiliatesController@edit')
            ->name('affiliates.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'AffiliatesController@store')
            ->name('affiliates.store');

        Route::put('affiliate/{affiliate}', 'AffiliatesController@update')
            ->name('affiliates.update')
            ->where('id', '[0-9]+');

        Route::delete('/affiliate/{affiliate}', 'AffiliatesController@destroy')
            ->name('affiliates.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'admin/filter_tabs',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'FilterTabsController@index')
            ->name('filter_tabs.index');

        Route::get('/create', 'FilterTabsController@create')
            ->name('filter_tabs.create');

        Route::get('/show/{filterTab}', 'FilterTabsController@show')
            ->name('filter_tabs.show')
            ->where('id', '[0-9]+');

        Route::get('/{filterTab}/edit', 'FilterTabsController@edit')
            ->name('filter_tabs.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'FilterTabsController@store')
            ->name('filter_tabs.store');

        Route::put('filter_tab/{filterTab}', 'FilterTabsController@update')
            ->name('filter_tabs.update')
            ->where('id', '[0-9]+');

        Route::delete('/filter_tab/{filterTab}', 'FilterTabsController@destroy')
            ->name('filter_tabs.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'admin/filter_sections',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'FilterSectionsController@index')
            ->name('filter_sections.index');

        Route::get('/create', 'FilterSectionsController@create')
            ->name('filter_sections.create');

        Route::get('/show/{filterSection}', 'FilterSectionsController@show')
            ->name('filter_sections.show')
            ->where('id', '[0-9]+');

        Route::get('/{filterSection}/edit', 'FilterSectionsController@edit')
            ->name('filter_sections.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'FilterSectionsController@store')
            ->name('filter_sections.store');

        Route::put('filter_section/{filterSection}', 'FilterSectionsController@update')
            ->name('filter_sections.update')
            ->where('id', '[0-9]+');

        Route::delete('/filter_section/{filterSection}', 'FilterSectionsController@destroy')
            ->name('filter_sections.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'admin/mobile-regions',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'MobileRegionsController@index')
            ->name('mobile_regions.index');

        Route::get('/create', 'MobileRegionsController@create')
            ->name('mobile_regions.create');

        Route::get('/show/{mobileRegion}', 'MobileRegionsController@show')
            ->name('mobile_regions.show')
            ->where('id', '[0-9]+');

        Route::get('/{mobileRegion}/edit', 'MobileRegionsController@edit')
            ->name('mobile_regions.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'MobileRegionsController@store')
            ->name('mobile_regions.store');

        Route::put('mobile_region/{mobileRegion}', 'MobileRegionsController@update')
            ->name('mobile_regions.update')
            ->where('id', '[0-9]+');

        Route::delete('/mobile_region/{mobileRegion}', 'MobileRegionsController@destroy')
            ->name('mobile_regions.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'admin/mobile-prices',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'MobilePricesController@index')
            ->name('mobile_prices.index');

        Route::get('/create', 'MobilePricesController@create')
            ->name('mobile_prices.create');

        Route::get('/show/{mobilePrice}', 'MobilePricesController@show')
            ->name('mobile_prices.show')
            ->where('id', '[0-9]+');

        Route::get('/{mobilePrice}/edit', 'MobilePricesController@edit')
            ->name('mobile_prices.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'MobilePricesController@store')
            ->name('mobile_prices.store');

        Route::put('mobile_price/{mobilePrice}', 'MobilePricesController@update')
            ->name('mobile_prices.update')
            ->where('id', '[0-9]+');

        Route::delete('/mobile_price/{mobilePrice}', 'MobilePricesController@destroy')
            ->name('mobile_prices.destroy')
            ->where('id', '[0-9]+');
    });

//news
Route::group(
    [
        'prefix' => 'admin/news',
        'middleware' => ['auth', 'checkPermission'],
    ], function () {

        Route::get('/', 'NewsController@index')
            ->name('news.index');

        Route::get('/create', 'NewsController@create')
            ->name('news.create');

        Route::get('/show/{id}', 'NewsController@show')
            ->name('news.show')
            ->where('id', '[0-9]+');

        Route::get('/{id}/edit', 'NewsController@edit')
            ->name('news.edit')
            ->where('id', '[0-9]+');

        Route::post('/', 'NewsController@store')
            ->name('news.store');

        Route::put('news/{id}', 'NewsController@update')
            ->name('news.update')
            ->where('id', '[0-9]+');

        Route::delete('/news/{id}', 'NewsController@destroy')
            ->name('news.destroy')
            ->where('id', '[0-9]+');
    });

Route::group(
    [
        'prefix' => 'user/review',
        'middleware' => ['auth'],
    ], function () {

        Route::post('/review-submit/{id}', 'HomeController@userReviewStore')
            ->name('review.store');
    });

Route::group([
    'prefix' => 'admin/user-opinions',
    'middleware' => ['auth', 'checkPermission'],
], function () {

    Route::get('/', 'UserOpinionsController@index')
        ->name('opinions.index');

    Route::get('/{opinions}/edit', 'UserOpinionsController@edit')
        ->name('opinions.edit')
        ->where('id', '[0-9]+');

    Route::put('permission/{opinions}', 'UserOpinionsController@update')
        ->name('opinions.update')
        ->where('id', '[0-9]+');

    Route::delete('/opinions/{opinions}', 'UserOpinionsController@destroy')
        ->name('opinions.destroy')
        ->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'members',
    'middleware' => ['auth'],
], function () {

    Route::get('/account', 'MemberController@account')
        ->name('members.account');

    Route::put('/profile/{userId}', 'MemberController@updateProfile')
        ->name('members.profileUpdate')
        ->where('userId', '[0-9]+');

    Route::put('/change-password/{userId}', 'MemberController@changePassword')
        ->name('members.changePassword')
        ->where('userId', '[0-9]+');

});
