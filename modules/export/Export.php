<?php
/**
 * 
 * @author sbuckpesch
 *
 */
class Export {
	
	private $db; // DB connection
	
	/**
	 * 
	 * @param Zend DB Connection $db Zenda database connection
	 */
	function __construct($db) {
		$this->db = $db;
	}
	
	/**
	 * Returns the submitted query as csv-file
	 * @param String $sql SQL Query, the result you want to get back as csv-file
	 */
	public function query_to_csv($sql) {
		try {
			$result = $this->db->fetchAll($sql);
			$this->array_to_csv($result);
			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	
	/**
	 * 
	 * @param Array $arr_data Data array to download as csv-file
	 * @param Array $arr_title Title row for the data. This needs to have the same amount of columns as the $arr_data 
	 * @param string $filename file name of the export csv-file
	 * @return boolean
	 */
	public function array_to_csv($arr_data, $arr_title = false, $filename = 'export.csv') {
		$csv_terminated = "\n";
		$csv_separator = ";";
		$csv_enclosed = '"';
		$csv_escaped = "\\";
	
		$fields_cnt = count($arr_data[0]);
		$schema_insert = '';
		
		if ( $arr_title ) {
			foreach($arr_title as $title)
			{
				$l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
						stripslashes($title)) . $csv_enclosed;
				$schema_insert .= $l;
				$schema_insert .= $csv_separator;
			} // end for
		}
	
		$out = trim(substr($schema_insert, 0, -1));
		$out .= $csv_terminated;
	
		// Format the data
		foreach ($arr_data as $row) {
			$schema_insert = '';
			$j = 1;
			foreach ($row as $field) {
				if ($field == '0' || $field != ''){
					if ($csv_enclosed == ''){
						$schema_insert .= $field;
					} else {
						$schema_insert .= $csv_enclosed .
						str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $field) . $csv_enclosed;
					}
				} else {
					$schema_insert .= '';
				}
	
				if ($j < $fields_cnt) {
					$schema_insert .= $csv_separator;
					$j++;
				}
			}
	
			$out .= $schema_insert;
			$out .= $csv_terminated;
		} // end while
	
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Length: " . strlen($out));
		header("Content-type: text/x-csv;charset=utf8");
		header("Content-Disposition: attachment; filename=$filename");
		echo $out;
		return true;
	}
}