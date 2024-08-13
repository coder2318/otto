<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

function _D_clean($var, $maxDepth = 1)
{
    $return = null;
    $isObj = is_object($var);

    if (is_array($var)) {
        $return = [];
        foreach ($var as $k => $v) {
            $return[$k] = _D_clean($v, $maxDepth);
        }
    } elseif ($maxDepth) {
        if ($isObj) {
            if ($var instanceof \DateTime) {
                $return = $var->format('c');
            } else {
                $reflClass = new \ReflectionClass(get_class($var));
                $return = new \stdclass();

                if (get_class($var) == 'stdClass') {
                    $nextDepth = $maxDepth;
                } else {
                    $nextDepth = $maxDepth - 1;
                    $return->{'__CLASS__'} = get_class($var);
                }

                $excludeProperties = [];

                if ($var instanceof \Illuminate\Database\Eloquent\Model) {
                    foreach ($reflClass->getProperties() as $reflProperty) {
                        $name = $reflProperty->getName();
                        if (in_array($name, ['original'])) {
                            $reflProperty->setAccessible(true);
                            $return->$name = _D_clean($reflProperty->getValue($var), $maxDepth - 1);
                        }
                    }
                } else {
                    foreach ((array) $var as $name => $sub) {
                        if (! in_array($name, $excludeProperties)) {
                            if (ord(((string) $name)[0]) != 0) {
                                $return->$name = _D_clean($sub, $nextDepth);
                            }
                        }
                    }
                }
            }
        } else {
            $return = $var;
        }
    } else {
        $return = $isObj ? get_class($var) : $var;
    }

    return $return;
}

function _D()
{
    echo "<meta charset='utf-8' />";
    echo "<pre style='position:relative;z-index:100000;background:white;border:1px solid #eee;'>";

    $trace = debug_backtrace();
    $loc = $trace[0];
    echo $loc['file'] . ' (' . $loc['line'] . ")\n";

    $args = func_get_args();
    foreach ($args as $i => $val) {
        $val = _D_clean($val);
        $s = print_r($val, true);
        if ($i > 0) {
            echo "\n";
        }
        echo htmlentities($s, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_DISALLOWED);
    }
    echo '</pre>';
}

function getFontData(string $fontName, string $fontPath): array
{
    $fontData = [];

    try {
        $files = File::files($fontPath);

        foreach ($files as $file) {
            $filename = $file->getFilename();

            if (stripos($filename, 'regular') !== false) {
                $fontData[$fontName]['R'] = $filename;
            } elseif (stripos($filename, 'bolditalic') !== false) {
                $fontData[$fontName]['BI'] = $filename;
            } elseif (stripos($filename, 'bold') !== false) {
                $fontData[$fontName]['B'] = $filename;
            } elseif (stripos($filename, 'italic') !== false) {
                $fontData[$fontName]['I'] = $filename;
            }
        }
    } catch (\Exception $e) {
        Log::error("Failed to retrieve font data at {$fontPath}: " . $e->getMessage());
        return [];
    }

    return $fontData;
}
