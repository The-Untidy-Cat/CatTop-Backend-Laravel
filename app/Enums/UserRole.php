<?php
namespace App\Enums;

enum UserRole: int
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
    public function label(): string
    {
        return static::getLabel($this);
    }
    public static function getLabel(self $id): string
    {
        return match ($id) {
            self::ADMIN => __('messages.user.role.admin'),
            self::CUSTOMER => __('messages.user.role.customer'),
            self::SELLER => __('messages.user.role.seller'),
        };
    }
}
