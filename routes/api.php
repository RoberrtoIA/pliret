<?php

use App\Http\Controllers\V1\ExportTraineeProgressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\ModuleController;
use App\Http\Controllers\V1\GradingController;
use App\Http\Controllers\V1\ProgramController;
use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\ExecutionController;
use App\Http\Controllers\V1\EvaluationCriteriaController;
use App\Http\Controllers\V1\Assignment\SaveQuestionController;
use App\Http\Controllers\V1\Assignment\HomeworkStartController;
use App\Http\Controllers\V1\Assignment\HomeworkFinishController;
use App\Http\Controllers\V1\Assignment\InterviewStartController;
use App\Http\Controllers\V1\Execution\FinishExecutionController;
use App\Http\Controllers\V1\User\CreateTraineeAccountController;
use App\Http\Controllers\V1\Assignment\InterviewFinishController;
use App\Http\Controllers\V1\Execution\AssignUserModuleController;
use App\Http\Controllers\V1\User\CreateEmployeeAccountController;
use App\Http\Controllers\V1\Assignment\HomeworkSolutionController;
use App\Http\Controllers\V1\Program\ProgramAssignDeveloperController;
use App\Http\Controllers\V1\Execution\ExecutionAssignTrainerController;
use App\Http\Controllers\V1\Execution\ExecutionEnrollTraineeController;
use App\Http\Controllers\V1\Assignment\SaveEvaluationCriteriaController;
use App\Http\Controllers\V1\User\QuestionController;
use App\Http\Controllers\V1\User\TopicController;

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

    Route::get('social/{driver}/login', [AuthController::class, 'socialLogin'])
        ->name('social.login');

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::get('report/{id}', ExportTraineeProgressController::class)
        ->middleware(['ability:see_program_content_details'])
        ->name('report');

        Route::resource('programs', ProgramController::class)
            ->only(['store', 'update', 'destroy'])
            ->middleware(['ability:manage_programs']);

        Route::resource('programs', ProgramController::class)
            ->only(['index', 'show'])
            ->middleware([
                'ability:manage_programs'
                    . ',add_program_content'
            ]);

        Route::resource('modules', ModuleController::class)
            ->only(['store', 'update', 'destroy'])
            ->middleware(['ability:add_program_content']);

        Route::resource('modules', ModuleController::class)
            ->only(['index', 'show'])
            ->middleware(['ability:add_program_content,see_program_content_details']);

        Route::resource('topics', TopicController::class)
            ->only(['store', 'update', 'destroy'])
            ->middleware(['ability:add_program_content']);

        Route::resource('topics', TopicController::class)
            ->only(['index', 'show'])
            ->middleware(['ability:add_program_content']);

        Route::resource('questions', QuestionController::class)
            ->only(['store', 'update', 'destroy'])
            ->middleware(['ability:add_program_content']);

        Route::resource('questions', QuestionController::class)
            ->only(['index', 'show'])
            ->middleware(['ability:add_program_content']);

        Route::resource('evaluations', EvaluationCriteriaController::class)
            ->only(['store', 'update', 'destroy'])
            ->middleware(['ability:add_program_content']);

        Route::resource('evaluations', EvaluationCriteriaController::class)
            ->only(['index', 'show'])
            ->middleware(['ability:add_program_content']);

        Route::put('assignments/save-evaluation-criteria', SaveEvaluationCriteriaController::class)
            ->name('assignments.save-evaluation-criteria')
            ->middleware(['ability:take_homework']);

        // Route::put('assignments/save-question', SaveQuestionController::class)
        //     ->name('assignments.save-question')
        //     ->middleware(['ability:take_quiz']);

        // Route::resource('gradings', GradingController::class)
        //     ->only(['destroy'])
        //     ->middleware(['ability:manage_executions']);

        // Route::resource('gradings', GradingController::class)
        //     ->only(['index', 'show'])
        //     ->middleware([
        //         'ability:,see_program_content_details'
        //             . ',see_program_content'
        //     ]);

        Route::post('users/create-trainee-account', CreateTraineeAccountController::class)
            ->name('users.createTraineeAccount')
            ->middleware(['ability:manage_user_accounts']);

        Route::post('users/create-employee-account', CreateEmployeeAccountController::class)
            ->name('users.createEmployeeAccount')
            ->middleware(['ability:manage_user_accounts']);


        Route::resource('users', UserController::class)
            ->only(['index', 'show', 'update', 'destroy'])
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

        // Route::get('executions/finish/{execution}', FinishExecutionController::class)
        //     ->name('executions.finish')
        //     ->middleware(['ability:manage_executions']);

        Route::get(
            'executions/{execution}/assign-trainer/{trainer}',
            ExecutionAssignTrainerController::class
        )
            ->name('executions.assign-trainer')
            ->middleware(['ability:manage_executions']);

        Route::get(
            'executions/{execution}/enroll-trainee/{trainee}',
            ExecutionEnrollTraineeController::class
        )
            ->name('executions.enroll-trainee')
            ->middleware(['ability:manage_executions']);

        Route::post(
            'executions/assign-trainee-module',
            AssignUserModuleController::class
        )
            ->name('executions.assign-trainee-module')
            ->middleware(['ability:manage_executions']);

        // Route::post(
        //     'assignments/{assignment}/interview-start',
        //     InterviewStartController::class
        // )
        //     ->name('assignments.interview-start')
        //     ->middleware(['ability:take_quiz']);

        // Route::post(
        //     'assignments/{assignment}/interview-finish',
        //     InterviewFinishController::class
        // )
        //     ->name('assignments.interview-finish')
        //     ->middleware(['ability:take_quiz']);

        // Route::post(
        //     'assignments/{assignment}/homework-start',
        //     HomeworkStartController::class
        // )
        //     ->name('assignments.homework-start')
        //     ->middleware(['ability:take_homework']);

        // Route::post(
        //     'assignments/{assignment}/homework-finish',
        //     HomeworkFinishController::class
        // )
        //     ->name('assignments.homework-finish')
        //     ->middleware(['ability:take_homework']);

        // Route::post(
        //     'assignments/{assignment}/homework-solution',
        //     HomeworkSolutionController::class
        // )
        //     ->name('assignments.homework-solution')
        //     ->middleware(['ability:take_homework']);

        Route::get(
            'programs/{program}/assign-developer/{developer}',
            ProgramAssignDeveloperController::class
        )
            ->name('programs.assign-developer')
            ->middleware(['ability:manage_programs']);
    });
});
