<?php
namespace App\Enums;

enum EmployeeState: string
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
            self::DRAFT => __('messages.employee.state.draft'),
            self::ACTIVE => __('messages.employee.state.active'),
            self::INACTIVE => __('messages.employee.state.inactive'),
            self::BANNED => __('messages.employee.state.banned'),
        };
    }
}
