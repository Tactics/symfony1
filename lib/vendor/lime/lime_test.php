<?php

/**
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Unit test library.
 *
 * @package    lime
 * @author     Fabien Potencier <fabien.potencier@gmail.com>
 * @version    SVN: $Id: lime.php 18665 2009-05-26 19:41:00Z fabien $
 */
class lime_test
{
    const EPSILON = 1.0E-10;
    public $plan = \null;
    public $test_nb = 0;
    public $failed = 0;
    public $passed = 0;
    public $skipped = 0;
    public $output = \null;
    public function __construct($plan = \null, $output_instance = \null)
    {
        $this->plan = $plan;
        $this->output = $output_instance ? $output_instance : new \lime_output();
        \null !== $this->plan and $this->output->echoln(\sprintf("1..%d", $this->plan));
    }
    public function __destruct()
    {
        $total = $this->passed + $this->failed + $this->skipped;
        \null === $this->plan and $this->plan = $total and $this->output->echoln(\sprintf("1..%d", $this->plan));
        if ($total > $this->plan) {
            $this->output->red_bar(\sprintf(" Looks like you planned %d tests but ran %d extra.", $this->plan, $total - $this->plan));
        } elseif ($total < $this->plan) {
            $this->output->red_bar(\sprintf(" Looks like you planned %d tests but only ran %d.", $this->plan, $total));
        }
        if ($this->failed) {
            $this->output->red_bar(\sprintf(" Looks like you failed %d tests of %d.", $this->failed, $this->passed + $this->failed));
        } else {
            if ($total == $this->plan) {
                $this->output->green_bar(" Looks like everything went fine.");
            }
        }
        \flush();
    }
    public function ok($exp, $message = '')
    {
        if ($result = (bool) $exp) {
            ++$this->passed;
        } else {
            ++$this->failed;
        }
        $this->output->echoln(\sprintf("%s %d%s", $result ? 'ok' : 'not ok', ++$this->test_nb, $message = $message ? \sprintf('%s %s', 0 === \strpos($message, '#') ? '' : ' -', $message) : ''));
        if (!$result) {
            $traces = \debug_backtrace();
            if (!empty($_SERVER['PHP_SELF'])) {
                $i = \strstr($traces[0]['file'], $_SERVER['PHP_SELF']) ? 0 : (isset($traces[1]['file']) ? 1 : 0);
            } else {
                $i = 0;
            }
            $this->output->diag(\sprintf('    Failed test (%s at line %d)', \str_replace(\getcwd(), '.', $traces[$i]['file']), $traces[$i]['line']));
        }
        return $result;
    }
    public function is($exp1, $exp2, $message = '')
    {
        if (\is_object($exp1) || \is_object($exp2)) {
            $value = $exp1 === $exp2;
        } else {
            if (\is_float($exp1) && \is_float($exp2)) {
                $value = \abs($exp1 - $exp2) < self::EPSILON;
            } else {
                $value = $exp1 == $exp2;
            }
        }
        if (!($result = $this->ok($value, $message))) {
            $this->output->diag(\sprintf("           got: %s", \var_export($exp1, \true)), \sprintf("      expected: %s", \var_export($exp2, \true)));
        }
        return $result;
    }
    public function isnt($exp1, $exp2, $message = '')
    {
        if (!($result = $this->ok($exp1 != $exp2, $message))) {
            $this->output->diag(\sprintf("      %s", \var_export($exp2, \true)), '          ne', \sprintf("      %s", \var_export($exp2, \true)));
        }
        return $result;
    }
    public function like($exp, $regex, $message = '')
    {
        if (!($result = $this->ok(\preg_match($regex, $exp), $message))) {
            $this->output->diag(\sprintf("                    '%s'", $exp), \sprintf("      doesn't match '%s'", $regex));
        }
        return $result;
    }
    public function unlike($exp, $regex, $message = '')
    {
        if (!($result = $this->ok(!\preg_match($regex, $exp), $message))) {
            $this->output->diag(\sprintf("               '%s'", $exp), \sprintf("      matches '%s'", $regex));
        }
        return $result;
    }
    public function cmp_ok($exp1, $op, $exp2, $message = '')
    {
        eval(\sprintf("\$result = \$exp1 {$op} \$exp2;"));
        if (!$this->ok($result, $message)) {
            $this->output->diag(\sprintf("      %s", \str_replace("\n", '', \var_export($exp1, \true))), \sprintf("          %s", $op), \sprintf("      %s", \str_replace("\n", '', \var_export($exp2, \true))));
        }
        return $result;
    }
    public function can_ok($object, $methods, $message = '')
    {
        $result = \true;
        $failed_messages = array();
        foreach ((array) $methods as $method) {
            if (!\method_exists($object, $method)) {
                $failed_messages[] = \sprintf("      method '%s' does not exist", $method);
                $result = \false;
            }
        }
        !$this->ok($result, $message);
        !$result and $this->output->diag($failed_messages);
        return $result;
    }
    public function isa_ok($var, $class, $message = '')
    {
        $type = \is_object($var) ? \get_class($var) : \gettype($var);
        if (!($result = $this->ok($type == $class, $message))) {
            $this->output->diag(\sprintf("      variable isn't a '%s' it's a '%s'", $class, $type));
        }
        return $result;
    }
    public function is_deeply($exp1, $exp2, $message = '')
    {
        if (!($result = $this->ok($this->test_is_deeply($exp1, $exp2), $message))) {
            $this->output->diag(\sprintf("           got: %s", \str_replace("\n", '', \var_export($exp1, \true))), \sprintf("      expected: %s", \str_replace("\n", '', \var_export($exp2, \true))));
        }
        return $result;
    }
    public function pass($message = '')
    {
        return $this->ok(\true, $message);
    }
    public function fail($message = '')
    {
        return $this->ok(\false, $message);
    }
    public function diag($message)
    {
        $this->output->diag($message);
    }
    public function skip($message = '', $nb_tests = 1)
    {
        for ($i = 0; $i < $nb_tests; $i++) {
            ++$this->skipped and --$this->passed;
            $this->pass(\sprintf("# SKIP%s", $message ? ' ' . $message : ''));
        }
    }
    public function todo($message = '')
    {
        ++$this->skipped and --$this->passed;
        $this->pass(\sprintf("# TODO%s", $message ? ' ' . $message : ''));
    }
    public function include_ok($file, $message = '')
    {
        if (!($result = $this->ok(@(include $file) == 1, $message))) {
            $this->output->diag(\sprintf("      Tried to include '%s'", $file));
        }
        return $result;
    }
    private function test_is_deeply($var1, $var2)
    {
        if (\gettype($var1) != \gettype($var2)) {
            return \false;
        }
        if (\is_array($var1)) {
            \ksort($var1);
            \ksort($var2);
            $keys1 = \array_keys($var1);
            $keys2 = \array_keys($var2);
            if (\array_diff($keys1, $keys2) || \array_diff($keys2, $keys1)) {
                return \false;
            }
            $is_equal = \true;
            foreach ($var1 as $key => $value) {
                $is_equal = $this->test_is_deeply($var1[$key], $var2[$key]);
                if ($is_equal === \false) {
                    break;
                }
            }
            return $is_equal;
        } else {
            return $var1 === $var2;
        }
    }
    public function comment($message)
    {
        $this->output->comment($message);
    }
    public function info($message)
    {
        $this->output->info($message);
    }
    public function error($message)
    {
        $this->output->error($message);
    }
    public static function get_temp_directory()
    {
        if ('\\' == \DIRECTORY_SEPARATOR) {
            foreach (array('TEMP', 'TMP', 'windir') as $dir) {
                if ($var = isset($_ENV[$dir]) ? $_ENV[$dir] : \getenv($dir)) {
                    return $var;
                }
            }
            return \getenv('SystemRoot') . '\temp';
        }
        if ($var = isset($_ENV['TMPDIR']) ? $_ENV['TMPDIR'] : \getenv('TMPDIR')) {
            return $var;
        }
        return '/tmp';
    }
}
