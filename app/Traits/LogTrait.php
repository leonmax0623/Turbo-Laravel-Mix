<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LogTrait
{
    public static function error(\Throwable $e, $message = null) {
        Log::error($message ?: 'Ошибка', [
            $e->getCode(),
            $e->getLine(),
            $e->getMessage(),
            $e->getTrace()
        ]);
    }
}
