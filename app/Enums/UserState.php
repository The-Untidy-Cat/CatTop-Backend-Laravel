<?php
namespace App\Enums;

enum UserState: int
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
