<?php

namespace App\Console\Commands;

class InvocableClass
{
    public function __invoke(): void
    {
        \Log::info(static::class);
    }
}
