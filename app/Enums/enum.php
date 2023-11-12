<?php
// namespace App\Enums;
enum RAMProperties: string
{
    case MEMORY = 'Capacity';
    case TYPE = 'RAM Type';
    case FREQUENCY = 'Frequency';
}

// STORAGE
enum StorageProperties: string
{
    case DRIVE = 'Drive';
    case CAPACITY = 'Capacity';
    case TYPE = 'Drive Type';
    case RPM = 'Revolutions Per Minute';
    case CACHE = 'Cache';
}

// PROCESSOR
enum CPUProperties: string
{
    case NAME = 'CPU Name';
    case CORES = 'Cores';
    case THREADS = 'Threads';
    case BASE_CLOCK = 'Base Clock Speed';
    case TURBO_CLOCK = 'Max Turbo Speed';
    case CACHE = 'Cache';
}

// SCREEN
enum ScreenProperties: string
{
    case SIZE = 'Size';
    case RESOLUTION = 'Resolution';
    case TECHNOLOGY = 'Screen Technology';
    case REFRESH_RATE = 'Refresh Rate';
    case TOUCH = 'Touchscreen';
}

enum VariantType: string
{
    case PROCESSOR = 'CPU';
    case RAM = 'RAM';
    case GPU = 'Graphics Card';
    case STORAGE = 'Storage';
    case SCREEN = 'Display';
    case PORTS = 'Ports';
    case KEYBOARD = 'Keyboard';
    case TOUCHPAD = 'Touchpad';
    case WEBCAM = 'Webcam';
    case BATTERY = 'Battery';
    case WEIGHT = 'Weight';
    case OPERATING_SYSTEM = 'Operating System';
    case WARRANTY = 'Warranty Period';
}

enum ProductVariantState: string
{
    case DRAFT = 0;
    case PUBLISHED = 1;
    case ARCHIVED = 2;
    case OUT_OF_STOCK = 3;
    public static function toArray(): array
    {
        return [
            self::DRAFT,
            self::PUBLISHED,
            self::ARCHIVED,
            self::OUT_OF_STOCK,
        ];
    }
}

enum ProductState: string
{
    case DRAFT = 0;
    case PUBLISHED = 1;
    case ARCHIVED = 2;
    case OUT_OF_STOCK = 3;
    public static function toArray(): array
    {
        return [
            self::DRAFT,
            self::PUBLISHED,
            self::ARCHIVED,
            self::OUT_OF_STOCK,
        ];
    }
}

enum OrderState: string
{
    case DRAFT = 0;
    case PENDING = 1;
    case CONFIRMED = 2;
    case DELIVERING = 3;
    case DELIVERED = 4;
    case CANCELLED = 5;
    case REFUNDED = 7;
    case FAILED = 8;
    public static function toArray(): array
    {
        return [
            self::DRAFT,
            self::PENDING,
            self::CONFIRMED,
            self::DELIVERING,
            self::DELIVERED,
            self::CANCELLED,
            self::REFUNDED,
            self::FAILED,
        ];
    }
}

enum UserState: string
{
    case DRAFT = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
    case BANNED = 3;
    public static function toArray(): array
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::INACTIVE,
            self::BANNED,
        ];
    }
}

enum UserRole: string
{
    case ADMIN = 0;
    case CUSTOMER = 1;
    case SELLER = 2;
    public static function toArray(): array
    {
        return [
            self::ADMIN,
            self::CUSTOMER,
            self::SELLER,
        ];
    }
}

enum EmployeeState: string
{
    case DRAFT = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
    case BANNED = 3;
    public static function toArray(): array
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::INACTIVE,
            self::BANNED,
        ];
    }
}

enum CustomerState: string
{
    case DRAFT = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
    case BANNED = 3;
    public static function toArray(): array
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::INACTIVE,
            self::BANNED,
        ];
    }
}

enum BrandState: string
{
    case DRAFT = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
    public static function toArray(): array
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::INACTIVE,
        ];
    }
}
