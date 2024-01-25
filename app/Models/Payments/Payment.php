<?php

declare(strict_types=1);

namespace App\Models\Payments;

use App\Enums\Payments\Status;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use HasUuids, HasFactory, LogsActivity;

    protected $fillable = [
        'id',
        'client_id',
        'description',
        'value',
        'status',
        'payment_method_id',
        'due_date',
    ];

    protected $casts = [
        'status' => Status::class,
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
