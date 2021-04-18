<?php

/**
 * "Inner" class for handling enumerations.
 * Uses build-in PHP5 iterator support.
 */
class ConditionEnumeration implements \Iterator
{
    /** Current element number */
    private $num = 0;
    /** "Outer" ConditionBase class. */
    private $outer;
    function __construct(\ConditionBase $outer)
    {
        $this->outer = $outer;
    }
    public function valid()
    {
        return $this->outer->countConditions() > $this->num;
    }
    function current()
    {
        $o = $this->outer->conditions[$this->num];
        if ($o instanceof \ProjectComponent) {
            $o->setProject($this->outer->getProject());
        }
        return $o;
    }
    function next()
    {
        $this->num++;
    }
    function key()
    {
        return $this->num;
    }
    function rewind()
    {
        $this->num = 0;
    }
}
