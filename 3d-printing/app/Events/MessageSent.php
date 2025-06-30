<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Buat instance event baru.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Channel yang akan di-broadcast.
     * Kita menggunakan PrivateChannel agar hanya user yang berhak yang bisa mendengarkan.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->conversation_id),
        ];
    }
    
    /**
     * Nama event yang akan di-broadcast.
     * Defaultnya adalah nama class, tapi ini lebih rapi.
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }
}