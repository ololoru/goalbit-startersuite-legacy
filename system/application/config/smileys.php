<?php
/*****************************************************************************
 * smileys.php : 
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
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| SMILEYS
| -------------------------------------------------------------------
| This file contains an array of smileys for use with the emoticon helper.
| Individual images can be used to replace multiple simileys.  For example:
| :-) and :) use the same image replacement.
|
| Please see user guide for more info: 
| http://www.codeigniter.com/user_guide/helpers/smiley_helper.html
|
*/

$smileys = array(

//	smiley			image name						width	height	alt

	':-)'			=>	array('grin.gif',			'19',	'19',	'grin'),
	':lol:'			=>	array('lol.gif',			'19',	'19',	'LOL'),
	':cheese:'		=>	array('cheese.gif',			'19',	'19',	'cheese'),
	':)'			=>	array('smile.gif',			'19',	'19',	'smile'),
	';-)'			=>	array('wink.gif',			'19',	'19',	'wink'),
	';)'			=>	array('wink.gif',			'19',	'19',	'wink'),
	':smirk:'		=>	array('smirk.gif',			'19',	'19',	'smirk'),
	':roll:'		=>	array('rolleyes.gif',		'19',	'19',	'rolleyes'),
	':-S'			=>	array('confused.gif',		'19',	'19',	'confused'),
	':wow:'			=>	array('surprise.gif',		'19',	'19',	'surprised'),
	':bug:'			=>	array('bigsurprise.gif',	'19',	'19',	'big surprise'),
	':-P'			=>	array('tongue_laugh.gif',	'19',	'19',	'tongue laugh'),
	'%-P'			=>	array('tongue_rolleye.gif',	'19',	'19',	'tongue rolleye'),
	';-P'			=>	array('tongue_wink.gif',	'19',	'19',	'tongue wink'),
	':P'			=>	array('raspberry.gif',		'19',	'19',	'raspberry'),
	':blank:'		=>	array('blank.gif',			'19',	'19',	'blank stare'),
	':long:'		=>	array('longface.gif',		'19',	'19',	'long face'),
	':ohh:'			=>	array('ohh.gif',			'19',	'19',	'ohh'),
	':grrr:'		=>	array('grrr.gif',			'19',	'19',	'grrr'),
	':gulp:'		=>	array('gulp.gif',			'19',	'19',	'gulp'),
	'8-/'			=>	array('ohoh.gif',			'19',	'19',	'oh oh'),
	':down:'		=>	array('downer.gif',			'19',	'19',	'downer'),
	':red:'			=>	array('embarrassed.gif',	'19',	'19',	'red face'),
	':sick:'		=>	array('sick.gif',			'19',	'19',	'sick'),
	':shut:'		=>	array('shuteye.gif',		'19',	'19',	'shut eye'),
	':-/'			=>	array('hmm.gif',			'19',	'19',	'hmmm'),
	'>:('			=>	array('mad.gif',			'19',	'19',	'mad'),
	':mad:'			=>	array('mad.gif',			'19',	'19',	'mad'),
	'>:-('			=>	array('angry.gif',			'19',	'19',	'angry'),
	':angry:'		=>	array('angry.gif',			'19',	'19',	'angry'),
	':zip:'			=>	array('zip.gif',			'19',	'19',	'zipper'),
	':kiss:'		=>	array('kiss.gif',			'19',	'19',	'kiss'),
	':ahhh:'		=>	array('shock.gif',			'19',	'19',	'shock'),
	':coolsmile:'	=>	array('shade_smile.gif',	'19',	'19',	'cool smile'),
	':coolsmirk:'	=>	array('shade_smirk.gif',	'19',	'19',	'cool smirk'),
	':coolgrin:'	=>	array('shade_grin.gif',		'19',	'19',	'cool grin'),
	':coolhmm:'		=>	array('shade_hmm.gif',		'19',	'19',	'cool hmm'),
	':coolmad:'		=>	array('shade_mad.gif',		'19',	'19',	'cool mad'),
	':coolcheese:'	=>	array('shade_cheese.gif',	'19',	'19',	'cool cheese'),
	':vampire:'		=>	array('vampire.gif',		'19',	'19',	'vampire'),
	':snake:'		=>	array('snake.gif',			'19',	'19',	'snake'),
	':exclaim:'		=>	array('exclaim.gif',		'19',	'19',	'excaim'),
	':question:'	=>	array('question.gif',		'19',	'19',	'question') // no comma after last item

		);

/* End of file smileys.php */
/* Location: ./system/application/config/smileys.php */