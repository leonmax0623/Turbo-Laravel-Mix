<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Fresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'main:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Установка с нуля';

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
     * @return mixed
     */
    public function handle()
    {
        $this->call('migrate:fresh');

        $this->call('DB:seed');

        $this->call('main:fake');
    }
}
