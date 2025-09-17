<?php


use App\Http\Controllers\ClassController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScoreTypeController;
use App\Http\Controllers\SemesterController;


use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TimetableController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::middleware(['auth:staff', 'subscribed', 'check.account.suspension'])->group(callback: function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::resources([
        'staff' => StaffController::class,
        'students' => StudentController::class,
        'parents' => ParentController::class,
        'semesters' => SemesterController::class,
        'subjects' => SubjectController::class,
        'timetables' => TimetableController::class,
        'classes' => ClassController::class,
        'score-types' => ScoreTypeController::class,
        'fees' => FeeController::class,
        'roles' =>  RoleController::class
    ]);

    Route::prefix('student')->as('student.')->group(function () {
        Route::delete('bulk-destroy', [StudentController::class, 'bulkDestroy'])->name('bulk-destroy');
    });

    Route::prefix('staffs')->as('staffs.')->group(function () {
        Route::delete('bulk-destroy', [StaffController::class, 'bulkDestroy'])->name('bulk-destroy');
    });

    Route::prefix('role')->as('role.')->group(function () {
        Route::post('add-permissions', [RoleController::class, 'addPermissions'])->name('permissions');
        Route::delete('bulk-destroy', [RoleController::class, 'bulkDestroy'])->name('bulk-destroy');
    });


//    Route::prefix('classes')->as('classes.')->group(function () {

//        Route::get('/assign-subjects/',  ClassAssignSubject::class)->name('assign.subject')->middleware('permission:assign.Subject');
//        Route::get('/assign-staff/',  ClassAssignStaff::class)->name('assign.staff')->middleware('permission:assign.Staff');
//        Route::get('/class-terminal/{term_id}/{class_id}', [PDFController::class, 'classTerminal'])->name('class.terminal.report')
//            ->middleware('permission:view.TerminalReport');
//    });
//

//
//    // BILLS AND FINANCE
//    Route::prefix('finance')->as('finance.')->group(function () {

//        Route::get('/bill-student', BillStudent::class)->name('bill.student')->middleware('permission:bill.Student');
//        Route::get('/pay-bill',  FeePayment::class)->name('pay.bill')->middleware('permission:create.Payment');
//        Route::get('/payment-history',  PaymentHistory::class)->name('payment.history')->middleware('permission:view.Payment');
//        Route::get('/payment-receipt/{uuid}', [PDFController::class, 'paymentReceipt'])->name('payment.receipt')->middleware('permission:view.Payment');
//        Route::get('/debts',  FeeDebt::class)->name('debt')->middleware('permission:view.Debt');
//        Route::get('/debt-printout', [PDFController::class, 'debtResult'])->name('debt.printout')->middleware('permission:view.Debt');
//        Route::get('/payment-overflows',   SurplusPaymentIndex::class)->name('payment.overflows')->middleware('permission:view.Debt');
//        Route::get('/report',   ReportIndex::class)->name('report')->middleware('permission:financial.Report');
//        Route::get('/print/{startDate}/{endDate}',   [PDFController::class, 'financialReport'])->name('report.print')->middleware('permission:financial.Report');
//
//    });
});
require __DIR__ . '/auth.php';
