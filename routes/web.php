<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AgencyInfoController;
use App\Http\Controllers\Module3_Controoler\InquiryAssignmentController;
use App\Http\Controllers\Module3_Controoler\AgencyReviewAndNotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ================= MODULE 4 controller ================= //
use App\Http\Controllers\Module4\NotificationController;
use App\Http\Controllers\Module4\AgencyDashboardController;


// ================= PUBLIC USER ROUTES ================= //

Route::prefix('publicuser')->group(function () {
    // Login & Register
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('publicuser.login');
    Route::post('/login', [UserController::class, 'login'])->name('publicuser.login.submit');

    Route::get('/register', [UserController::class, 'showPublicRegisterForm'])->name('publicuser.register');
    Route::post('/register', [UserController::class, 'registerPublic'])->name('publicuser.register.submit');

    // Profile & Dashboard (requires login)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [UserController::class, 'showPublicProfile'])->name('publicuser.dashboard');
        Route::get('/profile', [UserController::class, 'showPublicProfile'])->name('publicuser.profile');
        Route::post('/profile/update', [UserController::class, 'updatePublicProfile'])->name('profile.update');
        // Add this route for the change password view
        Route::get('/edit-password', function () {
            return view('ManageUser.publicuser.edit_password');
        })->name('publicuser.edit_password');

        // View Assigned Agencies
        Route::get('/view-assigned', [InquiryAssignmentController::class, 'showViewAssigned'])->name('publicuser.view.assigned');

        // Agency Information
        Route::get('/agency-info', [InquiryAssignmentController::class, 'showAgencyInfo'])->name('publicuser.agency.info');
        Route::get('/agency-details/{id}', [AgencyInfoController::class, 'getAgencyDetails'])->name('publicuser.agency.details');
        Route::post('/agency-search', [AgencyInfoController::class, 'searchAgencies'])->name('publicuser.agency.search');

        // Inquiry routes for public users
        Route::get('/inquiry/create', [InquiryController::class, 'create'])->name('inquiry.create');
        Route::post('/inquiry/store', [InquiryController::class, 'store'])->name('inquiry.store');
        Route::get('/inquiry/browse', [InquiryController::class, 'publicInquiries'])->name('inquiry.browse');
        Route::get('/inquiry/{id}', [InquiryController::class, 'show'])->name('inquiry.show');
        Route::get('/inquiry-dashboard', [InquiryController::class, 'userDashboard'])->name('inquiry.dashboard');
    });

    // Password Recovery (no login required)
    Route::get('/recover', [UserController::class, 'showRecoveryForm'])->name('publicuser.recover');
    Route::post('/recover', [UserController::class, 'handleRecoveryEmail'])->name('publicuser.recover.submit');
    Route::get('/recover/password', [UserController::class, 'showPasswordResetForm'])->name('publicuser.recover.password');
    Route::post('/recover/password', [UserController::class, 'handlePasswordReset'])->name('publicuser.recover.password.submit');
});

// ================= SHARED HOME PAGE ================= //

Route::middleware('auth')->get('/home', function () {
    return redirect()->route('publicuser.dashboard');
})->name('home');

// ================= LOGOUT ROUTE ================= //

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// ================= AGENCY ROUTES ================= //

