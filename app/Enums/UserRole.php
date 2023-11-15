<?php
namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    case SELLER = 'seller';
    public static function toArray(): array
    {
        return [
            self::ADMIN->value,
            self::CUSTOMER->value,
            self::SELLER->value,
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
