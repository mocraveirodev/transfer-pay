<?php

namespace Src\Infrastructure\Clients;

use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Src\Domain\Interfaces\AuthorizationClientInterface;
use Src\Domain\Exception\AuthorizationException;

class AuthorizationClient implements AuthorizationClientInterface
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    /**
     * @throws AuthorizationException
     */
    public function handle(): bool
    {
        try {
            $response = Http::get(config("authorization.url"));

            return match ($response->json()['message']) {
                'Autorizado' => true,
                default      => false,
            };
        } catch (BadResponseException $exception) {
            throw AuthorizationException::serviceUnavailable($exception);
        }

    }
}
