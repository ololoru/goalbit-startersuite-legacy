<?php
/*****************************************************************************
 * goalbit_js.php : 
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
function loadGoalBitPlayer(id)
{
	var element = document.getElementById(id);
	element.innerHTML = "<iframe id='" + id + "' src ='<?= base_url() ?>webplayer/player.html?url=gbtp://" + element.getAttribute("url") + "&autoplay=" + element.getAttribute("autoplay") + "&fullscreen=" + element.getAttribute("fullscreen") + "&aspectRatio=" + element.getAttribute("aspectRatio") + "&minimumAcceptVersion=" + element.getAttribute("minimumAcceptVersion") + "&maximumAcceptVersion=" + element.getAttribute("maximumAcceptVersion") + "' width=" + element.getAttribute("width") + " height = " + element.getAttribute("height") + "  frameborder=0 scrolling=no marginwidth='0px 0px' marginheight='0px 0px'></iframe>";
}
