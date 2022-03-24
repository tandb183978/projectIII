<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EmployeeManageController;
use App\Http\Controllers\Admin\LeaveManagementController;
use App\Http\Controllers\Admin\SalaryManagementController;

use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\Employee\SalaryController;

use App\Http\Controllers\MailController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('homepage.index');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function(){

    /**
     * Mail route (both admin and employee
     */
    Route::prefix('mail')->name('mail.')->group(function(){

        Route::post('/compose', [MailController::class, 'compose'])->name('compose');

        Route::delete('/delete/{mail_id}', [MailController::class, 'delete_mail'])->name('delete_mail');
        Route::delete('/delete_root_mail/{root_id}', [MailController::class, 'option_for_selected_mails'])->name('delete_root_mail');
        Route::delete('/delete_selected_mails', [MailController::class, 'option_for_selected_mails'])->name('delete_selected_mails');

        Route::post('/mark_root_mail_important/{root_id}', [MailController::class, 'option_for_selected_mails'])->name('mark_root_mail_important');
        Route::post('/mark_selected_mails_important', [MailController::class, 'option_for_selected_mails'])->name('mark_selected_mails_important');

        Route::post('/mark_mail_favorite/{mail_id}', [MailController::class, 'mark_favorite'])->name('mark_mail_favorite');
        Route::post('/mark_root_mail_favorite/{root_id}', [MailController::class, 'option_for_selected_mails'])->name('mark_root_mail_favorite');
        Route::post('/mark_selected_mails_favorite', [MailController::class, 'option_for_selected_mails'])->name('mark_selected_mails_favorite');

        Route::post('/store_reply', [MailController::class, 'store_reply'])->name('store_reply');

        Route::prefix('inbox')->name('inbox.')->group(function(){
            Route::get('/', [MailController::class, 'index'])->name('index');
            Route::get('/detail/{mail_id}', [MailController::class, 'detail'])->name('detail');
        });

        Route::prefix('outbox')->name('outbox.')->group(function(){
            Route::get('/', [MailController::class, 'index'])->name('index');
            Route::get('/detail/{mail_id}', [MailController::class, 'detail'])->name('detail');
        });

        Route::prefix('favorite')->name('favorite.')->group(function(){
            Route::get('/', [MailController::class, 'index'])->name('index');
            Route::get('/detail/{mail_id}', [MailController::class, 'detail'])->name('detail');
        });

        Route::prefix('important')->name('important.')->group(function(){
            Route::get('/', [MailController::class, 'index'])->name('index');
            Route::get('/detail/{mail_id}', [MailController::class, 'detail'])->name('detail');
        });

        Route::prefix('trash_bin')->name('trash_bin.')->group(function(){
            Route::get('/', [MailController::class, 'index'])->name('index');
            Route::get('/detail/{mail_id}', [MailController::class, 'detail'])->name('detail');
        });

    });

    /**
     * Admin route
     */
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('/', [AdminController::class, 'index'])->name('index');

        Route::prefix('employee')->name('employee.')->group(function(){
            Route::get('list-employee', [EmployeeManageController::class, 'list_employee'])->name('list_employee');
            Route::get('add-employee', [EmployeeManageController::class, 'add_employee'])->name('add_employee');
            Route::post('add-employee', [EmployeeManageController::class, 'store_employee'])->name('store_employee');
            Route::get('edit/{employee_id}', [EmployeeManageController::class,'edit_view'])->name('edit_view');
            Route::post('edit/{employee_id}', [EmployeeManageController::class,'edit'])->name('edit');
            Route::delete('delete/{employee_id}', [EmployeeManageController::class,'delete'])->name('delete');
        });

        Route::prefix('salary')->name('salary.')->group(function(){
            Route::get('list-attendance-employees', [SalaryManagementController::class, 'list_attendance_employees'])->name('employee_attendance');
            Route::post('list-attendance-employees', [SalaryManagementController::class, 'list_attendance_range'])->name('employee_attendance.get_range');

            Route::get('list-salary-employees', [SalaryManagementController::class, 'list_salary_employees'])->name('employee_salary');
            Route::post('list-salary-employees', [SalaryManagementController::class, 'list_salary_employees'])->name('employee_salary_select');
            Route::get('calculate_salary/{salary_id}/{title}', [SalaryManagementController::class, 'calculate_individual_salary'])->name('calculate_salary');
            Route::get('calculate_salary/{title}', [SalaryManagementController::class, 'calculate_all_salary'])->name('calculate_all_salary');

            Route::get('update_fees/{salary_id}/{title}', [SalaryManagementController::class, 'update_fees'])->name('update_fee');
            Route::post('update_fees/{salary_id}/{title}', [SalaryManagementController::class, 'store_fees'])->name('store_fee');

        });

        Route::prefix('leave')->name('leave.')->group(function (){
            Route::get('list-leaves-employees', [LeaveManagementController::class, 'list_leaves'])->name('list_leaves');
            Route::post('list-leaves-employees', [LeaveManagementController::class, 'list_leaves'])->name('list_leaves_select');
            Route::get('detail/{leave_id}', [LeaveManagementController::class, 'leave_detail'])->name('leave_detail');
            Route::post('detail/{leave_id}', [LeaveManagementController::class, 'leave_process'])->name('leave_process');
        });
    });

    /**
     * Employee route
     */
    Route::prefix('employee')->name('employee.')->group(function(){

        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/information', [EmployeeController::class, 'change_information'])->name('information');

        Route::prefix('attendance')->name('attendance.')->group(function(){
            Route::get('create-attendance', [AttendanceController::class,'create'])->name('create');
            Route::post('create-attendance/{attendance_id}', [AttendanceController::class,'store_entry'])->name('store.entry');
            Route::put('create-attendance/{attendance_id}', [AttendanceController::class, 'store_exit'])->name('store.exit');

            Route::get('list-attendance', [AttendanceController::class,'list'])->name('list');
        });

        Route::prefix('salary')->name('salary.')->group(function(){
            Route::get('list-salary', [SalaryController::class, 'list_salaries'])->name('list');
            Route::post('list-salary', [SalaryController::class, 'list_salaries_range'])->name('get_range');
        });

        Route::prefix('leave')->name('leave.')->group(function(){
            Route::get('create-form', [LeaveController::class, 'create_form'])->name('create_form');
            Route::post('create-form', [LeaveController::class, 'store_form'])->name('store_form');

            Route::get('list-leaves', [LeaveController::class, 'list_leaves'])->name('list_leaves');
            Route::post('list-leaves', [LeaveController::class, 'list_leaves'])->name('list_leaves_select');
        });
    });
});



