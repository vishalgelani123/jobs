<?php

use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Auth\VendorOTPVerifyController;
use App\Http\Controllers\Backend\AdminBranchController;
use App\Http\Controllers\Backend\AdminVendorController;
use App\Http\Controllers\Backend\ApprovalController;
use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\CountryStateCityController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DocumentController;
use App\Http\Controllers\Backend\GeneralChargeController;
use App\Http\Controllers\Backend\GeneralTermConditionCategoryController;
use App\Http\Controllers\Backend\GeneralTermConditionController;
use App\Http\Controllers\Backend\InquiryAwardController;
use App\Http\Controllers\Backend\InquiryContactDetailController;
use App\Http\Controllers\Backend\InquiryGeneralChargeController;
use App\Http\Controllers\Backend\InquiryProductDetailController;
use App\Http\Controllers\Backend\InquiryReportController;
use App\Http\Controllers\Backend\InquiryVendorRateDetailController;
use App\Http\Controllers\Backend\PreVendorCategoryController;
use App\Http\Controllers\Backend\PreVendorDetailController;
use App\Http\Controllers\Backend\PreVendorFollowupDetailController;
use App\Http\Controllers\Backend\PreVendorSubCategoryController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\ReplaceFileController;
use App\Http\Controllers\Backend\ResetPasswordController;
use App\Http\Controllers\Backend\ResInquiryMasterController;
use App\Http\Controllers\Backend\SendMailController;
use App\Http\Controllers\Backend\SmtpSettingController;
use App\Http\Controllers\Backend\TermConditionCategoryController;
use App\Http\Controllers\Backend\TermConditionController;
use App\Http\Controllers\Backend\TestController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\VendorController;
use App\Http\Controllers\Backend\VendorDashboardController;
use App\Http\Controllers\Backend\VendorDocTypeController;
use App\Http\Controllers\Backend\VendorInquiryMasterController;
use App\Http\Controllers\Backend\VendorReportController;
use App\Http\Controllers\Backend\VendorTypeController;
use App\Http\Controllers\Backend\WhatsAppSettingController;
use App\Http\Controllers\Backend\HeadInquiryController;
use App\Http\Controllers\Backend\HeadInquiryProductDetailController;
use App\Http\Controllers\Frontend\InvitationController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);

Route::get('fresh-seed', function () {
    try {
        Artisan::call('migrate:fresh --seed');
        dd("Fresh seed done");
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});

Route::get('cache-clear', function () {
    try {
        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        dd("optimize, config, and cache cleared");
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});

Route::get('up', function () {
    try {
        Artisan::call('up');
        dd("Website Up");
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});

Route::get('down', function () {
    try {
        Artisan::call('down');
        dd("Website Down");
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});

/*Route::fallback(function () {
    return view('errors.500');
});*/

Route::get('/', function () {
    if (!Auth::user()) {
        return redirect()->route('login');
    }
    if (Auth::user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::user()->hasRole('employer')) {
        return redirect()->route('employer.dashboard');
    }

    return redirect()->route('candidate.dashboard');
})->name('index');

Route::get('home', function () {
    return redirect()->route('index');
})->name('home');

Route::get('back-to-login', function () {
    if (Auth::check()) {
        Auth::logout();
    }
    return redirect()->route('login');
})->name('back.to.login');



Route::group(['middleware' => ['auth']], function () {
    Route::get('admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('candidate-dashboard', [DashboardController::class, 'candidateDashboard'])->name('candidate.dashboard');
    Route::get('employer-dashboard', [DashboardController::class, 'employerDashoard'])->name('employer.dashboard');
    Route::group(['prefix' => 'jobs', 'as' => 'jobs.'], function () {
        Route::get('/', [JobController::class, 'index'])->name('index');
        Route::post('apply', [JobController::class, 'apply'])->name('apply');
        Route::post('store', [JobController::class, 'store'])->name('store');
        Route::post('edit', [JobController::class, 'edit'])->name('edit');
        Route::group(['prefix' => '{job}'], function () {
            Route::post('status-change', [JobController::class, 'statusChange'])->name('status.change');
            Route::post('update', [JobController::class, 'update'])->name('update');
            Route::post('delete', [JobController::class, 'delete'])->name('delete');
        });
    });

    Route::group(['prefix' => 'submited-application', 'as' => 'submited-application.'], function () {
        Route::get('/', [JobController::class, 'submitedApplication'])->name('index');
        Route::post('store', [JobController::class, 'store'])->name('store');
        Route::post('edit', [JobController::class, 'edit'])->name('edit');
        Route::post('status-change', [JobController::class, 'statusChange'])->name('status.change');
        Route::group(['prefix' => '{application}'], function () {

            Route::post('update', [JobController::class, 'update'])->name('update');
            Route::post('delete', [JobController::class, 'delete'])->name('delete');
        });
    });

});




