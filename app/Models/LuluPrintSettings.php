<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuluPrintSettings extends Model
{
    protected $fillable = [
        'name',
        'is_enabled',
        'book_type',
        'color_type',
        'print_type',
        'bind_type',
        'paper_type',
        'finish_type',
        'linen_type',
        'foil_type',
    ];

    public $timestamps = false;

    protected static $availableOptions = [
        'book_type' => [
            'Pocketbook' => '0425X0687',
            'Novella' => '0500X0800',
            'Digest' => '0550X0850',
            'A5' => '0583X0827',
            'US Trade' => '0600X0900',
            'Royal' => '0614X0921',
            'Comic' => '0663X1025',
            'Small Square' => '0750X0750',
            'Executive' => '0700X1000',
            'Crown Quatro' => '0744X0968',
            'Square' => '0850X0850',
            'A4' => '0827X1169',
            'US Letter' => '0850X1100',
            'Landscape' => '0900X0700',
            'US Letter Landscape' => '1100X0850',
            'A4 Landscape' => '1169X0827',
        ],
        'color_type' => [
            'Mono' => 'BW',
            'Color' => 'FC',
        ],
        'print_type' => [
            'Premium' => 'PRE',
            'Standard' => 'STD',
        ],
        'bind_type' => [
            'Perfect' => 'PB',
            'Coil' => 'CO',
            'Saddle Stitch' => 'SS',
            'Case Wrap' => 'CW',
            'Linen Wrap' => 'LW',
            'Wire O' => 'WO',
        ],
        'paper_type' => [
            '60# Uncoated White' => '060UW444',
            '60# Uncoated Cream' => '060UC444',
            '70# Coated White' => '070CW460',
            '80# Coated White' => '080CW444',
            '100# Coated White' => '100CW',
        ],
        'finish_type' => [
            'Gloss' => 'G',
            'Matte' => 'M',
            'Unlaminated' => 'U',
        ],
        'linen_type' => [
            'Red Linen' => 'R',
            'Navy Linen' => 'N',
            'Black Linen' => 'B',
            'Gray Linen' => 'G',
            'Tan Linen' => 'T',
            'Forest Linen' => 'F',
            'Interior Cover Print' => 'I',
            'N/A' => 'X',
        ],
        'foil_type' => [
            'Gold' => 'G',
            'Black' => 'B',
            'White' => 'W',
            'N/A' => 'X',
        ],
    ];

    public static function getAvailableOptions($optionType = null)
    {
        if ($optionType == null) {
            return static::$availableOptions;
        } else {
            return static::$availableOptions[$optionType] ?? [];
        }
    }

    public function getPackageId()
    {
        $result = '';
        $options = static::$availableOptions;
        foreach ($options as $fieldName => $valuesByKeys) {
            $result .= $valuesByKeys[$this->{$fieldName}];
        }

        return $result;
    }
}
