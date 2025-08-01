<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class QrScanned implements ShouldBroadcast
{
    use SerializesModels;

    public $product;

    public function __construct($product)
    {
        $this->product = [
            'id' => $product['id'] ?? $product->id ?? null,
            'name' => $product['name'] ?? $product->name ?? null,
            'status' => $product['status'] ?? 200,
        ];
    }

    public function broadcastOn()
    {
        return new Channel('qr-channel');
    }

    public function broadcastAs()
    {
        return 'qr.scanned';
    }

    public function broadcastWith()
    {
        return ['product' => $this->product];
    }
}
