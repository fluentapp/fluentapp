<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\DailyHash\Service\DailyHashCreator;

class RotateHash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rotate-hash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotates a hash in the DB';
    
    private $dailyHashService = null;
    
    
    public function __construct() {
        parent::__construct();
        $this->dailyHashService = new DailyHashCreator;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
       $this->info('Generating new hash');
       $this->dailyHashService->rotate();
       $this->info('New Hash Generated');
    }
}
