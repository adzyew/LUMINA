<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Queue::failing(function (JobFailed $event): void {
            $payload = $event->job->payload();
            $displayName = data_get($payload, 'displayName') ?: $event->job->resolveName();

            Log::channel('queue')->error('Queue job failed', [
                'connection' => $event->connectionName,
                'queue' => $event->job->getQueue(),
                'job_id' => $event->job->getJobId(),
                'job_name' => $displayName,
                'exception' => $event->exception->getMessage(),
            ]);
        });
    }
}
