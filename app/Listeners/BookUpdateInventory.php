<?php

namespace App\Listeners;

use App\Events\BookRegistered;
use App\Events\BookRemoved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookUpdateInventory
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookRegistered|BookRemoved $event): void
    {
        \Log::info(static::class, $event->book->toArray());
    }
}
