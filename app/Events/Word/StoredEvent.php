<?php

namespace App\Events\Word;

use App\Models\Term;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StoredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Term
     */
    public $term;

    /**
     * StoredEvent constructor.
     * @param Term $term
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
