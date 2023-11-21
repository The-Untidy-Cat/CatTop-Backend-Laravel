<?php
namespace App\Enums;

enum StorageProperties: string
{
    case DRIVE = 'drive';
    case CAPACITY = 'capacity';
    case TYPE = 'type';
    // case RPM = 'Revolutions Per Minute';
    // case CACHE = 'Cache';
    public static function toArray(): array {
        return [
            // self::DRIVE,
            // self::CACHE,
            self::CAPACITY,
            self::TYPE,
            // self::RPM,
        ];
    }
}
