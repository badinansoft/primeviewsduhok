<?php

namespace App\Models;

use App\Observers\WaterObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $apartment_id
 * @property int $customer_id
 * @property bool $is_rent
 * @property ?int $rent_customer_id
 * @property float $amount
 * @property ?string $paid_at
 * @property ?string $notes
 * @property ?int $paid_by
 * @property string $start_date
 * @property string $end_date
 * @property int $created_by
 * @property-read Customer $customer
 * @property-read Customer $rentCustomer
 * @property-read Apartment $apartment
 * @property-read bool $isPaid
 */
#[ObservedBy(WaterObserver::class)]

class Water extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'customer_id',
        'is_rent',
        'rent_customer_id',
        'amount',
        'paid_at',
        'notes',
        'start_date',
        'end_date',
        'created_by',
        'paid_by',
    ];

    protected $casts = [
        'is_rent' => 'boolean',
        'paid_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function rentCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function isPaid(): Attribute
    {
        return new Attribute(
            get: fn() => $this->paid_at !== null,
        );
    }

    public function receiptUrl(): Attribute
    {
        return new Attribute(
            get: fn() => route('print.water', $this->id),
        );
    }
}
