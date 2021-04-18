<?php

/**
 * This is a simple wrapper class to manage the lifetime of an ODBC result resource
 * (returned by odbc_exec(), odbc_execute(), etc.) We use a separate class because
 * the resource can be shared by both ODBCConnection and an ODBCResultSet at the
 * same time. ODBCConnection hangs on to the last result resource to be used in
 * its getUpdateCount() method. It also passes this resource to new instances of
 * ODBCResultSet. At some point the resource has to be cleaned up via
 * odbc_free_result(). Using this class as a wrapper, we can pass around multiple
 * references to the same resource. PHP's reference counting mechanism will clean
 * up the resource when its no longer used via ODBCResultResource::__destruct().
 * @package   creole.drivers.odbc
 */
class ODBCResultResource
{
    /**
     * @var resource ODBC result resource returned by {@link odbc_exec()}/{@link odbc_execute()}.
     */
    protected $handle = \null;
    public function __construct($handle)
    {
        if (\is_resource($handle)) {
            $this->handle = $handle;
        }
    }
    public function __destruct()
    {
        if ($this->handle !== \null) {
            @\odbc_free_result($this->handle);
        }
    }
    public function getHandle()
    {
        return $this->handle;
    }
}
