<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;   

class MarkUsersOffline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mark-users-offline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::where(
            'last_seen_at',
            '<',
            now()->subMinutes(5)
        )
        ->update(['is_online' => false]);

        return self::SUCCESS;
    }
}
