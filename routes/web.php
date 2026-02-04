<?php

use App\Http\Controllers\Web\AdminAuthController;
use App\Http\Controllers\Web\CouponController;
use App\Http\Controllers\Web\DashboardAddressController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DashboardReviewController;
use App\Http\Controllers\Web\FcmTokenController;
use App\Http\Controllers\Web\SuperAdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\Web\DashboardDesignController;
use App\Http\Controllers\Web\DashboardDesignOptionController;
use App\Http\Controllers\Web\DashboardInvoiceController;
use App\Http\Controllers\Web\DashboardOrderController;
use App\Http\Controllers\Web\DashboardUserController;
use App\Http\Controllers\Web\DashboardWalletController;
use App\Http\Controllers\Web\NotificationWebController;
use App\Http\Controllers\Web\StripeRedirectController;
use App\Http\Controllers\Web\Superadmin\AdminController;
use App\Http\Controllers\Web\Superadmin\PermissionController;
use App\Http\Controllers\Web\Superadmin\RoleController;

Route::get('/stripe/success', [StripeRedirectController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeRedirectController::class, 'cancel'])->name('stripe.cancel');

Route::get('/auth/login', fn() => redirect()->route('admin.login'))->name('login');

Route::get('login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('login', [AdminAuthController::class, 'login'])
    ->name('admin.login.post');

// Route::get('/admin/register', [AdminAuthController::class, 'showRegisterForm'])
//     ->name('admin.register');

// Route::post('/admin/register', [AdminAuthController::class, 'register'])
//     ->name('admin.register.post');

// SUPER ADMIN
Route::get('/super-admin/login', [SuperAdminAuthController::class, 'showLoginForm'])
    ->name('super.login');

Route::post('/super-admin/login', [SuperAdminAuthController::class, 'login'])
    ->name('super.login.post');

// Route::get('/super-admin/register', [SuperAdminAuthController::class, 'showRegisterForm'])
//     ->name('super.register');

// Route::post('/super-admin/register', [SuperAdminAuthController::class, 'register'])
//     ->name('super.register.post');


// ================== Logout  ==================

Route::post('/dashboard/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('admin.login');
})->name('dashboard.logout')->middleware('auth');

 
Route::middleware('auth')->get('/dashboard/welcome', function () {
    return view('dashboard.welcome');
})->name('dashboard.welcome');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {


    Route::get('/welcome', [DashboardController::class, 'welcome'])
        ->name('welcome');

    Route::get('/addresses', [DashboardAddressController::class, 'index'])
        ->name('addresses.index');
    Route::resource('design-options', DashboardDesignOptionController::class)
        ->except(['show']);
    Route::get('/designs', [DashboardDesignController::class, 'index'])
        ->name('designs.index');
    Route::get('/designs/{design}', [DashboardDesignController::class, 'show'])
        ->name('designs.show');

    Route::get('/wallets', [DashboardWalletController::class, 'index'])->name('wallets.index');
    Route::get('/wallets/{user}', [DashboardWalletController::class, 'show'])->name('wallets.show');
    Route::post('/wallets/{user}/charge', [DashboardWalletController::class, 'charge'])->name('wallets.charge');

    Route::get('/orders', [DashboardOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [DashboardOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [DashboardOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');



    Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
    Route::get('/users', [DashboardUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [DashboardUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/status', [DashboardUserController::class, 'updateStatus'])->name('users.updateStatus');


    Route::get('/invoices', [DashboardInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [DashboardInvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [DashboardInvoiceController::class, 'download'])->name('invoices.download');

    Route::get('/reviews', [DashboardReviewController::class, 'index'])->name('reviews.index');
    ////////////////////////////////////
 Route::get('/notifications', [NotificationWebController::class, 'index'])->name('notifications.index');

    Route::post('/notifications/mark-all-read', [NotificationWebController::class, 'markAllRead'])
        ->name('notifications.markAllRead');

    Route::post('/notifications/{id}/mark-read', [NotificationWebController::class, 'markRead'])
        ->whereUuid('id')
        ->name('notifications.markRead');

    Route::get('/notifications/{id}/open', [NotificationWebController::class, 'open'])
        ->whereUuid('id')
        ->name('notifications.open');

Route::post('/fcm-tokens', [FcmTokenController::class, 'store'])
    ->middleware('auth')
    ->name('fcm_tokens.store');
    Route::delete('/fcm-tokens', [FcmTokenController::class, 'destroy'])->middleware('auth')
  ->name('fcm-tokens.destroy');




});

Route::middleware(['web', 'auth'])
    ->prefix('dashboard/superadmin')
    ->name('dashboard.superadmin.')
    ->group(function () {

        // Permissions
        Route::resource('permissions', PermissionController::class)
            ->except(['show']);

        // Roles
        Route::resource('roles', RoleController::class)
            ->except(['show']);

        // Admins
        Route::resource('admins', AdminController::class)->except(['show']);


        Route::get('admins/{admin}/role', [AdminController::class, 'editRole'])
            ->name('admins.editRole');

        Route::put('admins/{admin}/role', [AdminController::class, 'updateRole'])
            ->name('admins.updateRole');


    });