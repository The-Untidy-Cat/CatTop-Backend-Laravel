<?php
namespace App\Enums;

enum CustomerState: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';
    public static function toArray(): array
    {
        return [
            self::DRAFT->value,
            self::ACTIVE->value,
            self::INACTIVE->value,
            self::BANNED->value,
        ];
    }
    public function label(): string
    {
        return static::getLabel($this);
    }
    public static function getLabel(self $id): string
    {
        return match ($id) {
            self::DRAFT => __('messages.customer.state.draft'),
            self::ACTIVE => __('messages.customer.state.active'),
            self::INACTIVE => __('messages.customer.state.inactive'),
            self::BANNED => __('messages.customer.state.banned'),
        };
    }
}
