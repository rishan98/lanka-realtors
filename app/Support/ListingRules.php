<?php

namespace App\Support;

class ListingRules
{
    public static function validKind(string $kind): bool
    {
        return array_key_exists($kind, config('listing.kinds', []));
    }

    public static function validSubtype(string $kind, string $subtype): bool
    {
        $subtypes = config('listing.kinds.'.$kind.'.subtypes', []);

        return array_key_exists($subtype, $subtypes);
    }

    public static function subtypeRuleString(string $kind): string
    {
        $keys = array_keys(config('listing.kinds.'.$kind.'.subtypes', []));

        return 'in:'.implode(',', $keys);
    }
}
