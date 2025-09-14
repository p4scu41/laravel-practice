<?php

use App\Mail\BookCreated;
use App\Mail\BookDeleted;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mail', function () {
    // return (new BookCreated(Book::first()))->render();
    return (new BookDeleted(Book::first()))->render();
});
