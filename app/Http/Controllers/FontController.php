<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class FontController extends Controller
{
    static function getFonts(): JsonResponse
    {
        $fontsDir = config('pdf.custom_font_dir');
        $fonts = [];

        if (File::exists($fontsDir)) {
            foreach (File::files($fontsDir) as $file) {
                $fontFile = basename($file);
                $fontName = pathinfo($file, PATHINFO_FILENAME);
                $fonts[] = [
                    'name' => self::formatFontName($fontName),
                    'value' => $fontName,
                    'path' => asset("storage/book/fonts/{$fontFile}"),
                ];
            }

            usort($fonts, function ($a, $b) {
                return strcasecmp($a['name'], $b['name']);
            });
        }

        return response()->json($fonts);
    }

    static function formatFontName(string $fontName): string
    {
        $formattedName = str_replace(['_', '-'], ' ', $fontName);

        $formattedName = ucwords($formattedName);

        return $formattedName;
    }

    static function getConfig(string $fontName): array
    {
        $fontPath = $fontName . '.ttf';
        $fontData[config('pdf.default_font_name')]['R'] = $fontPath;

        return [
            'custom_font_dir' => config('pdf.custom_font_dir'),
            'custom_font_data' => $fontData,
            'default_font' => config('pdf.default_font_name')
        ];
    }
}
