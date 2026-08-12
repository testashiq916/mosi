<?php

use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\Member\MemberDonationController;
use App\Http\Controllers\Member\MemberProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Portal Routes
|--------------------------------------------------------------------------
| Wires up the three controllers that were actually present in the source
| dump (MemberDashboardController, MemberDonationController,
| MemberProfileController). Loaded with the 'auth' middleware and a
| 'member.' route-name prefix, matching the route() calls used inside
| those controllers and their views.
*/

Route::middleware(['auth'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [MemberProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [MemberProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/documents', [MemberProfileController::class, 'uploadDocument'])->name('profile.documents.upload');
    Route::delete('/profile/documents/{id}', [MemberProfileController::class, 'deleteDocument'])->name('profile.documents.delete');
    Route::get('/donations-list', [MemberProfileController::class, 'getDonations'])->name('donations.list');
    Route::get('/receipts', [MemberProfileController::class, 'getReceipts'])->name('receipts');
    Route::get('/receipts/{id}/download', [MemberProfileController::class, 'downloadReceipt'])->name('receipts.download');

    Route::get('/donate', [MemberDonationController::class, 'index'])->name('donations');
    Route::post('/donate', [MemberDonationController::class, 'store'])->name('donations.store');
    Route::get('/donate/recurring', [MemberDonationController::class, 'recurringDonations'])->name('recurring-donations');
    Route::post('/donate/recurring/{id}/cancel', [MemberDonationController::class, 'cancelRecurring'])->name('donations.recurring.cancel');
    Route::get('/donate/history', [MemberDonationController::class, 'donationHistory'])->name('donation-history');
    Route::get('/donate/{id}/receipt', [MemberDonationController::class, 'downloadReceipt'])->name('donations.receipt');
});
