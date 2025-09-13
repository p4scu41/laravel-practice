<?php

use App\Models\Book;
use Database\Seeders\BookSeeder;

test('seed', function () {
    $this->assertDatabaseEmpty('books');

    $this->seed(BookSeeder::class);

    $this->assertDatabaseCount('books', 5);
});

test('create', function () {
    $book = Book::factory()->create();

    $this->assertModelExists($book);
});

test('read', function () {
    Book::factory()->create();

    $book = Book::first();

    $this->assertModelExists($book);
});

test('update', function () {
    $book = Book::factory()->create();

    $book->update(['name' => 'book updated']);

    $book = Book::where(['name' => 'book updated'])->first();

    $this->assertModelExists($book);
});

test('delete', function () {
    $book = Book::factory()->create();

    $book->delete();

    $this->assertModelMissing($book);
});
