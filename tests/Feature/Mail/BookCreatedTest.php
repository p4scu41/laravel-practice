<?php

use App\Mail\BookCreated;
use App\Models\Book;

test('render', function () {
    $book = Book::factory()->create();
    $mailable = new BookCreated($book);

    $mailable->assertSeeInHtml($book->name);
});
