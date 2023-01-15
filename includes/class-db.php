<?php

class DB
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                'mysql:host=devkinsta_db;dbname=Simple_CMS', // use devkinsta for host & your own db name
                'root',
                'eIQ1TKk22RcYiBA9' // insert your own password here
            );
        } catch( PDOException $error ) {
            die("Database connection failed");
        }
    }

    public static function connect()
    {
        return new self(); 
        // equal to new DB()
        // DB::connect() is eqal to new DB()
    }

    /**
     * Trigger SELECT command via PDO
     */
    public function select( $sql, $data = [], $is_list = false )
    {
        // prepare
        $statement = $this->db->prepare( $sql );
        // execute
        $statement->execute($data);
        // fetch
        // if $is_list = false then return only single record
        // if $is_list = true then return all the records
        if ( $is_list ) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
        
    }

    /**
     * Trigger INSERT INTO command via PDO
     * $sql = insert command
     * $data will be used in execute()
     */
    public function insert( $sql, $data = [] )
    {
        $statement = $this->db->prepare( $sql );
        $statement->execute( $data );
        return $this->db->lastInsertId();
    }

    /**
     * Trigger UPDATE command via PDO
     */
    public function update( $sql, $data = [])
    {
        $statement = $this->db->prepare( $sql );
        $statement->execute( $data );
        return $statement->rowCount();
    }

    /**
     * Trigger DELETE command via PDO
     */
    public function delete( $sql, $data = [])
    {
        $statement = $this->db->prepare( $sql );
        $statement->execute( $data );
        return $statement->rowCount();
    }
}