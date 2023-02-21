<?php

declare(strict_types=1);

namespace App\Traits\DataBase\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

trait DropIfExists
{
    protected function dropIfExists(string $table, string $column)
    {
        if (Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }

    protected function dropIfExistsForeign(string $table, string $column)
    {
        if (Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->dropConstrainedForeignId($column);
            });
        }
    }
}
