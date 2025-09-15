<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class SendEmails extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails {option : Any valid option} {--max=10 : Maximum of items}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pending emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $arguments = $this->arguments();

        \Log::info(static::class, $arguments);

        $this->info('Sent emails successfully.');
        // $this->fail('Sent emails failed.');
    }
}
