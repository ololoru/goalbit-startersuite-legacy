<?php
/*****************************************************************************
 * validations.php : 
 *****************************************************************************
 * Copyright (C) 2008-2011 The Goalbit Team
 *
 * Authors:    Andres Barrios <andres dot barrios at goalbit-solutions dot com>
 *			   Matias Barrios <matias dot barrios at goalbit-solutions dot com>
 *			   Daniel De Vera <daniel dot de dot vera at goalbit-solutions dot com>
 * 			   Pablo Rodriguez Bocca <pablo dot rodriguez at goalbit-solutions dot com>
 *			   Claudia Rostagnol <claudia dot rostagnol at goalbit-solutions dot com>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.

 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.

 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *****************************************************************************/
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Validations {

	function genRandStr($minLen, $maxLen, $alphaLower = 1, $alphaUpper = 1, $num = 1, $batch = 1) {
		
		$alphaLowerArray = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
		$alphaUpperArray = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$numArray = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
		
		if (isset($minLen) && isset($maxLen)) {
			if ($minLen == $maxLen) {
				$strLen = $minLen;
			} else {
				$strLen = rand($minLen, $maxLen);
			}
			$merged = array_merge($alphaLowerArray, $alphaUpperArray, $numArray);
			
			if ($alphaLower == 1 && $alphaUpper == 1 && $num == 1) {
				$finalArray = array_merge($alphaLowerArray, $alphaUpperArray, $numArray);
			} elseif ($alphaLower == 1 && $alphaUpper == 1 && $num == 0) {
				$finalArray = array_merge($alphaLowerArray, $alphaUpperArray);
			} elseif ($alphaLower == 1 && $alphaUpper == 0 && $num == 1) {
				$finalArray = array_merge($alphaLowerArray, $numArray);
			} elseif ($alphaLower == 0 && $alphaUpper == 1 && $num == 1) {
				$finalArray = array_merge($alphaUpperArray, $numArray);
			} elseif ($alphaLower == 1 && $alphaUpper == 0 && $num == 0) {
				$finalArray = $alphaLowerArray;
			} elseif ($alphaLower == 0 && $alphaUpper == 1 && $num == 0) {
				$finalArray = $alphaUpperArray;                        
			} elseif ($alphaLower == 0 && $alphaUpper == 0 && $num == 1) {
				$finalArray = $numArray;
			} else {
				return FALSE;
			}
			
			$count = count($finalArray);
			
			if ($batch == 1) {
				$str = '';
				$i = 1;
				while ($i <= $strLen) {
					$rand = rand(0, $count);
					$newChar = $finalArray[$rand];
					$str .= $newChar;
					$i++;
				}
				$result = $str;
			} else {
				$j = 1;
				$result = array();
				while ($j <= $batch) { 
					$str = '';
					$i = 1;
					while ($i <= $strLen) {
						$rand = rand(0, $count);
						$newChar = $finalArray[$rand];
						$str .= $newChar;
						$i++;
					}
					$result[] = $str;
					$j++;
				}
			}
			
			return $result;
		}
	}
	function get_token()
	{
		return $this->genRandStr(32, 32);
	}
	
	function translate_password($password)
	{
		return sha1($password);
	}
	
	function find_similar($tracker_list, $channel_info_hash)
	{
		foreach($tracker_list as $index => $item)
		{
			$similar = similar_text($index, $channel_info_hash);
			if ($similar > 25)
				return $index;
		}
	}
}

?>