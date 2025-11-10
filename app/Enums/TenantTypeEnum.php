<?php

namespace App\Enums;

enum TenantTypeEnum: string
{
    case HOTEL = 'hotel';
    case HOSPITAL = 'hospital';

    public function label(): string
    {
        return match ($this) {
            self::HOTEL => 'Hotel',
            self::HOSPITAL => 'Hospital',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::HOTEL => 'green',
            self::HOSPITAL => 'red',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
