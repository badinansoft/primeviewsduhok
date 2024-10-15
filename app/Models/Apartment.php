<?php

namespace App\Models;

use App\Enums\ApartmentView;
use App\Observers\ApartmentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $uuid
 * @property int $number
 * @property int $level_id
 * @property ?int $customer_id
 * @property int $tower_id
 * @property ?float $balance
 * @property ApartmentView $view
 * @property bool $is_rent
 * @property ?int $rent_customer_id
 * @property float $gas_unit
 * @property bool $status
 */
#[ObservedBy(ApartmentObserver::class)]
class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'number',
        'level_id',
        'customer_id',
        'tower_id',
        'view',
        'is_rent',
        'balance',
        'gas_unit',
        'rent_customer_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_rent' => 'boolean',
            'number' => 'integer',
            'level_id' => 'integer',
            'customer_id' => 'integer',
            'tower_id' => 'integer',
            'rent_customer_id' => 'integer',
            'view' => ApartmentView::class,
            'balance' => 'integer',
            'status' => 'boolean',
        ];
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function tower(): BelongsTo
    {
        return $this->belongsTo(Tower::class);
    }

    public function rentCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'rent_customer_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function gas(): HasMany
    {
        return $this->hasMany(Gas::class);
    }

    public function title(): Attribute
    {
        return new Attribute(
            get: fn () => $this->tower->name . ' - ' . $this->level->name . ' - ' . $this->number,
        );
    }

    public function profile(): Attribute
    {
        return new Attribute(
            get: fn () => route('profile.show', $this->uuid),
        );
    }
}
