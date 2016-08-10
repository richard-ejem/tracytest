<?php

namespace RichardEjem;

/**
 * Simple BasePath and PathInfo extractor.
 * Inspired by nette/http, simplified to lightweight class.
 *
 * @author Richard Ejem <richard@ejem.cz>
 */

class PathInfo
{
    private static $basePath;

    private static $pathInfo;

    private static function unescapeScriptPath($s)
    {
        return rawurldecode(preg_replace_callback(
            '#%(' . substr(chunk_split(bin2hex('%/?#'), 2, '|'), 0, -1) . ')#i',
            function ($m) { return '%25' . strtoupper($m[1]); },
            $s
        ));
    }

    private static function fixUtf($s)
    {
        return htmlspecialchars_decode(htmlspecialchars($s, ENT_NOQUOTES | ENT_IGNORE, 'UTF-8'), ENT_NOQUOTES);
    }

    private static function parse()
    {
        if (self::$basePath !== NULL) {
            return;
        }
        $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $tmp = explode('?', $requestUrl, 2);
        $path = self::unescapeScriptPath($tmp[0]);
        $path = preg_replace('#/{2,}#', '/', $path);
        $path = self::fixUtf($path);

        // detect script path
        $lpath = strtolower($path);
        $script = isset($_SERVER['SCRIPT_NAME']) ? strtolower($_SERVER['SCRIPT_NAME']) : '';
        if ($lpath !== $script) {
            $max = min(strlen($lpath), strlen($script));
            for ($i = 0; $i < $max && $lpath[$i] === $script[$i]; $i++) {
                ;
            }
            self::$basePath = $i ? substr($path, 0, strrpos($path, '/', $i - strlen($path) - 1) + 1) : '/';
        } else {
            self::$basePath = substr($path, 0, strrpos($path, '/') + 1);
        }
        self::$pathInfo = substr($path, strlen(self::$basePath));
        if (self::$pathInfo === FALSE) {
            self::$pathInfo = '';
        }
    }

    /** @return string */
    public static function getBasePath()
    {
        self::parse();
        return self::$basePath;
    }

    /** @return string */
    public static function getPathInfo()
    {
        self::parse();
        return self::$pathInfo;
    }
}
