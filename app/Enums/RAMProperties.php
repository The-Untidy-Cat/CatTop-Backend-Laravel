<?php
namespace App\Enums;

enum RAMProperties: string
{
    case MEMORY = 'ram.capacity';
    case TYPE = 'ram.type';
    case FREQUENCY = 'ram.frequency';
    public static function toArray(): array
    {
        return [
            self::MEMORY->value,
            self::TYPE->value,
            self::FREQUENCY->value,
        ];
    }
}
