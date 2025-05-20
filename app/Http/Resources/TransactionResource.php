<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'receiver' => $this->receiver,
            'sender' => $this->whenNotNull($this->sender),
            'amount' => $this->amount,
            'description' => $this->description,
            'created at' => $this->created_at
        ];
    }
}
