<?php

namespace App\Providers;

use App\Models\Checkbox;
use App\Models\Order;
use App\Models\Pipeline;
use App\Models\PipelineTask;
use App\Models\Stage;
use App\Models\Task;
use App\Observers\BaseObserver;
use App\Observers\OrderObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Order::observe(OrderObserver::class);
        Task::observe(BaseObserver::class);
        Checkbox::observe(BaseObserver::class);
    }
}
