<?php

namespace App\Jobs;

use App\Models\Book;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BookJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $book)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->book?->id) {
            \Log::info(static::class, $this->book->toArray());
            // $this->fail(new \InvalidArgumentException('Invalid book at ' . static::class, 1));
        }

    }
}
