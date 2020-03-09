<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppDownMail extends Mailable
{
    use Queueable, SerializesModels;

    private $url;

    private $message;

    /**
     * Create a new message instance.
     *
     * @param string $url
     * @param string $message
     */
    public function __construct(string $url, string $message)
    {
        $this->url = $url;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Status Website :url', ['url' => $this->url]))
            ->markdown('app.down', [
                'url' => $this->url,
                'message' => $this->message,
            ]);
    }
}
