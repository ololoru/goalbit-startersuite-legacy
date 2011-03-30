<?php
/*****************************************************************************
 * Utils.php : 
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
<?php

class Utils {


    function btv_adapt_get_parameters ( &$get_params )
    {
        $info_hash_id = '';
        if ( isset($get_params['info_hash']) )
        {
            $unsigned_char_arr = unpack('C*', stripslashes($get_params['info_hash']) );
            for ( $i = 1; $i <= count($unsigned_char_arr); $i ++ )
                $info_hash_id .= sprintf('%x', $unsigned_char_arr[$i]);

        }
        $get_params['info_hash'] = $info_hash_id;

        $peer_hash_id = '';
        if ( isset($get_params['peer_id']) )
        {
            $unsigned_char_arr = unpack('C*', stripslashes($get_params['peer_id']) );
            for ( $i = 1; $i <= count($unsigned_char_arr); $i ++ )
                $peer_hash_id .= sprintf('%x', $unsigned_char_arr[$i]);
        }
        $get_params['peer_id'] = $peer_hash_id;

        $event = 'started';
        if ( isset($get_params['event']) )
        {
            $event = $get_params['event'];
        }
        $get_params['event'] = $event;

        if ( isset($get_params['qoe']) )
        {
            if ( $get_params['qoe'] < 0 )
                $get_params['qoe'] = 0.0;
            elseif ( $get_params['qoe'] > 1 )
                $get_params['qoe'] = 1.0;
            else
                settype($get_params['qoe'], "float");
        }
        else
            $get_params['qoe'] = 0.0;

        if ( $peer_info['numwant'] < 0 )
            $peer_info['numwant'] = 0;
        elseif ( $peer_info['numwant'] > MAX_PEERS_TO_RETURN )
            $peer_info['numwant'] = MAX_PEERS_TO_RETURN;

        $port_check = 0;
        if ( isset($get_params['port_check']) )
        {
            $port_check = $get_params['port_check'];
        }
        $get_params['port_check'] = $port_check;

        if ( !isset($get_params['downloaded']) || !is_numeric($get_params['downloaded']) )
            $get_params['downloaded'] = 0;

        if ( !isset($get_params['uploaded']) || !is_numeric($get_params['uploaded']) )
            $get_params['uploaded'] = 0;

        $get_params['ip'] = $_SERVER['REMOTE_ADDR'];
    }


    function check_peer_port( $peer_info )
    {
        $timeout = 2;
        $sock = @fsockopen($peer_info['ip'], $peer_info['port'], $errno, $errstr, $timeout);
        if( !$sock )
            return false;

        fclose($sock);
        return true;
    }

	
    function get_random_password($chars_min=6, $chars_max=8, $use_upper_case=false, $include_numbers=false, $include_special_chars=false)
    {
        $length = rand($chars_min, $chars_max);
        $selection = 'aeuoyibcdfghjklmnpqrstvwxz';
        if($include_numbers) {
            $selection .= "1234567890";
        }
        if($include_special_chars) {
            $selection .= "!@\"#$%&[]{}?|";
        }
                                
        $password = "";
        for($i=0; $i<$length; $i++) {
            $current_letter = $use_upper_case ? (rand(0,1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];            
            $password .=  $current_letter;
        }                
        
        return $password;
    }
}
?>