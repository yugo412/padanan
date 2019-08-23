<?php

namespace App\Events\Term;

use App\Models\Term;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TermViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Term
     */
    public $term;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
