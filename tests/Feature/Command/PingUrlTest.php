<?php

namespace Tests\Feature\Command;

use App\Console\Commands\PingUrlCommand;
use App\Mail\AppDownMail;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PingUrlTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPingOk(): void
    {
        Mail::fake();

        $this->mockHttp(PingUrlCommand::class, [
            new Response(200),
        ]);

        $this->artisan('ping:url')
            ->expectsOutput(__('Ping is OK.'))
            ->assertExitCode(0);

        Mail::assertNothingSent();
    }

    public function testPingNotOk(): void
    {
        Mail::fake();

        $this->mockHttp(PingUrlCommand::class, [
            new Response(404),
        ]);

        $url = 'https://padanantest.com';
        $this->artisan(sprintf('ping:url %s', $url))
            ->expectsOutput(__('Ping is not OK on :url.', ['url' => $url]))
            ->assertExitCode(1);

        Mail::assertSent(AppDownMail::class, function ($email) {
            return $email->hasTo(config('mail.developer'));
        });
    }

    private function mockHttp(string $class, array $mocks = []): void
    {
        $mock = new MockHandler($mocks);

        $this->app->when($class)
            ->needs(Client::class)
            ->give(function () use ($mock) {
                $handler = HandlerStack::create($mock);

                return new Client(['handler' => $handler]);
            });
    }
}
