<?php

namespace App\Models;

use App\Observers\GasObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int customer_id
 * @property int apartment_id
 * @property bool is_rent
 * @property int rent_customer_id
 * @property float last_unit
 * @property float current_unit
 * @property float consumption
 * @property float unit_price
 * @property float total_before_discount
 * @property float discount
 * @property float total_price
 * @property string attachment
 * @property string date
 * @property string paid_at
 * @property string notes
 * @property string paid_by
 * @property int created_by
 * @property-read Customer $customer
 * @property-read Apartment $apartment
 */
#[ObservedBy(GasObserver::class)]
class Gas extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'apartment_id',
        'is_rent',
        'rent_customer_id',
        'last_unit',
        'current_unit',
        'consumption',
        'unit_price',
        'total_before_discount',
        'discount',
        'total_price',
        'attachment',
        'date',
        'paid_at',
        'notes',
        'paid_by',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
            'unit_price' => 'integer',
            'date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function rentCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'rent_customer_id');
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
            get: fn() => route('print.gas', $this->id),
        );
    }
}
