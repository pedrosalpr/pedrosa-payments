<?php

declare(strict_types=1);

namespace App\Models\Payments;

use App\Enums\Payments\Status;
use App\Models\Clients\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use HasUuids, HasFactory, LogsActivity;

    protected $fillable = [
        'id',
        'user_id',
        'client_id',
        'description',
        'value',
        'status',
        'payment_method_id',
        'due_date',
        'tax',
    ];

    protected $casts = [
        'status' => Status::class,
        'due_date' => 'date',
        'processed_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
