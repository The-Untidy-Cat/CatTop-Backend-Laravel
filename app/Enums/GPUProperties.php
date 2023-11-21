<?php
namespace App\Enums;

enum GPUProperties: string
{
    case NAME = 'name';
    case MEMORY = 'memory';
    case TYPE = 'type';
    case FREQUENCY = 'frequency';
    public static function toArray(): array
    {
        return [
            self::NAME->value,
            self::MEMORY->value,
            self::TYPE->value,
            self::FREQUENCY->value,
        ];
    }
}
