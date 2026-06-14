<?php

use App\Http\Controllers\AgentContactLeadController;
use App\Http\Controllers\AgentReviewController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ContactInquiryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HeroCarouselController;
use App\Http\Controllers\Admin\ListingBannerController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\ListingController;
use App\Http\Controllers\Agent\ProfileController;
use App\Http\Controllers\Agent\ReviewController as AgentReviewModerationController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ListingBrowseController;
use App\Http\Controllers\ListingContactLeadController;
use App\Http\Controllers\LocateController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\SitePageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalController::class, 'index'])->name('portal.home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/listings', [ListingBrowseController::class, 'index'])->name('listings.index');
Route::get('/listings/{listing}', [ListingBrowseController::class, 'show'])->name('listings.show');
Route::post('/listings/{listing}/contact-leads', [ListingContactLeadController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('listings.contact-leads.store');

Route::get('/locate-me', LocateController::class)->name('locate');
Route::get('/agents/{agent}/portfolio', [PortalController::class, 'agentPortfolio'])->name('agents.portfolio');
Route::post('/agents/{agent}/contact-leads', [AgentContactLeadController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('agents.contact-leads.store');
Route::post('/agents/{agent}/reviews', [AgentReviewController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('agents.reviews.store');

Route::get('/projects', [SitePageController::class, 'projects'])->name('projects');
Route::redirect('/invest-now', '/listings?kind=projects');
Route::get('/wanted', [SitePageController::class, 'wanted'])->name('wanted');
Route::get('/find-realtor', [SitePageController::class, 'findRealtor'])->name('find-realtor');
Route::get('/owners', [SitePageController::class, 'owners'])->name('owners');
Route::redirect('/grab-me', '/owners');
Route::get('/about-us', [SitePageController::class, 'about'])->name('about');
Route::get('/contact-us', [SitePageController::class, 'contact'])->name('contact');
Route::post('/contact-us', [SitePageController::class, 'submitContact'])->name('contact.submit');

Route::redirect('register/owner', '/register?role=owner')->name('register.owner');
Route::view('register/pending', 'auth.register-pending')->name('register.pending');

Auth::routes();

Route::get('/home', function () {
    if (auth()->check()) {
        return redirect(\App\Providers\RouteServiceProvider::homeFor(auth()->user()));
    }

    return redirect()->route('portal.home');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('users/pending', [UserApprovalController::class, 'index'])->name('users.pending');
    Route::post('users/{user}/approve', [UserApprovalController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/reject', [UserApprovalController::class, 'reject'])->name('users.reject');
    Route::get('agents', [AgentController::class, 'index'])->name('agents.index');
    Route::patch('agents/{user}/rating', [AgentController::class, 'updateRating'])->name('agents.rating');
    Route::resource('cities', CityController::class)->except(['show']);
    Route::get('hero-carousel', [HeroCarouselController::class, 'edit'])->name('hero-carousel.edit');
    Route::put('hero-carousel', [HeroCarouselController::class, 'update'])->name('hero-carousel.update');
    Route::get('listing-banners', [ListingBannerController::class, 'index'])->name('listing-banners.index');
    Route::get('listing-banners/{kind}', [ListingBannerController::class, 'edit'])->name('listing-banners.edit');
    Route::put('listing-banners/{kind}', [ListingBannerController::class, 'update'])->name('listing-banners.update');
    Route::get('contact-inquiries', [ContactInquiryController::class, 'index'])->name('contact-inquiries.index');
    Route::get('contact-inquiries/{contactInquiry}', [ContactInquiryController::class, 'show'])->name('contact-inquiries.show');
});

Route::middleware(['auth', 'portal', 'role:agent', 'approved'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('reviews', [AgentReviewModerationController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/approve', [AgentReviewModerationController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [AgentReviewModerationController::class, 'reject'])->name('reviews.reject');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('listings', ListingController::class)->except(['show']);
});

Route::middleware(['auth', 'portal', 'role:owner', 'approved'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('listings', ListingController::class)->except(['show']);
});
