<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddMathesForBet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matches:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add matches from the Api daily';

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
     * @return mixed
     */
    public function handle()
    {
        
    }
}
