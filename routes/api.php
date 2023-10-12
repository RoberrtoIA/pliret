<?php

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Execution\ExecutionEnrollTraineeController;
use App\Http\Controllers\V1\ExecutionController;
use App\Http\Controllers\V1\ProgramController;
use App\Http\Controllers\V1\User\CreateEmployeeAccountController;
use App\Http\Controllers\V1\User\CreateTraineeAccountController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('api.v1.')->prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::post('users/create-employee-account', CreateEmployeeAccountController::class)
            ->name('users.createEmployeeAccount')
            ->middleware(['ability:manage_user_accounts']);

        Route::post('users/create-trainee-account', CreateTraineeAccountController::class)
            ->name('users.createTraineeAccount')
            ->middleware(['ability:manage_user_accounts']);

        Route::resource('executions', ExecutionController::class)
            ->only(['index', 'show'])
            ->middleware([
                'ability:manage_executions'
                    . ',see_program_content_details'
                    . ',see_program_content'
            ]);

        Route::resource('executions', ExecutionController::class)
            ->only(['store', 'update', 'destroy'])
            ->middleware(['ability:manage_executions']);

        Route::resource('programs', ProgramController::class)
            ->only(['store', 'update', 'destroy'])
            ->middleware(['ability:manage_programs']);

        Route::resource('programs', ProgramController::class)
            ->only(['index', 'show'])
            ->middleware([
                'ability:manage_programs'
                    . ',add_program_content'
            ]);

        Route::resource('users', UserController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->middleware(['ability:manage_user_accounts']);

        Route::get(
            'executions/{execution}/enroll-trainee/{trainee}',
            ExecutionEnrollTraineeController::class
        )
            ->name('executions.enroll-trainee')
            ->middleware(['ability:manage_executions']);
    });
});
