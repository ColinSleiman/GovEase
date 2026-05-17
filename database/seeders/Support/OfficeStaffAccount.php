<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Str;

final class OfficeStaffAccount
{
    public static function email(
        string $municipalityName,
        string $officeName,
        int $officeCount = 1,
    ): string {
        $municipalitySlug = str_replace('-', '', Str::slug($municipalityName));

        if ($officeCount <= 1) {
            return "officestaff{$municipalitySlug}@govease.com";
        }

        $officeSlug = self::officeSlug($officeName);

        return "officestaff{$municipalitySlug}{$officeSlug}@govease.com";
    }

    /**
     * @return array{firstName: string, lastName: string}
     */
    public static function names(string $municipalityName, string $officeName, int $officeCount = 1): array
    {
        if ($officeCount <= 1) {
            return [
                'firstName' => 'Office',
                'lastName' => 'Staff ('.$municipalityName.')',
            ];
        }

        $parts = preg_split('/\s*-\s*/', $officeName) ?: [$officeName];
        $officeLabel = trim((string) end($parts));

        return [
            'firstName' => 'Office',
            'lastName' => 'Staff ('.$municipalityName.' - '.$officeLabel.')',
        ];
    }

    private static function officeSlug(string $officeName): string
    {
        $parts = preg_split('/\s*-\s*/', $officeName) ?: [$officeName];
        $label = trim((string) end($parts));
        $label = preg_replace('/\s+(Municipality\s+)?Office$/i', '', $label) ?? $label;

        return str_replace('-', '', Str::slug($label !== '' ? $label : $officeName));
    }
}
