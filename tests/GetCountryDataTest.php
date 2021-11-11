<?php

namespace Magnit56\TutuInterviewPractical3;

use Magnit56\TutuInterviewPractical3\CountryNotFoundException;
use PHPUnit\Framework\TestCase;
use function Magnit56\TutuInterviewPractical3\getCountryData;

class GetCountryDataTest extends TestCase
{
    public function setUp(): void
    {
        putenv('LANGUAGE=ru_RU.utf8');
        putenv('LC_ALL=ru_RU.utf8');
        setlocale(LC_ALL, 'ru_RU.utf8');
    }

    public function testCountry(): void
    {
        $actual = getCountryData('Украина');
        $expected = [
            'alpha2' => 'UA',
            'emoji' => '🇺🇦',
            'wikiUrl' => 'https://ru.wikipedia.org/wiki/Украина'
        ];
        $this->assertEquals($expected, $actual);
    }

    public function testCountryNegative(): void
    {
        $this->expectException(CountryNotFoundException::class);
        $this->expectExceptionMessage('Такой страны не существует или мы не имеем информации о ней, попробуйте ввести синоним этой страны');
        $actual = getCountryData('Цыгания');
    }
}
