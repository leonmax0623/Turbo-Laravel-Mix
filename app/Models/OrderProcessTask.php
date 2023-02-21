<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProcessTask extends Model
{
    use HasFactory;

    protected $table = 'order_process_task';

    protected $guarded = ['id'];

    protected $casts = [
        'order_id' => 'integer',
        'process_task_id' => 'integer'
    ];
}
