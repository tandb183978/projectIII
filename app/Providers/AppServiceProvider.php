<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\UserRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Role\RoleRepositoryInterface::class,
            \App\Repositories\Role\RoleRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Employee\EmployeeRepositoryInterface::class,
            \App\Repositories\Employee\EmployeeRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Attendance\AttendanceRepositoryInterface::class,
            \App\Repositories\Attendance\AttendanceRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Leave\LeaveRepositoryInterface::class,
            \App\Repositories\Leave\LeaveRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Salary\SalaryRepositoryInterface::class,
            \App\Repositories\Salary\SalaryRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Mail\MailRepositoryInterface::class,
            \App\Repositories\Mail\MailRepository::class
        );




    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
