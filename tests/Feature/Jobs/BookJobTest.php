<?php

use App\Jobs\BookJob;
use App\Models\Book;

test('released', function () {
    $job = (new BookJob(Book::factory()->create()))->withFakeQueueInteractions();

    $job->handle();

    $job->assertNotFailed();
});

test('failed', function () {
    $job = (new BookJob(new Book()))->withFakeQueueInteractions();

    $job->handle();

    $job->assertFailedWith(\InvalidArgumentException::class);
});
