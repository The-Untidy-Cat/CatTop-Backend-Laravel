<?php
namespace App\Enums;

enum UserState: string
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
            self::DRAFT => __('messages.user.state.draft'),
            self::ACTIVE => __('messages.user.state.active'),
            self::INACTIVE => __('messages.user.state.inactive'),
            self::BANNED => __('messages.user.state.banned'),
        };
    }
}
