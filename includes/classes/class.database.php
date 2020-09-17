<?php
/**
 * DesertCore CMS
 * https://desertcore.com/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2018-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 * 
 * Donate to the Project:
 * https://desertcore.com/donate
 * 
 * Contribute:
 * https://github.com/lautaroangelico/DesertCore
 * 
 * ----------------------------------------------------------------
 * 
 * Chevereto
 * http://chevereto.com/
 * @version	2.6.0
 * @author	Rodolfo Berríos A. <http://rodolfoberrios.com/>
 * 		<inbox@rodolfoberrios.com>
 * Copyright (c) Rodolfo Berrios <inbox@rodolfoberrios.com>
 * 
 * Licensed under the MIT license
 * https://opensource.org/licenses/MIT
 */

class database {
	
	public $error;
	public $ok;
	public $dead;
	
	function __construct($SQLHOST, $SQLPORT, $SQLDB, $SQLUSER, $SQLPWD, $SQLDRIVER) {
		try {
			
			if($SQLDRIVER == 3) {
				$this->db = new PDO("odbc:Driver={SQL Server};Server=".$SQLHOST.";Database=".$SQLDB."; Uid=".$SQLUSER.";Pwd=".$SQLPWD.";");
			} else {
				if($SQLDRIVER == 2) {
					$pdo_connect = "sqlsrv:Server=".$SQLHOST.",".$SQLPORT.";Database=".$SQLDB."";
				} else {
					$pdo_connect = 'dblib:host='.$SQLHOST.':'.$SQLPORT.';dbname='.$SQLDB;
				}
				$this->db = new PDO($pdo_connect, $SQLUSER, $SQLPWD, array(PDO::ATTR_TIMEOUT => 15));
			}

			
		} catch (PDOException $e) {
			$this->dead = true;
			$this->error = "PDOException: ".$e->getMessage();
		}
		
	}
	
	public function query($sql, $array=array()) {
		if(!is_array($array)) $array = array($array);
		$query = $this->db->prepare($sql);
		if (!$query) {
			$this->error = $this->trow_error();
			$query->closeCursor();
			return false;
		} else {
			if($query->execute($array)) {
				$query->closeCursor();
				return true;
			} else {
				$this->error = $this->trow_error($query);
				return false;
			}
		}
	}
	
	public function queryFetch($sql, $array=array()) {
		if(!is_array($array)) $array = array($array);
		$query = $this->db->prepare($sql);
		if (!$query) {
			$this->error = $this->trow_error();
			$query->closeCursor();
			return false;
		} else {
			if($query->execute($array)) {
				$result = $query->fetchAll(PDO::FETCH_ASSOC);
				$query->closeCursor();
				return (check($result)) ? $result : NULL;
			} else {
				$this->error = $this->trow_error($query);
				return false;
			}
		}
	}
	
	public function queryFetchSingle($sql, $array=array()) {
		$result = $this->queryFetch($sql, $array);
		return (isset($result[0])) ? $result[0] : NULL;
	}
	
	private function trow_error($state='') {
		if(!check($state)) {
			$error = $this->db->errorInfo();
		} else {
			$error = $state->errorInfo();
		}
		return '[SQL '.$error[0].'] ['.$this->db->getAttribute(PDO::ATTR_DRIVER_NAME).' '.$error[1].'] > '.$error[2];
	}

}