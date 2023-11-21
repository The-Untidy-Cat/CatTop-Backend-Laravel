<?php
namespace App\Enums;

enum RAMProperties: string
{
    case MEMORY = 'capacity';
    case TYPE = 'type';
    case FREQUENCY = 'frequency';
    public static function toArray(): array
    {
        return [
            self::MEMORY->value,
            self::TYPE->value,
            self::FREQUENCY->value,
        ];
    }
}
