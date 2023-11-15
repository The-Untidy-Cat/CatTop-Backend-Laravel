<?php
namespace App\Enums;

enum GPUProperties: string
{
    case NAME = 'gpu.name';
    case MEMORY = 'gpu.memory';
    case TYPE = 'gpu.type';
    case FREQUENCY = 'gpu.frequency';
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
