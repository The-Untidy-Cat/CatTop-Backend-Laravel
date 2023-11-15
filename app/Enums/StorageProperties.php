<?php
namespace App\Enums;

enum StorageProperties: string
{
    case DRIVE = 'storage.drive';
    case CAPACITY = 'storage.capacity';
    case TYPE = 'storage.type';
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
