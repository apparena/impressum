<?php
class AA_Db {

	private $db;
	private $db_con;
	private $db_host;
	private $db_name;
	private $db_user;
	private $db_pass;


	function __construct($db_host, $db_name, $db_user, $db_pass) {
		$this->db_host = $db_host;
		$this->db_name = $db_name;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;

		// Make a MySQL Connection
		$this->db_con = mysql_connect($db_host, $db_user, $db_pass);
		if (!$this->db_con) {
			throw ('No connection to the db: ' . mysql_error());
		}

		mysql_select_db($db_name, $this->db_con);
	}

	/**
	 * Sends a query to the db and returns the complete result in an array
	 */
	function query( $sql ) {
		$sql = $this->cleanQuery($sql);
		$result = mysql_query( $sql, $this->db_con);
		$all = array ();
		if ($result) {
			while ( $row = mysql_fetch_assoc( $result ) ) {
				$all[] = $row;
			}
		} else {
			return false;
		}
		return $all;
	}
	
	/**
	 * Cleansup query to prevent SQL injections
	 */
	private function cleanQuery( $string ) {
		if(get_magic_quotes_gpc()) {
			$string = stripslashes($string);
		} if (phpversion() >= '4.3.0') {
			$string = mysql_real_escape_string($string);
		} else {
			$string = mysql_escape_string($string);
		}
		return $string;
	}
}