<?php
namespace App\Enums;

enum BrandState: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    public static function toArray(): array
    {
        return [
            self::DRAFT->value,
            self::ACTIVE->value,
            self::INACTIVE->value,
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
