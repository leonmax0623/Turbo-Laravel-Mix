<?php

if (!function_exists('user')) {
    function user()
    {
        return auth()->user();
    }
}

if (!function_exists('id')) {
    function id()
    {
        return auth()->id();
    }
}

if (!function_exists('unknown_error')) {
    function unknown_error(): string
    {
        return 'Сервер временно недоступен';
    }
}

if (!function_exists('response_json')) {
    function response_json(array $data = [], string $message = '', bool $success = true)
    {
        if (empty($message)) {
            return response()->json(['success' => $success] + $data);
        }

        return response()->json(['success' => $success, 'message' => $message] + $data);
    }
}

if (!function_exists('response_unknown_error')) {
    function response_unknown_error()
    {
        return response()->json(['success' => false, 'message' => unknown_error()]);
    }
}

if (!function_exists('response_success')) {
    function response_success(?string $message = '')
    {
        if (empty($message)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}

if (!function_exists('response_error')) {
    function response_error(?string $message = '', ?int $code = 400)
    {
        return response()->json(['success' => false, 'message' => $message], $code);
    }
}

if (!function_exists('cff')) {
    function cff(string $format, $datetime): ?Carbon\Carbon
    {
        if (empty($datetime)) {
            return null;
        }

        if (is_string($datetime)) {
            return Carbon\Carbon::createFromFormat($format, $datetime);
        }

        return $datetime;
    }
}

if (!function_exists('date_to_db')) {
    function date_to_db($datetime, string $format = 'd.m.Y H:i'): ?string
    {
        try {
            if (empty($datetime)) {
                return null;
            }

            if (is_string($datetime)) {
                return cff($format, $datetime)->format('Y-m-d H:i:s');
            }

            return $datetime->format('Y-m-d H:i:s');
        } catch (Throwable) {
            return null;
        }
    }
}

if (!function_exists('db_to_date')) {
    function db_to_date($datetime, string $format = 'd.m.Y'): ?string
    {
        try {
            if (empty($datetime)) {
                return null;
            }

            if (is_string($datetime)) {
                return cff('Y-m-d H:i:s', $datetime)->format($format);
            }

            return $datetime->format($format);
        } catch (Throwable) {
            return null;
        }
    }
}

if (!function_exists('sanitize_file_name')) {
    function sanitize_file_name(?string $fileName): ?string
    {
        if (empty($fileName)) {
            return null;
        }

        return \Illuminate\Support\Str::lower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
    }
}

if (!function_exists('gen_file_name')) {
    function gen_file_name($fileOrExtension): ?string
    {
        if (is_string($fileOrExtension)) {
            return Illuminate\Support\Str::lower(\Illuminate\Support\Str::random()) . '.' .
                request()->file($fileOrExtension)->getClientOriginalExtension();
        }

        return Illuminate\Support\Str::lower(\Illuminate\Support\Str::random()) . '.' .
            $fileOrExtension->getClientOriginalExtension();
    }
}

if (!function_exists('number2string')) {
    function number2string($number): string
    {

        // обозначаем словарь в виде статической переменной функции, чтобы
        // при повторном использовании функции его не определять заново
        static $dic = array(

            // словарь необходимых чисел
            array(
                -2 => 'две',
                -1 => 'одна',
                1 => 'один',
                2 => 'два',
                3 => 'три',
                4 => 'четыре',
                5 => 'пять',
                6 => 'шесть',
                7 => 'семь',
                8 => 'восемь',
                9 => 'девять',
                10 => 'десять',
                11 => 'одиннадцать',
                12 => 'двенадцать',
                13 => 'тринадцать',
                14 => 'четырнадцать',
                15 => 'пятнадцать',
                16 => 'шестнадцать',
                17 => 'семнадцать',
                18 => 'восемнадцать',
                19 => 'девятнадцать',
                20 => 'двадцать',
                30 => 'тридцать',
                40 => 'сорок',
                50 => 'пятьдесят',
                60 => 'шестьдесят',
                70 => 'семьдесят',
                80 => 'восемьдесят',
                90 => 'девяносто',
                100 => 'сто',
                200 => 'двести',
                300 => 'триста',
                400 => 'четыреста',
                500 => 'пятьсот',
                600 => 'шестьсот',
                700 => 'семьсот',
                800 => 'восемьсот',
                900 => 'девятьсот'
            ),

            // словарь порядков со склонениями для плюрализации
            array(
                array('рубль', 'рубля', 'рублей'),
                array('тысяча', 'тысячи', 'тысяч'),
                array('миллион', 'миллиона', 'миллионов'),
                array('миллиард', 'миллиарда', 'миллиардов'),
                array('триллион', 'триллиона', 'триллионов'),
                array('квадриллион', 'квадриллиона', 'квадриллионов'),
                // квинтиллион, секстиллион и т.д.
            ),

            // карта плюрализации
            array(
                2, 0, 1, 1, 1, 2
            )
        );

        // обозначаем переменную в которую будем писать сгенерированный текст
        $string = array();

        // дополняем число нулями слева до количества цифр кратного трем,
        // например 1234, преобразуется в 001234
        $number = str_pad($number, ceil(strlen($number) / 3) * 3, 0, STR_PAD_LEFT);

        // разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
        // т.к. мы не знаем максимальный порядок числа и будем бежать снизу
        // единицы, тысячи, миллионы и т.д.
        $parts = array_reverse(str_split($number, 3));

        // бежим по каждой части
        foreach ($parts as $i => $part) {

            // если часть не равна нулю, нам надо преобразовать ее в текст
            if ($part > 0) {

                // обозначаем переменную в которую будем писать составные числа для текущей части
                $digits = array();

                // если число трех значное, запоминаем количество сотен
                if ($part > 99) {
                    $digits[] = floor($part / 100) * 100;
                }

                // если последние 2 цифры не равны нулю, продолжаем искать составные числа
                // (данный блок прокомментирую при необходимости)
                if ($mod1 = $part % 100) {
                    $mod2 = $part % 10;
                    $flag = $i == 1 && $mod1 != 11 && $mod1 != 12 && $mod2 < 3 ? -1 : 1;
                    if ($mod1 < 20 || !$mod2) {
                        $digits[] = $flag * $mod1;
                    } else {
                        $digits[] = floor($mod1 / 10) * 10;
                        $digits[] = $flag * $mod2;
                    }
                }

                // берем последнее составное число, для плюрализации
                $last = abs(end($digits));

                // преобразуем все составные числа в слова
                foreach ($digits as $j => $digit) {
                    $digits[$j] = $dic[0][$digit];
                }

                // добавляем обозначение порядка или валюту
                $digits[] = $dic[1][$i][(($last %= 100) > 4 && $last < 20) ? 2 : $dic[2][min($last % 10, 5)]];

                // объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
                array_unshift($string, join(' ', $digits));
            }
        }

        // преобразуем переменную в текст и возвращаем из функции, ура!
        return join(' ', $string);
    }

    function mb_ucfirst($string, $encoding = 'utf8'): string
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}
