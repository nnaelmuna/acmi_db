<?php

namespace App\Console\Commands;

use App\Models\Inbound;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:clean-old-inbounds')]
#[Description('Command description')]
class CleanOldInbounds extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        Inbound::where('status', 'approved')
            ->where('approved_at', '<', Carbon::now()->subWeek())
            ->update(['status' => 'archived']); 

        $this->info('Old inbounds cleaned successfully');
    }
}
