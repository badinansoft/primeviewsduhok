<?php

namespace App\Enums;

enum ApartmentView:string
{
    case SOUTH = 'south';
    case SOUTH_EAST = 'south_east';
    case SOUTH_WEST = 'south_west';
    case EAST = 'east';
    case WEST = 'west';
    case NORTH = 'north';
    case NORTH_EAST = 'north_east';
    case NORTH_WEST = 'north_west';
    case EAST_SOUTH_WEST = 'east_south_west';
    case EAST_NORTH_WEST = 'east_north_west';
    case NORTH_WEST_SOUTH = 'north_west_south';
    case NORTH_EAST_SOUTH = 'north_east_south';

    public static function toArray(): array
    {
        return [
            self::SOUTH,
            self::SOUTH_EAST,
            self::SOUTH_WEST,
            self::EAST,
            self::WEST,
            self::NORTH,
            self::NORTH_EAST,
            self::NORTH_WEST,
            self::EAST_SOUTH_WEST,
            self::EAST_NORTH_WEST,
            self::NORTH_WEST_SOUTH,
            self::NORTH_EAST_SOUTH,
        ];
    }

    public function toArabic(): string
    {
        return match ($this) {
            self::SOUTH => 'جنوب',
            self::SOUTH_EAST => 'جنوب شرق',
            self::SOUTH_WEST => 'جنوب غرب',
            self::EAST => 'شرق',
            self::WEST => 'غرب',
            self::NORTH => 'شمال',
            self::NORTH_EAST => 'شمال شرق',
            self::NORTH_WEST => 'شمال غرب',
            self::EAST_SOUTH_WEST => 'شرق جنوب غرب',
            self::EAST_NORTH_WEST => 'شرق شمال غرب',
            self::NORTH_WEST_SOUTH => 'شمال غرب جنوب',
            self::NORTH_EAST_SOUTH => 'شمال شرق جنوب',
        };
    }
}
