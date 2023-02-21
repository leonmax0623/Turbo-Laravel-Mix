<?php

use App\Http\Controllers\Api\Atol\AtolController;
use App\Http\Controllers\Api\Documents\DocumentController;
use App\Http\Controllers\Api\Documents\DocumentTemplateController;
use App\Http\Controllers\Api\MapAnswers\MapAnswerController;
use App\Http\Controllers\Api\Maps\MapController;
use App\Http\Controllers\Api\Orders\OrderController;
use App\Http\Controllers\Api\Pipelines\UserController as PipelineUserController;
use App\Http\Controllers\Api\Products\ProductRequestController;
use App\Http\Controllers\Api\Works\WorkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Profile\ProfileAvatarController;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\Api\Roles\RoleController;
use App\Http\Controllers\Api\Departments\DepartmentController;
use App\Http\Controllers\Api\Users\UserPasswordController;
use App\Http\Controllers\Api\Profile\ProfilePasswordController;
use App\Http\Controllers\Api\Users\UserAvatarController;
use App\Http\Controllers\Api\Clients\ClientController;
use App\Http\Controllers\Api\Cars\CarMarkController;
use App\Http\Controllers\Api\Cars\CarModelController;
use App\Http\Controllers\Api\Cars\CarController;
use App\Http\Controllers\Api\Roles\PermissionController;
use App\Http\Controllers\Api\Cars\EngineVolumeController;
use App\Http\Controllers\Api\Cars\FuelController;
use App\Http\Controllers\Api\Finances\FinanceGroupController;
use App\Http\Controllers\Api\Finances\FinanceController;
use App\Http\Controllers\Api\Products\StorageController;
use App\Http\Controllers\Api\Products\ProducerController;
use App\Http\Controllers\Api\Products\ProductController;
use App\Http\Controllers\Api\Comments\CommentController;
use App\Http\Controllers\Api\Pipelines\PipelineController;
use App\Http\Controllers\Api\Pipelines\StageController;
use App\Http\Controllers\Api\Tasks\TaskController;
use App\Http\Controllers\Api\Tasks\TaskStatusController;
use App\Http\Controllers\Api\Tasks\CheckboxController;
use App\Http\Controllers\Api\TempFiles\TempFileController;
use App\Http\Controllers\Api\Tasks\TaskPipelineController;
use App\Http\Controllers\Api\AppealReasons\AppealReasonController;
use App\Http\Controllers\Api\Orders\OrderStageController;
use App\Http\Controllers\Api\Processes\ProcessCategoryController;
use App\Http\Controllers\Api\Processes\ProcessTaskController;
use App\Http\Controllers\Api\Processes\ProcessCheckboxController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('user-auth', [AuthController::class, 'userAuth']);
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
});