Route::prefix('agency')->group(function () {
    // Public agency routes (no login required)
    Route::get('/login', [UserController::class, 'showAgencyLoginForm'])->name('agency.login');
    Route::post('/login', [UserController::class, 'loginAgency'])->name('agency.login.submit');

    // Protected agency routes (require login)
    Route::middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('agency.dashboard');

        // Authentication routes
        Route::post('/logout', [UserController::class, 'logout'])->name('agency.logout');

        // Password update routes
        Route::get('/recover-password', [UserController::class, 'showAgencyPasswordRecovery'])
            ->name('agency.password.recover');
        Route::get('/password/update', [UserController::class, 'showAgencyPasswordUpdateForm'])
            ->name('agency.password.update.form');
        Route::post('/password/update', [UserController::class, 'handleAgencyPasswordUpdate'])
            ->name('agency.password.update');

        // Profile management
        Route::get('/profile', [UserController::class, 'showAgencyProfile'])->name('agency.profile');
        Route::post('/profile/update', [UserController::class, 'updateAgencyProfile'])->name('agency.profile.update');
        Route::get('/edit-password', function () {
            return view('ManageUser.agency.edit_password');
        })->name('agency.edit_password');
        Route::post('/profile/password', [UserController::class, 'updateAgencyPassword'])->name('agency.password.change');

        // Agency features
        Route::get('/settings', [UserController::class, 'showAgencySettings'])->name('agency.settings');
        Route::get('/inquiries', [UserController::class, 'showAgencyInquiries'])->name('agency.inquiries');
        Route::get('/reports', [UserController::class, 'showAgencyReports'])->name('agency.reports');
        Route::get('/view', [UserController::class, 'showAgencyView'])->name('agency.view');

        // Display Inquiry Details (Agency Staff Module)
        Route::get('/display-inquiry', [AgencyReviewAndNotificationController::class, 'showDisplayInquiryDetails'])
            ->name('agency.display.inquiry');
        Route::get('/inquiry-detail/{id}', [AgencyReviewAndNotificationController::class, 'showInquiryDetail'])
            ->name('agency.inquiry.detail');
        Route::post('/inquiry/{id}/status', [AgencyReviewAndNotificationController::class, 'updateInquiryStatus'])
            ->name('agency.inquiry.status.update');

        // Accept/Reject Inquiry Routes (Agency Staff Module)
        Route::get('/accept-reject', [AgencyReviewAndNotificationController::class, 'showAcceptRejectInquiries'])
            ->name('agency.accept.reject');
        Route::post('/inquiry/{id}/accept', [AgencyReviewAndNotificationController::class, 'acceptInquiry'])
            ->name('agency.inquiry.accept');
        Route::get('/inquiry/{id}/reject-comments', [AgencyReviewAndNotificationController::class, 'showAddRejectComments'])
            ->name('agency.inquiry.reject.comments');
        Route::post('/inquiry/{id}/reject', [AgencyReviewAndNotificationController::class, 'rejectInquiry'])
            ->name('agency.inquiry.reject');

        // Inquiry detail route for AJAX calls
        Route::get('/inquiry-detail/{id}', [AgencyReviewAndNotificationController::class, 'showInquiryDetail'])
            ->name('agency.inquiry.detail');

        // Inquiry detail routes for agencies
        Route::get('/inquiry/{id}', [InquiryController::class, 'show'])->name('agency.inquiry.show');
        Route::get('/inquiry/view/{id}', [AgencyReviewAndNotificationController::class, 'showInquiryDetail'])
            ->name('agency.inquiry.view');

        // Agency Inquiry List with Advanced Filtering
        Route::get('/inquiry-list', [InquiryController::class, 'showAgencyInquiryList'])
            ->name('agency.inquiry.list');

        // Inquiry Assignment Routes (New Agency Management Features)
        Route::get('/assignment-notes', [InquiryAssignmentController::class, 'showAssignmentNotes'])
            ->name('inquiry.assignment.notes');
        Route::post('/assignment-notes/add', [InquiryAssignmentController::class, 'addAssignmentNotes'])
            ->name('inquiry.assignment.notes.add');

        Route::get('/review-assign', [InquiryAssignmentController::class, 'showReviewAndAssign'])
            ->name('inquiry.assignment.review');
        Route::post('/inquiry/assign', [InquiryAssignmentController::class, 'updateInquiryStatus'])
            ->name('inquiry.assignment.assign');
        Route::post('/inquiry/status', [InquiryAssignmentController::class, 'updateInquiryStatus'])
            ->name('inquiry.assignment.status');

        Route::get('/submitted-inquiries', [InquiryAssignmentController::class, 'showSubmittedInquiries'])
            ->name('inquiry.assignment.submitted');

        // Export routes for submitted inquiries
        Route::get('/inquiries/export', [InquiryAssignmentController::class, 'exportInquiries'])
            ->name('inquiry.export');

        // API endpoint for inquiry details
        Route::get('/api/inquiry/{id}', [InquiryAssignmentController::class, 'getInquiryDetails'])
            ->name('inquiry.assignment.details');

        // API endpoint for assignment notes data (for Admin Dashboard integration)
        Route::get('/assignment-notes-data', [InquiryAssignmentController::class, 'getAssignmentNotesData'])
            ->name('inquiry.assignment.notes.data');

        // Notify MCMC endpoint module 4
        Route::post('/notify-mcmc', [AgencyReviewAndNotificationController::class, 'notifyMCMC'])
            ->name('agency.notify.mcmc');

        // Alternative route for home page
        Route::get('/home', function () {
            return redirect()->route('agency.dashboard');
        })->name('agency.home');
    });
});

