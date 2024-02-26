<?php

namespace App\Console\Commands;

use App\Exceptions\ApplicationException;
use App\Services\MessageService;
use Illuminate\Console\Command;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pending messages';

    /**
     * Execute the console command.
     *
     * @throws ApplicationException
     */
    public function handle(): void
    {
        $transactionService = new MessageService();
        $transactionService->sendMessage();
    }
}
