<?php

namespace App\Console\Commands;

use App\Models\Raw;
use Illuminate\Console\Command;

class WipCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function handle()
    {
        $raw = Raw::where('uri', '/test')->first();
        if (!$raw) {
            Raw::create(['uri' => '/test', 'body' => 'test me!']);
        }
        
        return 0;
    }
}
