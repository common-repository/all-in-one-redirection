<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
	This is a CSV class used to handle ".csv" files.
	=====================================================================================
	
	DESCRIPTION				:	Important: The constructor is using "$mode" parameters which is used
								the purpose of using this file. Possible values are:
								R	->	This will tell that this file is used for only reading.(file opened with "r")
								W	->	This will tell that this file is used for only writing.(file opened with "w")
								RW	->	This will tell that this file is used for only reading & writing.(file opened with "a+")
*/

class CSV {
	var $fp;
	var $filename;
	var $sept;
	
	// Open new CSV file with file object
	function CSV($fname = "", $mode = "R", $sept = ",") {
		if(isset($fname) && $fname != "" && isset($mode) && $mode != ""){
			# decide open mode depending on $mode
			if($mode == "W") $filemode = "w";
			elseif($mode == "R") $filemode = "r";
			elseif($mode == "RW") $filemode = "a+";
			$this->sept = $sept;

			if(!( $this->fp = @fopen($fname, $filemode) )){
				print("ERROR : Opening " . htmlentities($fname, ENT_QUOTES) . "<BR>");
				unset($this->fp);
				return false;
			}
			else{
				fseek($this->fp, 0, SEEK_SET);
				$this->filename = $fname;
				return true;
			}
		}
		else return true;
	}
	// used to set the file with its mode
	function SetFile($fname, $mode = "R"){
		return $this->CSV($fname, $mode);
	}
	
	// Close the open file
	function Close(){
		if(isset($this->fp)) return fclose($this->fp);
		else return true;
	}
	
	// get the value from csv file based on rows
	function GetArray($flagEOL = false, $flagHeader = false){
		if($linestr = $this->GetLine($flagEOL, $flagHeader)){
			$final_array = array();

			// Now first explode with comma
			$temp_array = explode($this->sept, $linestr);

			// now with each element check for quotes condition
			for($tempi = 0; $tempi < count($temp_array); $tempi++){
				if(!empty($temp_array[$tempi]) && $temp_array[$tempi][0] == '"'){
					$cnt_quote = $this->CountQuotes($temp_array[$tempi]);

					if(( $cnt_quote % 2 ) == 0)
						array_push($final_array, $temp_array[$tempi]);
					else {
						for($tempj = $tempi; $tempj < count($temp_array); $tempj++){
							$cnt_quote_back = $this->CountQuotesBack($temp_array[$tempj]);

							if(( $cnt_quote_back % 2 ) != 0){
								// get all the elements starting from tempi upto tempj
								// and store them as a single string in array with removing
								// quotes
								$str_ln = "";
								for($tempk = $tempi; $tempk <= $tempj; $tempk++){
									if($tempk == $tempi){
										if($temp_array[$tempi][0] == '"' && $temp_array[$tempi][( strlen($temp_array[$tempi]) - 1 )] == '"'){
											$str_ln = substr($temp_array[$tempk], 1, strlen($temp_array[$tempi]) - 2);
										}
										else{
											$str_ln = substr($temp_array[$tempk], 1);
										}
									}
									elseif($tempk == $tempj)
										$str_ln .= $this->sept . substr($temp_array[$tempk], 0, -1);
									else
										$str_ln .= $this->sept . $temp_array[$tempk];
								}

								array_push($final_array, $str_ln);
								$tempi = $tempj;
								break;
							}
						}
					}
				}
				else {
					array_push($final_array, $temp_array[$tempi]);
				}
			}

			// now from all elements of final_array remove quotes where there are two quotes
			for($tempi = 0; $tempi < count($final_array); $tempi++){
				/*
					NOTE : Following line is commented because if any string is like abc""def to be import was getting like abc"def. so now if whole string is like "" then will replace with empty string and returning it.
				*/

				// only empty string within double quotes would be replaced with empty.
				if($final_array[$tempi] == '""') {
					$final_array[$tempi] = str_replace('', '', $final_array[$tempi]);
				}
			}

			return $final_array;
		}
		else
			return false;
	}

	// get the record till end of line
	function GetLine($flagEOL = false, $flagHeader = false){
		if($flagEOL && !$flagHeader) {
			$finalStr = "";
			$flag = true;
			while($flag) {
				if($infostr = fgets($this->fp, filesize($this->filename))) {
					$finalStr .= $infostr;
					if(strpos($finalStr, ",\"#EOLEOL\"")) {
						$flag = false;
						$finalStr = str_replace(",\"#EOLEOL\"", "", $finalStr);
						return trim($finalStr);
					}
					elseif(strpos($finalStr, ",#EOLEOL")) {
						$flag = false;
						$finalStr = str_replace(",#EOLEOL", "", $finalStr);
						return trim($finalStr);
					}
				}
				else {
					return false;
				}
			}
		}
		else {
			if($infostr = fgets($this->fp, filesize($this->filename))) {
				return trim($infostr);
			}
			else {
			return false;
			}
		}
	}
	
	// Add the value of rows in csv file
	function AddArray($info_array){
		if(!is_array($info_array)) print("Supplied Argument is not an array<BR>");
		else {
			for($cntid = 0; $cntid < count($info_array); $cntid++){
				$info_array[$cntid] = $this->FormatString($info_array[$cntid]);
			}

			$infostr = implode($this->sept, $info_array);
			$this->PutLine($infostr);
		}
	}
	
	//Write line in file
	function PutLine($str){
		$curr_pos = ftell($this->fp);
		fseek($this->fp, 0, SEEK_END);
		fwrite($this->fp, $str . "\r\n");
		fseek($this->fp, $curr_pos, SEEK_SET);
	}

	//set the proper format of input string
	function FormatString($str){
		$qouteFlag = true;
		$pos = strpos($str, '"');
		if($pos !== false){
			$tempstr = "";
			while(($pos = strpos($str, '"')) !== false){
				$tempstr .= substr($str, 0, $pos) . '""';
				$str = substr($str, $pos + 1);
			}

			$tempstr .= $str;
			$str = $tempstr;

			$qouteFlag = true;
		}

		$regExpStr = "/\\n|\\r\\n|\\r|\\t/";
		if(stripos($this->sept, "|") !== false) {
			$regExpStr = "/\\n|\\r\\n|\\r|\\t|\\" . $this->sept . "/";
		}
		else if(stripos($this->sept, "\\t") === false) {
			$regExpStr = "/\\n|\\r\\n|\\r|\\t|" . $this->sept . "/";
		}

		$posReg = preg_match($regExpStr, $str);
		if($qouteFlag || $posReg > 0){
			$str = '"' . $str . '"';
			//$str = '' . $str . '';
		}

		return $str;
	}
	
	// count the quotes
	function CountQuotes($str){
		for($tempi = 0; $tempi < strlen($str); $tempi++){
			if($str[$tempi] != '"')
				break;
		}

		return $tempi;
	}
	
	// count the quotes back
	function CountQuotesBack($str){
		$cnt = 0;
		for($tempi = strlen($str) - 1; $tempi >= 0; $tempi--){
			if($str[$tempi] != '"')
				break;
			else
				$cnt++;
		}

		return $cnt;
	}
}
?>