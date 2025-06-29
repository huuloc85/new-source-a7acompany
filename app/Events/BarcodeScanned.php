<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class BarcodeScanned implements ShouldBroadcast
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
        return new Channel('barcode-channel');
    }

    public function broadcastAs()
    {
        return 'barcode.scanned';
    }

    public function broadcastWith()
    {
        return ['product' => $this->product];
    }
}
