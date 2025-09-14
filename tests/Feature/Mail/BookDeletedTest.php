<?php

use App\Mail\BookDeleted;
use App\Models\Book;

test('render', function () {
    $book = Book::factory()->create();
    $mailable = new BookDeleted($book);

    $mailable->assertSeeInHtml($book->name);
});
