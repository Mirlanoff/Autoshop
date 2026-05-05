<?php

namespace App\DTO\Order;

use Illuminate\Http\Request;

class CreateOrderDTO
{
    public function __construct(
        public readonly string $customerName,
        public readonly string $phone,
        public readonly ?string $address = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            customerName: $request->string('customer_name')->toString(),
            phone: $request->string('phone')->toString(),
            address: $request->string('address')->toString() ?: null,
        );
    }
}
