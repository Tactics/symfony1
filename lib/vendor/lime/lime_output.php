<?php

class lime_output
{
    public function diag()
    {
        $messages = \func_get_args();
        foreach ($messages as $message) {
            \array_map(array($this, 'comment'), (array) $message);
        }
    }
    public function comment($message)
    {
        echo "# {$message}\n";
    }
    public function info($message)
    {
        echo "> {$message}\n";
    }
    public function error($message)
    {
        echo "> {$message}\n";
    }
    public function echoln($message)
    {
        echo "{$message}\n";
    }
    public function green_bar($message)
    {
        echo "{$message}\n";
    }
    public function red_bar($message)
    {
        echo "{$message}\n";
    }
}
