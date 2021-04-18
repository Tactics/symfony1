<?php

class lime_colorizer
{
    public static $styles = array();
    public static function style($name, $options = array())
    {
        self::$styles[$name] = $options;
    }
    public static function colorize($text = '', $parameters = array())
    {
        // disable colors if not supported (windows or non tty console)
        if (\DIRECTORY_SEPARATOR == '\\' || !\function_exists('posix_isatty') || !@\posix_isatty(\STDOUT)) {
            return $text;
        }
        static $options = array('bold' => 1, 'underscore' => 4, 'blink' => 5, 'reverse' => 7, 'conceal' => 8);
        static $foreground = array('black' => 30, 'red' => 31, 'green' => 32, 'yellow' => 33, 'blue' => 34, 'magenta' => 35, 'cyan' => 36, 'white' => 37);
        static $background = array('black' => 40, 'red' => 41, 'green' => 42, 'yellow' => 43, 'blue' => 44, 'magenta' => 45, 'cyan' => 46, 'white' => 47);
        !\is_array($parameters) && isset(self::$styles[$parameters]) and $parameters = self::$styles[$parameters];
        $codes = array();
        isset($parameters['fg']) and $codes[] = $foreground[$parameters['fg']];
        isset($parameters['bg']) and $codes[] = $background[$parameters['bg']];
        foreach ($options as $option => $value) {
            isset($parameters[$option]) && $parameters[$option] and $codes[] = $value;
        }
        return "\33[" . \implode(';', $codes) . 'm' . $text . "\33[0m";
    }
}
