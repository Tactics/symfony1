<?php

class lime_output_color extends \lime_output
{
    public $colorizer = \null;
    public function __construct()
    {
        $this->colorizer = new \lime_colorizer();
    }
    public function diag()
    {
        $messages = \func_get_args();
        foreach ($messages as $message) {
            echo $this->colorizer->colorize('# ' . \join("\n# ", (array) $message), 'COMMENT') . "\n";
        }
    }
    public function comment($message)
    {
        echo $this->colorizer->colorize(\sprintf('# %s', $message), 'COMMENT') . "\n";
    }
    public function info($message)
    {
        echo $this->colorizer->colorize(\sprintf('> %s', $message), 'INFO_BAR') . "\n";
    }
    public function error($message)
    {
        echo $this->colorizer->colorize(\sprintf(' %s ', $message), 'RED_BAR') . "\n";
    }
    public function echoln($message, $colorizer_parameter = \null)
    {
        $colorizer = $this->colorizer;
        $message = \preg_replace_callback('/(?:^|\.)((?:not ok|dubious) *\d*)\b/', function ($matches) use ($colorizer) {
            return $colorizer->colorize($matches[1], 'ERROR');
        }, $message);
        $message = \preg_replace_callback('/(?:^|\.)(ok *\d*)\b/', function ($matches) use ($colorizer) {
            return $colorizer->colorize($matches[1], 'INFO');
        }, $message);
        $message = \preg_replace_callback('/"(.+?)"/', function ($matches) use ($colorizer) {
            return $colorizer->colorize($matches[1], 'PARAMETER');
        }, $message);
        $message = \preg_replace_callback('/(\->|\:\:)?([a-zA-Z0-9_]+?)\(\)/', function ($matches) use ($colorizer) {
            return $colorizer->colorize("{$matches[1]}{$matches[2]}()", 'PARAMETER');
        }, $message);
        echo ($colorizer_parameter ? $this->colorizer->colorize($message, $colorizer_parameter) : $message) . "\n";
    }
    public function green_bar($message)
    {
        echo $this->colorizer->colorize($message . \str_repeat(' ', 71 - \min(71, \strlen($message))), 'GREEN_BAR') . "\n";
    }
    public function red_bar($message)
    {
        echo $this->colorizer->colorize($message . \str_repeat(' ', 71 - \min(71, \strlen($message))), 'RED_BAR') . "\n";
    }
}
