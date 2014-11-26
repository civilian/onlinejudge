<?php
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
//                      Online UV Judge for Moodle                       //
//        https://bitbucket.org/civilian/tg                              //
//                                                                       //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 3 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////

/**
 * Indentation management
 * 
 * @package   local_online_uv_judge
 * @author    Arkaitz Garro, Sunner Sun, Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Defines event handlers
 */
defined('MOODLE_INTERNAL') || die();

$handlers = array(
    'onlinejudge_task_judged' => array (
        'handlerfile'      => '/mod/assignment/type/onlinejudge/assignment.class.php',
        'handlerfunction'  => 'onlinejudge_task_judged',
        'schedule'         => 'instant'
    )
);
