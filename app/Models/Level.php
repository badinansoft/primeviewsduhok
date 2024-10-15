<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function title(): Attribute
    {
        return new Attribute(
            get: fn () => 'Level ' . $this->name,
        );
    }
}
