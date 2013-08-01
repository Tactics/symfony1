<?php
/*
 *  $Id: MSSQLSRVStatement.php 447 2009-01-15 13:58:21Z jupeter $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://creole.phpdb.org>.
 */

require_once 'creole/common/StatementCommon.php';
require_once 'creole/Statement.php';

/**
 * Class that contains MSSQL functionality for Statements.
 *
 * @author   Piotr Plenik <piotr.plenik@teamlab.pl>
 * @version  $Revision: 447 $
 * @package  creole.drivers.mssqlsrv
 */
class MSSQLSRVStatement extends StatementCommon implements Statement {

    /**
     * Executes the SQL query in this PreparedStatement object and returns the resultset generated by the query.
     *
     * @param string $sql This method may optionally be called with the SQL statement.
     * @param int $fetchmode The mode to use when fetching the results (e.g. ResultSet::FETCHMODE_NUM, ResultSet::FETCHMODE_ASSOC).
     * @return object Creole::ResultSet
     * @throws SQLException If there is an error executing the specified query.
     */
    public function executeQuery($sql, $fetchmode = null)
    {
        $this->updateCount = null;
        $this->resultSet = $this->conn->executeQuery($sql, $fetchmode);
        $this->resultSet->_setOffset($this->offset);
        $this->resultSet->_setLimit($this->limit);
        return $this->resultSet;
    }


    /**
     * Gets next result set (if this behavior is supported by driver).
     * Some drivers (e.g. MSSQL) support returning multiple result sets -- e.g.
     * from stored procedures.
     *
     * This function also closes any current restult set.
     *
     * Default behavior is for this function to return false.  Driver-specific
     * implementations of this class can override this method if they actually
     * support multiple result sets.
     *
     * @return boolean True if there is another result set, otherwise false.
     */
    public function getMoreResults()
    {
        if ($this->resultSet) $this->resultSet->close();
        $this->resultSet = null;
        return false;
    }

}
