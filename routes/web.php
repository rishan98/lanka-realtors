<?php

use App\Http\Controllers\Admin\ContactInquiryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\ListingController;
use App\Http\Controllers\Agent\ProfileController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ListingBrowseController;
use App\Http\Controllers\LocateController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\SitePageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalController::class, 'index'])->name('portal.home');

Route::get('/listings', [ListingBrowseController::class, 'index'])->name('listings.index');
Route::get('/listings/{listing}', [ListingBrowseController::class, 'show'])->name('listings.show');

Route::get('/locate-me', LocateController::class)->name('locate');
Route::get('/agents/{agent}/portfolio', [PortalController::class, 'agentPortfolio'])->name('agents.portfolio');

Route::get('/invest-now', [SitePageController::class, 'investNow'])->name('invest');
Route::get('/wanted', [SitePageController::class, 'wanted'])->name('wanted');
Route::get('/find-realtor', [SitePageController::class, 'findRealtor'])->name('find-realtor');
Route::get('/grab-me', [SitePageController::class, 'grabMe'])->name('grab-me');
Route::get('/about-us', [SitePageController::class, 'about'])->name('about');
Route::get('/contact-us', [SitePageController::class, 'contact'])->name('contact');
Route::post('/contact-us', [SitePageController::class, 'submitContact'])->name('contact.submit');

Route::redirect('register/owner', '/register?role=owner')->name('register.owner');
Route::view('register/pending', 'auth.register-pending')->name('register.pending');

Auth::routes();

Route::redirect('/home', '/agent/dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('users/pending', [UserApprovalController::class, 'index'])->name('users.pending');
    Route::post('users/{user}/approve', [UserApprovalController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/reject', [UserApprovalController::class, 'reject'])->name('users.reject');
    Route::get('contact-inquiries', [ContactInquiryController::class, 'index'])->name('contact-inquiries.index');
    Route::get('contact-inquiries/{contactInquiry}', [ContactInquiryController::class, 'show'])->name('contact-inquiries.show');
});

Route::middleware(['auth', 'role:agent', 'approved'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('listings', ListingController::class)->except(['show']);
});

Route::middleware(['auth', 'role:owner', 'approved'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('listings', ListingController::class)->except(['show']);
});
