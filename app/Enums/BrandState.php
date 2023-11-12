<?php
namespace App\Enums;

enum BrandState: int
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
    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            self::DRAFT => __('messages.brand.state.draft'),
            self::ACTIVE => __('messages.brand.state.active'),
            self::INACTIVE => __('messages.brand.state.inactive'),
        };
    }
}
