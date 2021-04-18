<?php

class TstampCustomFormat
{
    private $propertyName = "";
    private $pattern = "";
    private $locale = "";
    /**
     * The property to receive the date/time string in the given pattern
     *
     * @param propertyName the name of the property.
     */
    public function setProperty($propertyName)
    {
        $this->propertyName = $propertyName;
    }
    /**
     * The date/time pattern to be used. The values are as
     * defined by the PHP strftime() function.
     *
     * @param pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }
    /**
     * The locale used to create date/time string.
     *
     * @param locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
    /**
     * validate parameter and execute the format.
     *
     * @param TstampTask reference to task
     */
    public function execute(\TstampTask $tstamp)
    {
        if (empty($this->propertyName)) {
            throw new \BuildException("property attribute must be provided");
        }
        if (empty($this->pattern)) {
            throw new \BuildException("pattern attribute must be provided");
        }
        if (!empty($this->locale)) {
            \setlocale(\LC_ALL, $this->locale);
        }
        $value = \strftime($this->pattern);
        $tstamp->prefixProperty($this->propertyName, $value);
        if (!empty($this->locale)) {
            // reset locale
            \setlocale(\LC_ALL, \NULL);
        }
    }
}
