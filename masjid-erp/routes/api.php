<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sample API Routes for Additional Modules
|--------------------------------------------------------------------------
| This is the routing file exactly as it was found in the source dump
| (deepseek_php_20260728_6d2bb6.php). It references MahallaController,
| MarriageController, UtensilController, MeetingController,
| AssetController, StaffController and NotificationController — none of
| those controllers were included in the zip, only the database schema
| for their underlying tables (see database/migrations and
| database/schema). The route list itself registers fine (PHP does not
| resolve ::class references at load time), but every endpoint below will
| throw a class-not-found error the moment it's hit until those
| controllers are written. Kept here unmodified as a reference for the
| routes that go with the schema.
*/

use App\Http\Controllers\API\V1\Mahalla\MahallaController;
use App\Http\Controllers\API\V1\Marriage\MarriageController;
use App\Http\Controllers\API\V1\Rental\UtensilController;
use App\Http\Controllers\API\V1\Meeting\MeetingController;
use App\Http\Controllers\API\V1\Asset\AssetController;
use App\Http\Controllers\API\V1\Staff\StaffController;
use App\Http\Controllers\API\V1\Notification\NotificationController;

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        // Mahalla Management
        Route::prefix('mahalla')->group(function () {
            Route::get('/residents', [MahallaController::class, 'index']);
            Route::post('/residents', [MahallaController::class, 'store']);
            Route::get('/residents/{id}', [MahallaController::class, 'show']);
            Route::put('/residents/{id}', [MahallaController::class, 'update']);
            Route::delete('/residents/{id}', [MahallaController::class, 'destroy']);
            Route::post('/residents/{id}/dependents', [MahallaController::class, 'addDependent']);
        });

        // Marriage Management
        Route::prefix('marriage')->group(function () {
            Route::get('/registrations', [MarriageController::class, 'registrations']);
            Route::post('/registrations', [MarriageController::class, 'register']);
            Route::get('/registrations/{id}', [MarriageController::class, 'showRegistration']);
            Route::post('/registrations/{id}/approve', [MarriageController::class, 'approve']);
            Route::post('/noc', [MarriageController::class, 'requestNOC']);
            Route::get('/noc/{id}', [MarriageController::class, 'showNOC']);
        });

        // Utensil Rental
        Route::prefix('utensils')->group(function () {
            Route::get('/', [UtensilController::class, 'index']);
            Route::post('/', [UtensilController::class, 'store']);
            Route::get('/{id}', [UtensilController::class, 'show']);
            Route::post('/{id}/rent', [UtensilController::class, 'rent']);
            Route::post('/rentals/{id}/return', [UtensilController::class, 'return']);
            Route::get('/rentals', [UtensilController::class, 'getRentals']);
        });

        // Meetings
        Route::prefix('meetings')->group(function () {
            Route::get('/', [MeetingController::class, 'index']);
            Route::post('/', [MeetingController::class, 'store']);
            Route::get('/{id}', [MeetingController::class, 'show']);
            Route::put('/{id}', [MeetingController::class, 'update']);
            Route::post('/{id}/actions', [MeetingController::class, 'addAction']);
            Route::put('/actions/{id}/complete', [MeetingController::class, 'completeAction']);
        });

        // Assets
        Route::prefix('assets')->group(function () {
            Route::get('/', [AssetController::class, 'index']);
            Route::post('/', [AssetController::class, 'store']);
            Route::get('/{id}', [AssetController::class, 'show']);
            Route::put('/{id}', [AssetController::class, 'update']);
            Route::delete('/{id}', [AssetController::class, 'destroy']);
            Route::post('/{id}/maintenance', [AssetController::class, 'scheduleMaintenance']);
        });

        // Staff
        Route::prefix('staff')->group(function () {
            Route::get('/', [StaffController::class, 'index']);
            Route::post('/', [StaffController::class, 'store']);
            Route::get('/{id}', [StaffController::class, 'show']);
            Route::put('/{id}', [StaffController::class, 'update']);
            Route::post('/{id}/payroll', [StaffController::class, 'processPayroll']);
            Route::get('/{id}/attendance', [StaffController::class, 'getAttendance']);
            Route::post('/{id}/attendance', [StaffController::class, 'markAttendance']);
        });

        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::post('/send', [NotificationController::class, 'send']);
            Route::get('/templates', [NotificationController::class, 'getTemplates']);
            Route::post('/templates', [NotificationController::class, 'createTemplate']);
            Route::get('/types', [NotificationController::class, 'getTypes']);
        });
    });
});
