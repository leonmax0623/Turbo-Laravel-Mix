<?php

namespace App\Console\Commands;

use App\Services\Atol\AtolService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class AtolCloseShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'main:close:shift';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            (new AtolService())->closeShift();
        } catch (Throwable $e) {
            Log::error('Ошибка при закрытии смены', [
                'message'   => $e->getMessage(),
                'line'      => $e->getLine(),
                'code'      => $e->getCode(),
                'file'      => $e->getFile(),
            ]);
        }
        return 0;
    }
}
