<?php

use App\Http\Controllers\Api\Admin\DesignOptionController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\User\AddressController;
use App\Http\Controllers\Api\User\DesignController;
use App\Http\Controllers\Api\User\InvoiceController as UserInvoiceController;
use App\Http\Controllers\Api\User\ReviewController;
use App\Http\Controllers\Api\User\WalletController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeWebhookController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
//stripe listen --forward-to http://127.0.0.1:8000/api/stripe/webhook

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

//  Route::get("check", function () {
//         $user =User::findOrFail(11);
//         $role= Role::findOrFail($id);
//         $user->assignRole($role);


//     });

Route::prefix("Auth")->group(function () {
    Route::post("register", [AuthController::class, "register"]);
    Route::post("login", [AuthController::class, "login"]);
});
Route::middleware("auth:sanctum")->group(function () {
    Route::get("profile", [AuthController::class, "profile"]);
    Route::put("profile", [AuthController::class, "edit_profile"]);
    Route::delete("profile", [AuthController::class, "delete_profile"]);

    Route::prefix("address")->group(function () {
        Route::post("/", [AddressController::class, "create"]);
        Route::put("/{id}", [AddressController::class, "update"]);
        Route::delete("/{id}", [AddressController::class, "delete"]);
        Route::get("/", [AddressController::class, "index"]);
    });
    Route::prefix("design_option")->group(function () {
        Route::get("/", [DesignOptionController::class, "index"]);
        Route::post("/", [DesignOptionController::class, "create"])->middleware("permission:create design option");
        Route::put("/{id}", [DesignOptionController::class, "update"])->middleware("permission:update design option");
        Route::delete("/{id}", [DesignOptionController::class, "delete"])->middleware("permission:delete design option");


    });
    Route::prefix("design")->group(function () {
        Route::get("/", [DesignController::class, "index"])->middleware("permission:view design");
        Route::get("/{id}", [DesignController::class, "show"])->middleware("permission:view design");
        Route::post("/", [DesignController::class, "create"])->middleware("permission:create design");
        Route::put("/{id}", [DesignController::class, "update"])->middleware("permission:update design");
        Route::delete("/{id}", [DesignController::class, "delete"])->middleware("permission:delete design");

    });
    Route::prefix("orders")->group(function () {
        Route::post("/", [OrderController::class, "create"]);
        Route::get("/", [OrderController::class, "index"]);
        Route::put("/{order}", [OrderController::class, "update"]);



    });
    Route::prefix("wallet")->group(function () {
        Route::get("/", [WalletController::class, "index"]);
        Route::post("/", [WalletController::class, "create"]);

    });
    Route::prefix('invoices')->group(function () {
    Route::get('/', [UserInvoiceController::class, 'index']);                 // JSON collection
    Route::get('/order/{order}', [UserInvoiceController::class, 'showByOrder']); // JSON one
    Route::get('/order/{order}/download', [UserInvoiceController::class, 'downloadByOrder']); // PDF download
});

 Route::prefix('reviews')->group(function () {
    Route::post('/', [ReviewController::class, 'store']);
    Route::get('/order/{order}', [ReviewController::class, 'myByOrder']);
});

Route::prefix('notifications')->group(function () {
     Route::get('/', [NotificationController::class, 'index']);
    Route::get('/summary', [NotificationController::class, 'summary']);

    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead']);
    Route::post('/{id}/mark-read', [NotificationController::class, 'markRead']);
});


});
