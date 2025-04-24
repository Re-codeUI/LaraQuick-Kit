<?php
namespace MagicBox\LaraQuickKit\Helpers;

class ModuleHelper
{
    public static function isActive(string $module): bool
    {
        $active = config('laraquick.active_modules', []);
        return in_array($module, $active);
    }

    public static function getActiveModules(): array
    {
        return config('laraquick.active_modules', []);
    }
}