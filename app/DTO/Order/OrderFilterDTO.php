<?php

namespace App\DTO\Order;

use Illuminate\Http\Request;

class OrderFilterDTO
{
    public function __construct(
        public readonly ?string $search,
        public readonly ?string $status,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->string('search')->toString() ?: null,
            status: $request->string('status')->toString() ?: null,
        );
    }
}
