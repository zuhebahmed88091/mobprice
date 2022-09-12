<?php

namespace App\Console\Commands;

use App\Http\Controllers\ScraperController;
use Carbon\Carbon;
use Illuminate\Console\Command;

class everyEvening extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:every_evening';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron run in every evening';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        // echo "Log";
        $now = Carbon::now();
        echo "======================\n";
        echo "Cron Every Evening log\n";
        echo "Date: " . $now->toDateTimeString()."\n";

        $scraperController = new ScraperController();
        $scraperController->latestMobiles();
    }
}
