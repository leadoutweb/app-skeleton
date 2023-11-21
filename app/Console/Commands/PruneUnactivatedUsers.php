<?php

namespace App\Console\Commands;

use App\Authentication\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneUnactivatedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prune-unactivated-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune users that have not been activated.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->where('created_at', '<', Carbon::today()->subDays(7))->whereNull('activated_at')->delete();
    }
}
