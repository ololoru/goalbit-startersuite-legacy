<?php
/*****************************************************************************
 * btv_tracker.php : 
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

class Btv_tracker extends Controller {


    function Btv_tracker()
    {
        parent::Controller();
        $this->load->helper('url');
    }


    function announce()
    {
        $this->load->library('Utils');
        $this->utils->btv_adapt_get_parameters( $_GET );

        // Safety controls ----------------------------------------------------
        //
        if ( $_GET['info_hash'] == '' )
        {
            $data['failure_reason'] = 'Your client forgot to send your torrent\'s info_hash. Please upgrade your client.';
            $this->load->view('error', $data);
            return;
        }

        if ( $_GET['peer_id'] == '' )
        {
            $data['failure_reason'] = 'Your client forgot to send your peer\'s id. Please upgrade your client.';
            $this->load->view('error', $data);
            return;
        }

        if ( $_GET['protocol'] != GOALBIT_PROTOCOL_VERSION )
        {
            $data['failure_reason'] = 'Your client doesn\'t implement the protocol '. GOALBIT_PROTOCOL_VERSION .'. Please upgrade your client';
            $this->load->view('error', $data);
            return;
        }

        // Logic --------------------------------------------------------------
        //
        $this->load->database();
        $this->load->model('btv_channel_peer_model');

		// Maintenance operations (only for the broadcasters)
		//if ( $_GET['peer_type'] == BROADCASTER_PEER || $_GET['peer_type'] == BROADCASTER_SUPER_PEER )
			$this->btv_channel_peer_model->update_peers();

        if ( $_GET['event'] != 'stopped' )
        {
            $data['port_check'] = $this->btv_channel_peer_model->update_peer_info( $_GET );

            $peers_by_type  = $this->btv_channel_peer_model->get_peers_count_by_type( $_GET['info_hash'] );
            $max_seeder_abi = $this->btv_channel_peer_model->get_max_seeder_abi( $_GET );

            $peer_list = array();
            if ( $_GET['numwant'] > 0 )
                $peer_list = $this->btv_channel_peer_model->get_peer_list( $_GET, $peers_by_type );

            $data['peer_type']       = $_GET['peer_type'];
            $data['offset']          = ( $max_seeder_abi > ABI_MARGIN ) ? ($max_seeder_abi - ABI_MARGIN) : $max_seeder_abi;
            $data['max_abi']         = $max_seeder_abi;
            $data['broadcaster_num'] = $peers_by_type['broadcaster_num'];
            $data['superpeer_num']   = $peers_by_type['superpeer_num'];
            $data['peer_num']        = $peers_by_type['peer_num'];
            $data['interval']        = CLIENT_REQUEST_INTERVAL - CLIENT_REQUEST_VARIATION/2 + rand(0, CLIENT_REQUEST_VARIATION);
            $data['peer_list']       = $peer_list;
        }
        else
        {
            $this->btv_channel_peer_model->delete_peer_info( $_GET );

            $data['peer_type']       = $_GET['peer_type'];
            $data['offset']          = 0;
            $data['max_abi']         = 0;
            $data['broadcaster_num'] = 0;
            $data['superpeer_num']   = 0;
            $data['peer_num']        = 0;
            $data['interval']        = 0;
            $data['peer_list']       = array();
        }

        // View Display -------------------------------------------------------
        //
        $this->load->view('btv_announce', $data);
    }


    function stats( $mode = '' )
    {
        $this->load->database();
        $this->load->model('btv_channel_peer_model');

        $data  = array();
        $stats = array();

        switch ( $mode )
        {
            case 'list':
                $stats = $this->btv_channel_peer_model->get_list_stats();
                $view  = 'btv_stats_list_mode';
                break;

            case 'pump':
                $stats = $this->btv_channel_peer_model->get_pump_stats();
                $view  = 'btv_stats_pump_mode';
                break;

            case 'gall':
                $stats = $this->btv_channel_peer_model->get_gall_stats();
                $view  = 'btv_stats_pump_mode';
                break;

            case 'xml':
                $data['stats'] = $this->btv_channel_peer_model->get_list_stats();
                $view  = 'btv_stats_xml_mode';
                header("Content-type: text/xml");
                break;

            default:
                $stats = $this->btv_channel_peer_model->get_default_stats();
                $view  = 'btv_stats_default_mode';
                break;
        }

        $data['stats'] = $stats;
        $this->load->view($view, $data);
    }

}
?>