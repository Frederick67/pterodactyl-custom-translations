<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'csrf'])->prefix(config('billing.path'))->namespace('Billing')->group(function () {

  Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', 'Admin\AdminCoreController@index')->name('admin.billing');
    Route::post('/general_setiings', 'Admin\AdminCoreController@setSetting')->name('admin.billing.set.settings');
  
    Route::get('/admin/games', 'Admin\AdminCoreController@games')->name('admin.billing.games');
    Route::post('/admin/new/game', 'Admin\AdminCoreController@createGame')->name('admin.billing.create.game');
    Route::post('/admin/edit/game', 'Admin\AdminCoreController@editGame')->name('admin.billing.edit.game');
    Route::post('/admin/delete/game', 'Admin\AdminCoreController@deleteGame')->name('admin.billing.delete.game');
  
    Route::get('/admin/plans', 'Admin\AdminCoreController@plans')->name('admin.billing.plans');
    Route::post('/admin/new/plan', 'Admin\AdminCoreController@createPlan')->name('admin.billing.create.plan');
    Route::post('/admin/edit/plan', 'Admin\AdminCoreController@editPlan')->name('admin.billing.edit.plan');
    Route::post('/admin/delete/plan', 'Admin\AdminCoreController@deletePlan')->name('admin.billing.delete.plan');
    
    Route::get('/admin/users', 'Admin\AdminCoreController@users')->name('admin.billing.users');
    Route::get('/admin/user/{id}/invoices', 'Admin\AdminCoreController@userInvoices')->name('admin.billing.user.invoices');
    Route::get('/admin/user/{id}/payments', 'Admin\AdminCoreController@userPayments')->name('admin.billing.user.payments');
    Route::post('/admin/users/balance', 'Admin\AdminCoreController@newBalance')->name('admin.billing.users.balance');
  
    Route::get('/admin/pages', 'Admin\AdminCoreController@getPages')->name('admin.billing.pages');
    Route::get('/admin/pages/new', 'Admin\AdminCoreController@createPage')->name('admin.billing.pages.new');
    Route::get('/admin/pages/{id}/edit', 'Admin\AdminCoreController@updatePage')->name('admin.billing.pages.edit');
    Route::post('/admin/pages/save', 'Admin\AdminCoreController@savePage')->name('admin.billing.pages.save');
    Route::post('/admin/pages/delete', 'Admin\AdminCoreController@deletePage')->name('admin.billing.pages.delete');
  
    Route::get('/admin/alerts', 'Admin\AdminCoreController@alerts')->name('admin.billing.alerts');
  
    Route::get('/admin/meta', 'Admin\AdminCoreController@meta')->name('admin.billing.meta');
  
    Route::get('/admin/update', 'Admin\AdminCoreController@update')->name('admin.update');
  
  });
  
  Route::get('/toggle', 'CoreController@toggleMode')->name('billing.toggle.mode');
  Route::get('/toggle/lang/{lang}', 'CoreController@toggleUserLang')->name('billing.toggle.lang');
  
  // Billing Index Route -> /billing/
  Route::get('/', 'CoreController@index')->name('billing.link');
  
  // Billing Account Balance Route -> /billing/balance
  Route::any('/balance', 'ProfileController@index')->name('billing.balance');
  Route::any('/balance/update', 'ProfileController@updateUser')->name('billing.user.update');
  
  
  Route::post('/balance/stripe', 'ProfileController@stripe')->name('billing.balance.stripe');
  
  // Billing Cart Route -> /billing/cart
  Route::get('/cart', 'CartController@index')->name('billing.cart');
  Route::get('/cart/order/all', 'CartController@orderAll')->name('billing.cart.order.all');
  Route::post('/add/cart', 'CartController@addToCart')->name('billing.add.cart');
  Route::post('/remove/cart', 'CartController@removeCart')->name('billing.remove.cart');
  
  // Billing Cart Route -> /billing/invoices
  Route::get('/invoices', 'InvoicesController@index')->name('billing.invoices');
  Route::get('/invoice/{id}', 'InvoicesController@view')->name('billing.invoice.view');
  Route::get('/invoice/{id}/update', 'InvoicesController@orderUpdate')->name('billing.invoice.update');
  Route::get('/invoice/{id}/delete', 'InvoicesController@invoiceDelete')->name('billing.invoice.delete');
  
  
  // Game Plans Route -> /billing/{game}/plans
  Route::get('/{game}/plans', 'PlansController@getPlans')->name('billing.plans');
  
  
  // Custom pages
  Route::get('/p/{page}', 'CoreController@getPage')->name('billing.custom.page');

});

Route::group(['middleware' => 'guest'], function () {
  Route::any('/billing/scheduler', 'Billing\CoreController@scheduler')->name('billing.scheduler');
});
