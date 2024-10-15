<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 */
class Tower extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function apartments(): HasMany
    {
        return $this->hasMany(Apartment::class);
    }

}
