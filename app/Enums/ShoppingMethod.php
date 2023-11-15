<?php
namespace App\Enums;

enum ShoppingMethod: string
{
    case ONLINE = 'online';
    case OFFLINE = 'offline';
    public static function toArray(): array
    {
        return [
            self::ONLINE->value,
            self::OFFLINE->value
        ];
    }
    public function label(): string
    {
        return static::getLabel($this);
    }
    public static function getLabel(self $id): string
    {
        return match ($id) {
            self::ONLINE => __('messages.shopping.method.online'),
            self::OFFLINE => __('messages.shopping.method.offline'),
        };
    }
}
