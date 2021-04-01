<?php

namespace App\Helpers;

use PragmaRX\Countries\Package\Countries;

class CountriesHelper
{    
    /**
     * retrieves countries list
     *
     * @return array
     */
    public static function all(): array
    {
        $countries = new Countries();

        return $countries->all()->map(function ($country) use ($countries) {
                $country_name = $countries->where('cca2', $country->cca2)->first()['name_' . config('app.lang')];

                if (!is_null($country_name) && !empty($country_name)) {
                    return [$country->cca2 => utf8_decode($country_name)];
                }
            })
            ->values()
            ->toArray();
    }
    
    /**
     * retrieves country name from country code
     *
     * @param  string $country_code
     * @return string
     */
    public static function countryName(string $country_code): string
    {
        $countries = new Countries();
        return $countries->where('cca2', $country_code)->first()['name_' . config('app.lang')];
    }
}
