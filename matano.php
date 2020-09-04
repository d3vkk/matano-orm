<?php

session_start();

class Matano
{

    private $host, $user, $pass, $db, $tblname;


    /**
     *  Assign connection variables
     *
     * @param  string $host
     * @param  string $user
     * @param  string $pass
     * @param  string $db
     * @return void
     */
    public function __construct($host, $user, $pass, $db)
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db   = $db;

    }//end __construct()


    /**
     * Close database connection
     *
     * @uses   connect()
     * @return void
     */
    public function __destruct()
    {
        mysqli_close($this->connect());

    }//end __destruct()


    /**
     * Make connection
     *
     * @return object
     */
    public function connect()
    {
        $initconn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($initconn->connect_error) {
            die('Connection failed: '.$initconn->connect_error);
        }

        return $initconn;

    }//end connect()


    /**
     * Perform SQL operation
     *
     * @uses   connect()
     * @param  string $query
     * @return object
     */
    public function do($query)
    {
        return mysqli_query($this->connect(), $query);

    }//end do()


    /**
     * Displays error messages
     *
     * @param  array $array
     * @return void
     */
    public function errorHandler($array)
    {
        $_SESSION['error_array'] = $array;
        header('location: errorhandler.php');

    }//end errorHandler()


    /**
     * Get day from timestamp
     *
     * @param  string $column
     * @return integer
     */
    function convertTimestampToDay($column)
    {
        $preday = DateTime::createFromFormat('d-m-Y', date('d-m-Y', (strtotime($column))));
        return (int) $preday->format('d');

    }//end convertTimestampToDay()


    /*
    =============
    CREATE
    ============= */


    /**
     * Perform SQL insertion and check the affected rows
     *
     * @uses   do()
     * @uses   countTableRows()
     * @param  string $query
     * @param  string $tblname
     * @return boolean
     */
    public function insert($query, $tblname)
    {
        $rows_before = $this->countTableRows($tblname);
        $this->do($query);
        $rows_after = $this->countTableRows($tblname);

        return ($rows_after - $rows_before) == 1 ? true : false;

    }//end insert()


    /*
    =============
    READ
    ============= */


     /**
      * Get number of rows for a specific SQl query
      *
      * @uses   do()
      * @param  string $query
      * @return integer
      */
    public function countRows($query)
    {
        return mysqli_num_rows($this->do($query));

    }//end countRows()


    /**
     * Count rows in table
     *
     * @uses   do()
     * @param  string $tblname
     * @return integer
     */
    public function countTableRows($tblname)
    {
        return mysqli_num_rows($this->do("SELECT * FROM $tblname"));

    }//end countTableRows()


    /**
     * Get value of column in table
     *
     * @uses   do()
     * @param  string $rest_of_query
     * @param  string $column
     * @return any
     */
    public function getColumn($rest_of_query, $column)
    {
        return (mysqli_fetch_assoc($this->do("SELECT * FROM ".$rest_of_query)))[$column];

    }//end getColumn()


    /**
     * Get all columns for a specific SQl query
     *
     * @uses   countRows()
     * @uses   getColumn()
     * @param  string $query
     * @param  string $column
     * @return array
     */
    public function getColumns($query, $column)
    {
        $column_array = [];
        $total_rows   = $this->countRows("SELECT * FROM ".$query);
        $query        = rtrim($query, ';');
        for ($i = 0; $i < $total_rows; $i++) {
            $column_array[$i] = $this->getColumn($query." LIMIT 1 OFFSET $i;", $column);
        }

        return $column_array;

    }//end getColumns()


    /**
     * Get id
     *
     * @uses   getColumn()
     * @param  string $tblname
     * @param  string $where
     * @return integer
     */
    function getId($tblname, $where)
    {
        return (int) $this->getColumn("$tblname WHERE $where;", 'id');

    }//end getId()


    /**
     * Get the latest inserted id
     *
     * @uses   getColumn()
     * @param  string $tblname
     * @param  string $where
     * @return integer
     */
    public function latestId($tblname, $where)
    {
        if ($where != '') {
            $where = $where.' AND';
        }

        return (int) $this->getColumn("$tblname WHERE $where id = (SELECT max(id) FROM $tblname $where);", 'id');

    }//end latestId()


    /**
     * Get the latest row for a specific SQl query
     * using a date or timestamp column
     *
     * @uses   do()
     * @param  string $tblname
     * @param  string $time_column
     * @param  string $where
     * @return associative_array
     */
    public function latestRow($tblname, $time_column, $where)
    {
        return (mysqli_fetch_assoc($this->do("SELECT * FROM $tblname WHERE $where AND $time_column = (SELECT max($time_column) FROM $tblname WHERE $where);")));

    }//end latestRow()


    /*
    =============
    UPDATE
    ============= */


    /**
     * Perform SQL updation and confirm updation
     *
     * @uses   do()
     * @uses   getColumn()
     * @param  string $tblname
     * @param  string $column
     * @param  string $column_value
     * @param  string $where
     * @return boolean
     */
    public function update($tblname, $column, $column_value, $where)
    {
        if ($where != '') {
            $where = 'WHERE '.$where;
        }

        $this->do("UPDATE $tblname SET $column = '$column_value' $where;");
        return ($column_value == ($this->getColumn("$tblname $where;", $column)));

    }//end update()


    /*
    =============
    DELETE
    ============= */


    /**
     * Perform SQL deletion and confirm deletion
     *
     * @uses   do()
     * @uses   countRows()
     * @param  string $tblname
     * @param  string $where
     * @return boolean
     */
    public function delete($tblname, $where)
    {
        $this->do("DELETE FROM $tblname WHERE $where;");
        return (($this->countRows("SELECT * FROM $tblname WHERE $where;")) == 0);

    }//end delete()


    /*
    =============
    MIGRATE
    ============= */


    /**
     * Generate SQL insert statements to migrate data from one database to another
     *
     * @uses   countRows()
     * @uses   countTableRows()
     * @uses   getColumn()
     * @param  array $tables
     * @return void
     */
    public function migrate($tables)
    {
        $_SESSION['migrate_tables'] = $tables;
        header('location: migrate.php');

    }//end migrate()


}//end class
