<?php

use App\Console\Commands\InvocableClass;
use App\Console\Commands\SendEmails;
use App\Jobs\BookJob;
use App\Models\Book;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    \Log::info('Schedule closure');
})->name('schedule-closure')->daily();

Schedule::call(new InvocableClass())->daily();

Schedule::command(SendEmails::class, ['2'])
    ->withoutOverlapping()
    ->runInBackground()
    ->emailOutputOnFailure('admin@site.com');

Schedule::job(new BookJob(Book::first()))->everyFiveMinutes();
