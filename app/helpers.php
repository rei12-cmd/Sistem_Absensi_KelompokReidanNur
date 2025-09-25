<?php
if (!function_exists('setActive')) {
    function setActive($routes, $class = 'active')
    {
        foreach ((array) $routes as $route) {
            if (request()->routeIs($route)) {
                return $class;
            }
        }
        return '';
    }
}

if (!function_exists('setMenuOpen')) {
    function setMenuOpen($routes, $class = 'menu-open')
    {
        foreach ((array) $routes as $route) {
            if (request()->routeIs($route)) {
                return $class;
            }
        }
        return '';
    }
}

function convertToEnglishDate($dateInIndonesian)
{
    $dayMap = [
        'Senin' => 'Monday',
        'Selasa' => 'Tuesday',
        'Rabu' => 'Wednesday',
        'Kamis' => 'Thursday',
        'Jumat' => 'Friday',
        'Sabtu' => 'Saturday',
        'Minggu' => 'Sunday'
    ];

    $monthMap = [
        'Januari' => 'January',
        'Februari' => 'February',
        'Maret' => 'March',
        'April' => 'April',
        'Mei' => 'May',
        'Juni' => 'June',
        'Juli' => 'July',
        'Agustus' => 'August',
        'September' => 'September',
        'Oktober' => 'October',
        'November' => 'November',
        'Desember' => 'December'
    ];

    $dateInEnglish = str_replace(array_keys($dayMap), array_values($dayMap), $dateInIndonesian);
    $dateInEnglish = str_replace(array_keys($monthMap), array_values($monthMap), $dateInEnglish);

    return $dateInEnglish;
}