Route::group(
    ['middleware' => ['auth:sanctum', 'active']],
    function () {
        Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
            Route::get('user', 'getUser');
            Route::get('user/permissions', 'getUserPermissions');
            Route::post('logout', 'logout');
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::put('/', [ProfileController::class, 'update']);
            Route::post('avatar', [ProfileAvatarController::class, 'update']);
            Route::put('password', [ProfilePasswordController::class, 'update']);
        });

        Route::group(['prefix' => 'products-requests', 'controller' => ProductRequestController::class], function () {
            Route::get('get-statuses', 'getStatuses');
            Route::post('{productRequest}/status', 'updateStatus');
        });

        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('roles', RoleController::class);
        Route::get('permissions', [PermissionController::class, 'index']);

        Route::apiResource('users', UserController::class);
        Route::post('users/{user}/avatar', [UserAvatarController::class, 'update']);
        Route::put('users/{user}/password', [UserPasswordController::class, 'update']);

        Route::group(['prefix' => 'documents'], function () {
            Route::get('{order}/generate', [DocumentController::class, 'generate']);
            Route::get('{document}/generate/url', [DocumentController::class, 'generateUrl']);
        });

        Route::group(['prefix' => 'maps', 'controller' => MapController::class], function () {
            Route::get('get-types', 'getTypes');
        });

        Route::get('finances/{id}/payment', [FinanceController::class, 'payment']);
        Route::get('finances/{id}/payment-status', [FinanceController::class, 'paymentStatus']);
        Route::get('finances/types', [FinanceController::class, 'getTypes']);
        Route::get('finances/summaries', [FinanceController::class, 'summaries']);
        Route::get('atol/open-shift', [AtolController::class, 'openShift']);
        Route::get('atol/close-shift', [AtolController::class, 'closeShift']);
        Route::get('tasks/history', [TaskController::class, 'history']);

        Route::apiResource('car-marks', CarMarkController::class);
        Route::apiResource('car-models', CarModelController::class);
        Route::apiResource('engine-volumes', EngineVolumeController::class);
        Route::apiResource('fuels', FuelController::class);
        Route::apiResource('cars', CarController::class);
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('finance-groups', FinanceGroupController::class);
        Route::apiResource('finances', FinanceController::class);
        Route::apiResource('storages', StorageController::class)->except('index');
        Route::apiResource('producers', ProducerController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('products-requests', ProductRequestController::class);

        Route::post('pipelines/orders', [PipelineController::class, 'updateOrders']);
        Route::apiResource('pipelines', PipelineController::class);

        Route::post('stages/orders', [StageController::class, 'updateOrders']);
        Route::apiResource('stages', StageController::class);

        Route::delete('pipeline-users', [PipelineUserController::class, 'destroy']);
        Route::apiResource('pipeline-users', PipelineUserController::class)
            ->only(['index', 'store']);

        Route::apiResource('tasks', TaskController::class);
        Route::apiResource('process-tasks', ProcessTaskController::class);
        Route::apiResource('maps', MapController::class);
        Route::apiResource('map-answers', MapAnswerController::class);
        Route::apiResource('appeal-reasons', AppealReasonController::class);
        Route::apiResource('process-categories', ProcessCategoryController::class);
        Route::apiResource('orders', OrderController::class);
        Route::apiResource('works', WorkController::class);
        Route::apiResource('document-templates', DocumentTemplateController::class);
        Route::apiResource('documents', DocumentController::class);

        Route::post('order-stages/orders', [OrderStageController::class, 'updateOrders']);
        Route::apiResource('order-stages', OrderStageController::class);

        Route::post('orders/{order}/stage', [OrderController::class, 'updateStage']);

        Route::group(['prefix' => 'storages', 'controller' => StorageController::class], function () {
            Route::get('department/{department}', 'index');
        });

        Route::group(['prefix' => 'process-tasks/checkboxes', 'controller' => ProcessCheckboxController::class], function () {
            Route::post('{process_checkbox}/status', 'changeIsCheckedStatus');
            Route::post('{process_checkbox}', 'update');
            Route::delete('{process_checkbox}', 'destroy');
        });

        Route::post('products/{product}/photo', [ProductController::class, 'updatePhoto']);

        Route::post('temp/files', [TempFileController::class, 'store']);
        Route::post('temp/files/delete', [TempFileController::class, 'destroy']);

        Route::group(['prefix' => 'tasks'], function () {
            Route::post('{task}/pipelines/{pipeline}/stage', [TaskPipelineController::class, 'updateStage']);
            Route::post('assignUser', [TaskController::class, 'assignUser']);

            Route::group(['controller' => TaskStatusController::class], function () {
                Route::post('{task}/to/process', 'toProcess');
                Route::post('{task}/to/pause', 'toPause');
                Route::post('{task}/to/done', 'toDone');
                Route::post('{task}/status', 'updateStatus');
            });

            Route::group(['prefix' => 'checkboxes', 'controller' => CheckboxController::class], function () {
                Route::post('{checkbox}/status', 'changeIsCheckedStatus');
                Route::post('{checkbox}', 'update');
                Route::delete('{checkbox}', 'destroy');
            });
        });

        Route::group(['prefix' => 'comments', 'controller' => CommentController::class], function () {
            Route::get('{model}/{id}', 'index');
            Route::post('{model}/{id}', 'store');
            Route::get('get-types', 'getTypes');
        });

    }
);
