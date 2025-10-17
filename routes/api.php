<?php

use App\Http\Controllers\Api\V1\AnnouncementController;
use App\Http\Controllers\Api\V1\AttractionController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\FacilityController;
use App\Http\Controllers\Api\V1\InquiryController;
use App\Http\Controllers\Api\V1\MerchantController;
use App\Http\Controllers\Api\V1\StatusTodayController;
use App\Http\Controllers\Api\V1\TicketTypeController;
use App\Http\Controllers\Api\V1\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Api\V1\Admin\AttractionController as AdminAttractionController;
use App\Http\Controllers\Api\V1\Admin\ClosureController as AdminClosureController;
use App\Http\Controllers\Api\V1\Admin\EventController as AdminEventController;
use App\Http\Controllers\Api\V1\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Api\V1\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Api\V1\Admin\InquiryStatusController;
use App\Http\Controllers\Api\V1\Admin\MerchantController as AdminMerchantController;
use App\Http\Controllers\Api\V1\Admin\OperatingHourController as AdminOperatingHourController;
use App\Http\Controllers\Api\V1\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\V1\Admin\TicketTypeController as AdminTicketTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('status-today', [StatusTodayController::class, 'index']);
    Route::get('announcements', [AnnouncementController::class, 'index']);
    Route::get('events', [EventController::class, 'index']);
    Route::get('attractions', [AttractionController::class, 'index']);
    Route::get('attractions/{slug}', [AttractionController::class, 'show'])->where('slug', '^(?![0-9]+$)[A-Za-z0-9-]+$');
    Route::get('ticket-types', [TicketTypeController::class, 'index']);
    Route::get('facilities', [FacilityController::class, 'index']);
    Route::get('merchants', [MerchantController::class, 'index']);

    Route::middleware('throttle:inquiries')->post('inquiries', [InquiryController::class, 'store']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('attractions', AdminAttractionController::class)->except(['index']);
        Route::apiResource('operating-hours', AdminOperatingHourController::class);
        Route::apiResource('ticket-types', AdminTicketTypeController::class)->except(['index']);
        Route::apiResource('events', AdminEventController::class)->except(['index']);
        Route::apiResource('announcements', AdminAnnouncementController::class)->except(['index']);
        Route::apiResource('facilities', AdminFacilityController::class)->except(['index']);
        Route::apiResource('merchants', AdminMerchantController::class)->except(['index']);
        Route::apiResource('inquiries', AdminInquiryController::class)->except(['store']);
        Route::apiResource('closures', AdminClosureController::class);
        Route::apiResource('settings', AdminSettingController::class);

        Route::patch('inquiries/{inquiry}/status', [InquiryStatusController::class, 'update'])->name('inquiries.status');
    });
});
