<?php
	/*
	LogServer is a network resource used for proccessing, storing and displaying combat reports for the on-line game "OGame".
	Copyright (C) <2011>  <Skyline designs>.
	
	This file is part of LogServer.
	
	LogServer is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	LogServer is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with LogServer.  If not, see <http://www.gnu.org/licenses/>.
	*/
    
	// <errors_handling>
	$g_objError_ = new cError();
	function LogError($strSource, $strDescription) {
		global $g_objError_;
		$g_objError_->LogError($strSource, $strDescription);
	}
	function IsErrors() {
		global $g_objError_;
		return $g_objError_->IsErrors();
	}
	function PrintErrors() {
		global $g_objError_;
		$g_objError_->PrintErrors();
	}
	function GetErrStack() {
		global $g_objError_;
		return $g_objError_->GetErrStack();
	}
	class cError {
		private $arrErrStack = false;
		public function LogError($strSource, $strDescription) {
			$this->arrErrStack[] = array("source" => $strSource, "description" => $strDescription);
		}
		public function IsErrors() {
			$blnResult = array();
			($this->arrErrStack) ? ($blnResult = true) : ($blnResult = false);
			return $blnResult;
		}
		public function GetErrStack() {
			return $this->arrErrStack;
		}
		public function PrintErrors() {
			print_r($this->arrErrStack);
		}
	}
	// <errors_handling>
	function get_lang() {
	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		foreach(explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $value) {
			if(strpos($value, ';') !== false) {
				list($value, ) = explode(';', $value);
			}
			if(strpos($value, '-') !== false) {
				list($value, ) = explode('-', $value);
			}
			$langs[] = $value;
		}
	}
	return $langs;
}
?>
