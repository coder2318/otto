<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Sokil\IsoCodes\Database\Countries\Country;
use Sokil\IsoCodes\Database\Languages\Language;
use Sokil\IsoCodes\IsoCodesFactory;

class IsoService
{
    protected function resolver(Country|Language $item): array
    {
        return [
            'name' => $item->getName(),
            'code' => $item->getAlpha2(),
        ];
    }

    public function listCountries(): Collection
    {
        return collect(app(IsoCodesFactory::class)->getCountries())
            ->map(fn (Country $item) => $this->resolver($item))
            ->values();
    }

    public function listLanguages(): Collection
    {
        $languages = [
            'af' => ['name' => 'Afrikaans', 'native' => 'Afrikaans', 'id' => 1],
            'sq' => ['name' => 'Albanian - shqip', 'native' => 'shqip', 'id' => 2],
            'am' => ['name' => 'Amharic - አማርኛ', 'native' => 'አማርኛ', 'id' => 3],
            'ar' => ['name' => 'Arabic - العربية', 'native' => 'العربية', 'id' => 4],
            'an' => ['name' => 'Aragonese - aragonés', 'native' => 'aragonés', 'id' => 5],
            'hy' => ['name' => 'Armenian - հայերեն', 'native' => 'հայերեն', 'id' => 6],
            'ast' => ['name' => 'Asturian - asturianu', 'native' => 'asturianu', 'id' => 7],
            'az' => ['name' => 'Azerbaijani - azərbaycan dili', 'native' => 'azərbaycan dili', 'id' => 8],
            'eu' => ['name' => 'Basque - euskara', 'native' => 'euskara', 'id' => 9],
            'be' => ['name' => 'Belarusian - беларуская', 'native' => 'беларуская', 'id' => 10],
            'bn' => ['name' => 'Bengali - বাংলা', 'native' => 'বাংলা', 'id' => 11],
            'bs' => ['name' => 'Bosnian - bosanski', 'native' => 'bosanski', 'id' => 12],
            'br' => ['name' => 'Breton - brezhoneg', 'native' => 'brezhoneg', 'id' => 13],
            'bg' => ['name' => 'Bulgarian - български', 'native' => 'български', 'id' => 14],
            'ca' => ['name' => 'Catalan - català', 'native' => 'català', 'id' => 15],
            'ckb' => ['name' => 'Central Kurdish - کوردی (دەستنوسی عەرەبی)', 'native' => 'کوردی (دەستنوسی عەرەبی)', 'id' => 16],
            'zh' => ['name' => 'Chinese - 中文', 'native' => '中文', 'id' => 17],
            'zh-HK' => ['name' => 'Chinese (Hong Kong) - 中文（香港）', 'native' => '中文（香港）', 'id' => 18],
            'zh-CN' => ['name' => 'Chinese (Simplified) - 中文（简体）', 'native' => '中文（简体）', 'id' => 19],
            'zh-TW' => ['name' => 'Chinese (Traditional) - 中文（繁體）', 'native' => '中文（繁體）', 'id' => 20],
            'co' => ['name' => 'Corsican', 'native' => 'Corsican', 'id' => 21],
            'hr' => ['name' => 'Croatian - hrvatski', 'native' => 'hrvatski', 'id' => 22],
            'cs' => ['name' => 'Czech - čeština', 'native' => 'čeština', 'id' => 23],
            'da' => ['name' => 'Danish - dansk', 'native' => 'dansk', 'id' => 24],
            'nl' => ['name' => 'Dutch - Nederlands', 'native' => 'Nederlands', 'id' => 25],
            'en' => ['name' => 'English', 'native' => 'English', 'id' => 26],
            'en-AU' => ['name' => 'English (Australia)', 'native' => 'English (Australia)', 'id' => 27],
            'en-CA' => ['name' => 'English (Canada)', 'native' => 'English (Canada)', 'id' => 28],
            'en-IN' => ['name' => 'English (India)', 'native' => 'English (India)', 'id' => 29],
            'en-NZ' => ['name' => 'English (New Zealand)', 'native' => 'English (New Zealand)', 'id' => 30],
            'en-ZA' => ['name' => 'English (South Africa)', 'native' => 'English (South Africa)', 'id' => 31],
            'en-GB' => ['name' => 'English (United Kingdom)', 'native' => 'English (United Kingdom)', 'id' => 32],
            'en-US' => ['name' => 'English (United States)', 'native' => 'English (United States)', 'id' => 33],
            'eo' => ['name' => 'Esperanto - esperanto', 'native' => 'esperanto', 'id' => 34],
            'et' => ['name' => 'Estonian - eesti', 'native' => 'eesti', 'id' => 35],
            'fo' => ['name' => 'Faroese - føroyskt', 'native' => 'føroyskt', 'id' => 36],
            'fil' => ['name' => 'Filipino', 'native' => 'Filipino', 'id' => 37],
            'fi' => ['name' => 'Finnish - suomi', 'native' => 'suomi', 'id' => 38],
            'fr' => ['name' => 'French - français', 'native' => 'français', 'id' => 39],
            'fr-CA' => ['name' => 'French (Canada) - français (Canada)', 'native' => 'français (Canada)', 'id' => 40],
            'fr-FR' => ['name' => 'French (France) - français (France)', 'native' => 'français (France)', 'id' => 41],
            'fr-CH' => ['name' => 'French (Switzerland) - français (Suisse)', 'native' => 'français (Suisse)', 'id' => 42],
            'gl' => ['name' => 'Galician - galego', 'native' => 'galego', 'id' => 43],
            'ka' => ['name' => 'Georgian - ქართული', 'native' => 'ქართული', 'id' => 44],
            'de' => ['name' => 'German - Deutsch', 'native' => 'Deutsch', 'id' => 45],
            'de-AT' => ['name' => 'German (Austria) - Deutsch (Österreich)', 'native' => 'Deutsch (Österreich)', 'id' => 46],
            'de-DE' => ['name' => 'German (Germany) - Deutsch (Deutschland)', 'native' => 'Deutsch (Deutschland)', 'id' => 47],
            'de-LI' => ['name' => 'German (Liechtenstein) - Deutsch (Liechtenstein)', 'native' => 'Deutsch (Liechtenstein)', 'id' => 48],
            'de-CH' => ['name' => 'German (Switzerland) - Deutsch (Schweiz)', 'native' => 'Deutsch (Schweiz)', 'id' => 49],
            'el' => ['name' => 'Greek - Ελληνικά', 'native' => 'Ελληνικά', 'id' => 50],
            'gn' => ['name' => 'Guarani', 'native' => 'Guarani', 'id' => 51],
            'gu' => ['name' => 'Gujarati - ગુજરાતી', 'native' => 'ગુજરાતી', 'id' => 52],
            'ha' => ['name' => 'Hausa', 'native' => 'Hausa', 'id' => 53],
            'haw' => ['name' => 'Hawaiian - ʻŌlelo Hawaiʻi', 'native' => 'ʻŌlelo Hawaiʻi', 'id' => 54],
            'he' => ['name' => 'Hebrew - עברית', 'native' => 'עברית', 'id' => 55],
            'hi' => ['name' => 'Hindi - हिन्दी', 'native' => 'हिन्दी', 'id' => 56],
            'hu' => ['name' => 'Hungarian - magyar', 'native' => 'magyar', 'id' => 57],
            'is' => ['name' => 'Icelandic - íslenska', 'native' => 'íslenska', 'id' => 58],
            'id' => ['name' => 'Indonesian - Indonesia', 'native' => 'Indonesia', 'id' => 59],
            'ia' => ['name' => 'Interlingua', 'native' => 'Interlingua', 'id' => 60],
            'ga' => ['name' => 'Irish - Gaeilge', 'native' => 'Gaeilge', 'id' => 61],
            'it' => ['name' => 'Italian - italiano', 'native' => 'italiano', 'id' => 62],
            'it-IT' => ['name' => 'Italian (Italy) - italiano (Italia)', 'native' => 'italiano (Italia)', 'id' => 63],
            'it-CH' => ['name' => 'Italian (Switzerland) - italiano (Svizzera)', 'native' => 'italiano (Svizzera)', 'id' => 64],
            'ja' => ['name' => 'Japanese - 日本語', 'native' => '日本語', 'id' => 65],
            'kn' => ['name' => 'Kannada - ಕನ್ನಡ', 'native' => 'ಕನ್ನಡ', 'id' => 66],
            'kk' => ['name' => 'Kazakh - қазақ тілі', 'native' => 'қазақ тілі', 'id' => 67],
            'km' => ['name' => 'Khmer - ខ្មែរ', 'native' => 'ខ្មែរ', 'id' => 68],
            'ko' => ['name' => 'Korean - 한국어', 'native' => '한국어', 'id' => 69],
            'ku' => ['name' => 'Kurdish - Kurdî', 'native' => 'Kurdî', 'id' => 70],
            'ky' => ['name' => 'Kyrgyz - кыргызча', 'native' => 'кыргызча', 'id' => 71],
            'lo' => ['name' => 'Lao - ລາວ', 'native' => 'ລາວ', 'id' => 72],
            'la' => ['name' => 'Latin', 'native' => 'Latin', 'id' => 73],
            'lv' => ['name' => 'Latvian - latviešu', 'native' => 'latviešu', 'id' => 74],
            'ln' => ['name' => 'Lingala - lingála', 'native' => 'lingála', 'id' => 75],
            'lt' => ['name' => 'Lithuanian - lietuvių', 'native' => 'lietuvių', 'id' => 76],
            'mk' => ['name' => 'Macedonian - македонски', 'native' => 'македонски', 'id' => 77],
            'ms' => ['name' => 'Malay - Bahasa Melayu', 'native' => 'Bahasa Melayu', 'id' => 78],
            'ml' => ['name' => 'Malayalam - മലയാളം', 'native' => 'മലയാളം', 'id' => 79],
            'mt' => ['name' => 'Maltese - Malti', 'native' => 'Malti', 'id' => 80],
            'mr' => ['name' => 'Marathi - मराठी', 'native' => 'मराठी', 'id' => 81],
            'mn' => ['name' => 'Mongolian - монгол', 'native' => 'монгол', 'id' => 82],
            'ne' => ['name' => 'Nepali - नेपाली', 'native' => 'नेपाली', 'id' => 83],
            'no' => ['name' => 'Norwegian - norsk', 'native' => 'norsk', 'id' => 84],
            'nb' => ['name' => 'Norwegian Bokmål - norsk bokmål', 'native' => 'norsk bokmål', 'id' => 85],
            'nn' => ['name' => 'Norwegian Nynorsk - nynorsk', 'native' => 'nynorsk', 'id' => 86],
            'oc' => ['name' => 'Occitan', 'native' => 'Occitan', 'id' => 87],
            'or' => ['name' => 'Oriya - ଓଡ଼ିଆ', 'native' => 'ଓଡ଼ିଆ', 'id' => 88],
            'om' => ['name' => 'Oromo - Oromoo', 'native' => 'Oromoo', 'id' => 89],
            'ps' => ['name' => 'Pashto - پښتو', 'native' => 'پښتو', 'id' => 90],
            'fa' => ['name' => 'Persian - فارسی', 'native' => 'فارسی', 'id' => 91],
            'pl' => ['name' => 'Polish - polski', 'native' => 'polski', 'id' => 92],
            'pt' => ['name' => 'Portuguese - português', 'native' => 'português', 'id' => 93],
            'pt-BR' => ['name' => 'Portuguese (Brazil) - português (Brasil)', 'native' => 'português (Brasil)', 'id' => 94],
            'pt-PT' => ['name' => 'Portuguese (Portugal) - português (Portugal)', 'native' => 'português (Portugal)', 'id' => 95],
            'pa' => ['name' => 'Punjabi - ਪੰਜਾਬੀ', 'native' => 'ਪੰਜਾਬੀ', 'id' => 96],
            'qu' => ['name' => 'Quechua', 'native' => 'Quechua', 'id' => 97],
            'ro' => ['name' => 'Romanian - română', 'native' => 'română', 'id' => 98],
            'mo' => ['name' => 'Romanian (Moldova) - română (Moldova)', 'native' => 'română (Moldova)', 'id' => 99],
            'rm' => ['name' => 'Romansh - rumantsch', 'native' => 'rumantsch', 'id' => 100],
            'ru' => ['name' => 'Russian - русский', 'native' => 'русский', 'id' => 101],
            'gd' => ['name' => 'Scottish Gaelic', 'native' => 'Scottish Gaelic', 'id' => 102],
            'sr' => ['name' => 'Serbian - српски', 'native' => 'српски', 'id' => 103],
            'sh' => ['name' => 'Serbo - Croatian', 'native' => 'Croatian', 'id' => 104],
            'sn' => ['name' => 'Shona - chiShona', 'native' => 'chiShona', 'id' => 105],
            'sd' => ['name' => 'Sindhi', 'native' => 'Sindhi', 'id' => 106],
            'si' => ['name' => 'Sinhala - සිංහල', 'native' => 'සිංහල', 'id' => 107],
            'sk' => ['name' => 'Slovak - slovenčina', 'native' => 'slovenčina', 'id' => 108],
            'sl' => ['name' => 'Slovenian - slovenščina', 'native' => 'slovenščina', 'id' => 109],
            'so' => ['name' => 'Somali - Soomaali', 'native' => 'Soomaali', 'id' => 110],
            'st' => ['name' => 'Southern Sotho', 'native' => 'Southern Sotho', 'id' => 111],
            'es' => ['name' => 'Spanish - español', 'native' => 'español', 'id' => 112],
            'es-AR' => ['name' => 'Spanish (Argentina) - español (Argentina)', 'native' => 'español (Argentina)', 'id' => 113],
            'es-419' => ['name' => 'Spanish (Latin America) - español (Latinoamérica)', 'native' => 'español (Latinoamérica)', 'id' => 114],
            'es-MX' => ['name' => 'Spanish (Mexico) - español (México)', 'native' => 'español (México)', 'id' => 115],
            'es-ES' => ['name' => 'Spanish (Spain) - español (España)', 'native' => 'español (España)', 'id' => 116],
            'es-US' => ['name' => 'Spanish (United States) - español (Estados Unidos)', 'native' => 'español (Estados Unidos)', 'id' => 117],
            'su' => ['name' => 'Sundanese', 'native' => 'Sundanese', 'id' => 118],
            'sw' => ['name' => 'Swahili - Kiswahili', 'native' => 'Kiswahili', 'id' => 119],
            'sv' => ['name' => 'Swedish - svenska', 'native' => 'svenska', 'id' => 120],
            'tg' => ['name' => 'Tajik - тоҷикӣ', 'native' => 'тоҷикӣ', 'id' => 121],
            'ta' => ['name' => 'Tamil - தமிழ்', 'native' => 'தமிழ்', 'id' => 122],
            'tt' => ['name' => 'Tatar', 'native' => 'Tatar', 'id' => 123],
            'te' => ['name' => 'Telugu - తెలుగు', 'native' => 'తెలుగు', 'id' => 124],
            'th' => ['name' => 'Thai - ไทย', 'native' => 'ไทย', 'id' => 125],
            'ti' => ['name' => 'Tigrinya - ትግርኛ', 'native' => 'ትግርኛ', 'id' => 126],
            'to' => ['name' => 'Tongan - lea fakatonga', 'native' => 'lea fakatonga', 'id' => 127],
            'tr' => ['name' => 'Turkish - Türkçe', 'native' => 'Türkçe', 'id' => 128],
            'tk' => ['name' => 'Turkmen', 'native' => 'Turkmen', 'id' => 129],
            'tw' => ['name' => 'Twi', 'native' => 'Twi', 'id' => 130],
            'uk' => ['name' => 'Ukrainian - українська', 'native' => 'українська', 'id' => 131],
            'ur' => ['name' => 'Urdu - اردو', 'native' => 'اردو', 'id' => 132],
            'ug' => ['name' => 'Uyghur', 'native' => 'Uyghur', 'id' => 133],
            'uz' => ['name' => 'Uzbek - o‘zbek', 'native' => 'o‘zbek', 'id' => 134],
            'vi' => ['name' => 'Vietnamese - Tiếng Việt', 'native' => 'Tiếng Việt', 'id' => 135],
            'wa' => ['name' => 'Walloon - wa', 'native' => 'wa', 'id' => 136],
            'cy' => ['name' => 'Welsh - Cymraeg', 'native' => 'Cymraeg', 'id' => 137],
            'fy' => ['name' => 'Western Frisian', 'native' => 'Western Frisian', 'id' => 138],
            'xh' => ['name' => 'Xhosa', 'native' => 'Xhosa', 'id' => 139],
            'yi' => ['name' => 'Yiddish', 'native' => 'Yiddish', 'id' => 140],
            'yo' => ['name' => 'Yoruba - Èdè Yorùbá', 'native' => 'Èdè Yorùbá', 'id' => 141],
            'zu' => ['name' => 'Zulu - isiZulu', 'native' => 'isiZulu', 'id' => 142],
        ];

        return collect($languages)->map(fn (array $item, string $key) => [
            'name' => $item['name'],
            'code' => $key,
        ])->values();
    }
}
