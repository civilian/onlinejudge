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
 * @package   local_online_uv_judge
 * @author    Arkaitz Garro, Sunner Sun, Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Notes assignment form
 */
defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");
require_once("$CFG->dirroot/mod/assignment/type/onlinejudge/assignment.class.php");

class documentation_form extends moodleform {

    var $language;

    function documentation_form($language) {
        $this->language = $language;
        parent::moodleform();
    }

    function definition() {
        global $CFG, $COURSE, $cm, $id;
        global $DBDOM;
        $mform = & $this->_form; // Don't forget the underscore!
        $mform->closeHeaderBefore('select_docum');
        $mform->addElement('html', "<center><img src=graph.php height='100%' width='100%'> </center>");
    }

}

