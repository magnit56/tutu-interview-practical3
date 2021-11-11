<?php

namespace Magnit56\TutuInterviewPractical3;

use Exception;
use Spatie\Emoji\Emoji;
use Sokil\IsoCodes\IsoCodesFactory;
use Tightenco\Collect\Support\Collection;

function getCountryData($name) {
    putenv('LANGUAGE=ru_RU.utf8');
    putenv('LC_ALL=ru_RU.utf8');
    setlocale(LC_ALL, 'ru_RU.utf8');

    $alpha2 = getAlpha2FromCountryName($name);
    $emoji = getEmojiFromAlpha2($alpha2);
    $wikiUrl = getWikiUrlFromAlpha2($alpha2);
    return [
        'alpha2' => $alpha2,
        'emoji' => $emoji,
        'wikiUrl' => $wikiUrl
    ];
}

function getAlpha2FromCountryName($countryName)
{
    $countryNameInLowerCase = mb_strtolower($countryName);
    $isoCodes = new IsoCodesFactory();
    $codesOfCountries = collect($isoCodes->getCountries()->toArray())->mapWithKeys(function($country) {
        $countryName = $country->getLocalName();
        $countryNameInLowerCase = mb_strtolower($countryName);
        $alpha2 = $country->getAlpha2();
        return [$countryNameInLowerCase => $alpha2];
    });
    if ($codesOfCountries->has($countryNameInLowerCase)) {
        $countryCode = $codesOfCountries[$countryNameInLowerCase];
        return $countryCode;
    } else {
        throw new CountryNotFoundException('Такой страны не существует или мы не имеем информации о ней, попробуйте ввести синоним этой страны');
    }
}

function getWikiUrlFromAlpha2($alpha2)
{
    $isoCodes = new IsoCodesFactory();
    $countryName = $isoCodes->getCountries()->getByAlpha2($alpha2)->getLocalName();
    $countryNameInSnakeCase = str_replace(' ', '_', $countryName);
    $url = "https://ru.wikipedia.org/wiki/{$countryNameInSnakeCase}";
    return $url;
}

function getEmojiFromAlpha2($alpha2)
{
    return Emoji::countryFlag($alpha2);
}