// ================= MCMC ADMIN ================= //

Route::get('/mcmc/register-agency', [AdminController::class, 'showRegisterAgencyForm'])->name('mcmc.register_agency');
Route::post('/mcmc/register-agency', [AdminController::class, 'handleRegisterAgency'])->name('admin.handleRegisterAgency');

// ================= NOTIFICATION ROUTES ================= //
// These routes are now under the module4 prefix

// ================= DEFAULT ROUTE ================= //

Route::get('/', fn() => redirect()->route('publicuser.login'));

// ================= OVERWRITE LOGIN ROUTE ================= //

Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');

// ================= PASSWORD UPDATE ROUTE ================= //

Route::post('/user/password', [UserController::class, 'updatePassword'])->name('password.update');

// Login routes
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('publicuser.login.submit');

// Admin login routes
Route::get('/admin/login', [AdminController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'handleAdminLogin'])->name('admin.login.submit');

// Protected dashboard routes
Route::middleware('auth')->group(function () {

    // Public user dashboard (gmail and others)
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('publicuser.dashboard');

    // Admin dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Admin inquiry routes
    Route::get('/admin/inquiries', [AdminController::class, 'showInquiriesPage'])->name('admin.inquiries');
    Route::get('/admin/all-inquiries', [AdminController::class, 'showAllInquiries'])->name('admin.all.inquiries');
    Route::get('/admin/inquiry/{id}', [InquiryController::class, 'show'])->name('admin.inquiry.show');

    // Admin inquiry action routes
    Route::post('/admin/inquiries/{inquiryId}/discard-non-serious', [AdminController::class, 'discardAsNonSerious'])->name('admin.inquiry.discard');
    Route::get('/admin/inquiries/{inquiryId}/details', [AdminController::class, 'showInquiryDetails'])->name('admin.inquiry.details');

    // Admin inquiry reports routes
    Route::get('/admin/inquiry-reports', [AdminController::class, 'showInquiryReports'])->name('admin.inquiry.reports');
    Route::get('/admin/inquiry-reports/export-pdf', [AdminController::class, 'exportInquiryReportToPDF'])->name('admin.inquiry.reports.pdf');
    Route::get('/admin/inquiry-reports/export-excel', [AdminController::class, 'exportInquiryReportToExcel'])->name('admin.inquiry.reports.excel');

    // Admin assignment notes data endpoint
    Route::get('/admin/assignment-notes-data', [InquiryAssignmentController::class, 'getAssignmentNotesData'])->name('admin.assignment.notes.data');
    Route::post('/admin/assignment-notes/save', [InquiryAssignmentController::class, 'saveAssignmentNote'])->name('admin.assignment.notes.save');

    // Admin assignment notes page
    Route::get('/admin/assignment-notes', [InquiryAssignmentController::class, 'showAdminAssignmentNotes'])->name('admin.assignment.notes');
    Route::post('/admin/assignment-notes/add', [InquiryAssignmentController::class, 'addAssignmentNotes'])->name('admin.assignment.notes.add');

    // Admin review and assign inquiries routes
    Route::get('/admin/review-assign', [InquiryAssignmentController::class, 'showReviewAssignInquiries'])->name('admin.review.assign');
    Route::get('/admin/assign-inquiry/{inquiryId}', [InquiryAssignmentController::class, 'showAssignmentForm'])->name('admin.assign.form');
    Route::post('/admin/assign-inquiry', [InquiryAssignmentController::class, 'assignInquiry'])->name('admin.assign.inquiry');

    // Note: Agency dashboard is already defined in the agency routes group
});

