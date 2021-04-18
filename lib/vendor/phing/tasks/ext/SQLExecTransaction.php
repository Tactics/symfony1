<?php

/**
 * "Inner" class that contains the definition of a new transaction element.
 * Transactions allow several files or blocks of statements
 * to be executed using the same JDBC connection and commit
 * operation in between.
 */
class SQLExecTransaction
{
    private $tSrcFile = \null;
    private $tSqlCommand = "";
    private $parent;
    function __construct($parent)
    {
        // Parent is required so that we can log things ...
        $this->parent = $parent;
    }
    public function setSrc(\PhingFile $src)
    {
        $this->tSrcFile = $src;
    }
    public function addText($sql)
    {
        $this->tSqlCommand .= $sql;
    }
    /**
     * @throws IOException, SQLException
     */
    public function runTransaction($out = \null)
    {
        if (!empty($this->tSqlCommand)) {
            $this->parent->log("Executing commands", \PROJECT_MSG_INFO);
            $this->parent->runStatements(new \StringReader($this->tSqlCommand), $out);
        }
        if ($this->tSrcFile !== \null) {
            $this->parent->log("Executing file: " . $this->tSrcFile->getAbsolutePath(), \PROJECT_MSG_INFO);
            $reader = new \FileReader($this->tSrcFile);
            $this->parent->runStatements($reader, $out);
            $reader->close();
        }
    }
}
