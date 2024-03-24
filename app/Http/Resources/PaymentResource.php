<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Payment */
class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'transaction_number' => $this->transaction_number, // this is the transaction number
            'price' => $this->price, // this is the price of product
            'payment' => $this->payment, // this is the payment status
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->user->name, // This is the user who made the purchase
            'id' => $this->id,
        ];
    }
}