// Recovery route (no login required)
Route::get('/recover', function () {
    return view('ManageUser.publicuser.recover_password');
})->name('publicuser.recover');

// Note: Agency password update routes are already defined in the agency routes group

// ================= ADMIN SETTINGS ROUTE ================= //
Route::get('/admin/home', [AdminController::class, 'showAdminHomePage'])->name('admin.home');
Route::get('/admin/settings', [AdminController::class, 'showSettings'])->name('admin.settings');
Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
Route::get('/admin/recover-password', function () {
    return view('ManageUser.MCMC_Admin.recover_password');
})->name('admin.recover_password')->middleware('auth');
Route::post('/admin/update-password', [UserController::class, 'updatePassword'])->name('admin.update_password')->middleware('auth');

// ================= ADMIN REPORTS ROUTE ================= //
Route::get('/admin/reports', [AdminController::class, 'showReportPage'])->name('admin.reports');
Route::get('/admin/reports/export', [AdminController::class, 'exportReportToPDF'])->name('admin.reports.export');
Route::post('/admin/generate-report', [AdminController::class, 'generateReport'])->name('admin.generateReport');
Route::get('/admin/export-pdf', [AdminController::class, 'exportToPDF'])->name('admin.exportToPDF');
Route::get('/admin/export-excel', [AdminController::class, 'exportToExcel'])->name('admin.exportToExcel');

// ================= ADMIN LOGOUT ROUTE ================= //
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Make sure we're using the correct logout route throughout the app
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// ================= MODULE 4 ROUTES ================= //
Route::prefix('module4')->middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Module4\InquiryController::class, 'index'])->name('module4.dashboard');
    Route::get('/inquiry/{inquiry_id}', [App\Http\Controllers\Module4\InquiryController::class, 'show'])->name('module4.inquiry.show');
    Route::post('/inquiry/{inquiry_id}/status', [App\Http\Controllers\Module4\InquiryController::class, 'updateStatus'])->name('module4.inquiry.update-status');

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\Module4\NotificationController::class, 'index'])->name('module4.notifications');

    Route::get('/notifications/unread-count', [App\Http\Controllers\Module4\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // Report routes
    Route::get('/reports', [App\Http\Controllers\Module4\ReportController::class, 'index'])->name('module4.reports');
    Route::post('/reports/generate', [App\Http\Controllers\Module4\ReportController::class, 'generateReport'])->name('module4.reports.generate');

    // MCMC Admin Dashboard
    Route::get('/mcmc/dashboard', [App\Http\Controllers\Module4\InquiryController::class, 'mcmcDashboard'])->name('module4.mcmc.dashboard');

    // Example controller: Module4\ReportController
    Route::get('/mcmc/report', [\App\Http\Controllers\Module4\ReportController::class, 'index'])->name('module4.mcmc.report');

    Route::get('/mcmc/report/pdf', [\App\Http\Controllers\Module4\ReportController::class, 'exportPdf'])->name('module4.mcmc.report.pdf');
    Route::get('/mcmc/report/excel', [\App\Http\Controllers\Module4\ReportController::class, 'exportExcel'])->name('module4.mcmc.report.excel');
});

// Add notification mark-as-read route
Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

// Add a POST route at the root level for marking all notifications as read
Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::get('/admin/notifications', [App\Http\Controllers\Module4\NotificationController::class, 'index'])->name('admin.notifications');

// ================= MODULE 4 AGENCY DASHBOARD ================= //
Route::get('/module_4/agency/dashboard', [AgencyDashboardController::class, 'index'])->name('module4.agency.dashboard');
