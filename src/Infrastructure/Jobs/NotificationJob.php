<?php

namespace Src\Infrastructure\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Src\Domain\Exception\NotificationException;

class NotificationJob implements ShouldQueue, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /**
     * @throws NotificationException
     */
    public function handle(): void
    {
        try {
            Http::get(config("notification.url"));
        } catch (BadResponseException $exception) {
            throw NotificationException::serviceUnavailable($exception);
        }
    }
}
