<?php

namespace App\Providers;

use App\Models\RoleConst;
use App\Models\Task;
use App\Models\User;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Task::class => TaskPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(
            function ($user, $ability) {
                return $user->is_admin ? true : null;
            }
        );

        Gate::define(
            'users-crud',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_USERS_CRUD);
            }
        );

        Gate::define(
            'users-read',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_USERS_CRUD) || $user->hasPermissionTo(RoleConst::PERMISSION_USERS_READ);
            }
        );

        Gate::define(
            'clients-crud',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_CLIENTS_CRUD);
            }
        );

        Gate::define(
            'clients-read',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_CLIENTS_READ);
            }
        );

        Gate::define(
            'pipelines-crud',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_PIPELINES_CRUD);
            }
        );

        Gate::define(
            'storages-crud',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_STORAGES_CRUD);
            }
        );

        Gate::define(
            'works-crud',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_WORKS_CRUD);
            }
        );

        Gate::define(
            'works-read',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_WORKS_READ);
            }
        );

        Gate::define(
            'orders-crud',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_ORDERS_CRUD);
            }
        );

        Gate::define(
            'orders-read',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_ORDERS_READ);
            }
        );

        Gate::define(
            'documents-read',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_ORDERS_READ);
            }
        );

        Gate::define(
            'documents-crud',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_DOCUMENTS_CRUD);
            }
        );

        Gate::define(
            'document-templates-read',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_ORDERS_READ);
            }
        );

        $this->registerCommentGates();

        $this->registerTaskGates();
    }

    private function registerCommentGates(): void
    {
        Gate::define(
            'read-task-comments',
            function (User $user, $model) {
                return $user->can('view', $model);
            }
        );

        Gate::define(
            'read-order-comments',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_ORDERS_READ) ||
                    $user->hasPermissionTo(RoleConst::PERMISSION_ORDERS_CRUD);
            }
        );

        Gate::define(
            'read-comments',
            function (User $user, string $modelAlias, $model) {
                if ($modelAlias === 'task') {
                    return Gate::check('read-task-comments', [$model]);
                }
                if ($modelAlias === 'order') {
                    return Gate::check('read-order-comments');
                }
                return false;
            }
        );

        Gate::define(
            'store-comments',
            function (User $user, string $modelAlias, $model) {
                if ($modelAlias === 'task') {
                    return Gate::check('read-task-comments', [$model]);
                }
                if ($modelAlias === 'order') {
                    return Gate::check('read-order-comments');
                }
                return false;
            }
        );
    }

    private function registerTaskGates(): void
    {

        Gate::define(
            'read-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_READ);
            }
        );

        Gate::define(
            'read-own-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_READ_OWN);
            }
        );

        Gate::define(
            'read-department-tasks',
            function (User $user, ?int $departmentId = null) {
                return ($user->hasPermissionTo(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT) &&
                        (is_null($departmentId) || $user->department_id === $departmentId));
            }
        );

        Gate::define(
            'read-department-own-tasks',
            function (User $user, ?int $departmentId = null) {
                return ($user->hasPermissionTo(RoleConst::PERMISSION_TASKS_READ_DEPARTMENT_AND_OWN) &&
                    (is_null($departmentId) || $user->department_id === $departmentId));
            }
        );

        Gate::define(
            'create-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_CREATE);
            }
        );

        Gate::define(
            'update-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_UPDATE);
            }
        );

        Gate::define(
            'update-own-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_UPDATE_OWN);
            }
        );

        Gate::define(
            'update-department-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_UPDATE_DEPARTMENT);
            }
        );

        Gate::define(
            'update-stage-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_STAGE);
            }
        );

        Gate::define(
            'update-own-stage-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_STAGE_OWN);
            }
        );

        Gate::define(
            'update-department-stage-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_STAGE_DEPARTMENT);
            }
        );

        Gate::define(
            'delete-tasks',
            function (User $user) {
                return $user->hasPermissionTo(RoleConst::PERMISSION_TASKS_DELETE);
            }
        );
    }
}
