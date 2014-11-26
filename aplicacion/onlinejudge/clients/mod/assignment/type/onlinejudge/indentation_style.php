<?php

///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
//                      Online UV Judge for Moodle                       //
//        https://bitbucket.org/civilian/uv_moodle_judge/src             //
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
 * @author    Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../../../config.php');
require_once("$CFG->dirroot/mod/assignment/lib.php"); //TODO: para que lo incluyo?
require_once('indentation_style_form.php');
require_once('management_lib.php');

$id = optional_param('id', 0, PARAM_INT);  // Course Module ID
$a = optional_param('a', 0, PARAM_INT);   // Assignment ID

$url = new moodle_url('/mod/assignment/type/onlinejudge/indentation_style.php');
if ($id) {
    if (!$cm = get_coursemodule_from_id('assignment', $id)) {
        print_error('invalidcoursemodule');
    }

    if (!$assignment = $DB->get_record("assignment", array("id" => $cm->instance))) {
        print_error('invalidid', 'assignment');
    }

    if (!$course = $DB->get_record("course", array("id" => $assignment->course))) {
        print_error('coursemisconf', 'assignment');
    }
    $url->param('id', $id);
} else {
    if (!$assignment = $DB->get_record("assignment", array("id" => $a))) {
        print_error('invalidid', 'assignment');
    }
    if (!$course = $DB->get_record("course", array("id" => $assignment->course))) {
        print_error('coursemisconf', 'assignment');
    }
    if (!$cm = get_coursemodule_from_instance("assignment", $assignment->id, $course->id)) {
        print_error('invalidcoursemodule');
    }
    $url->param('a', $a);
}

$PAGE->set_url($url);
require_login($course, true, $cm);

global $context;
$context = context_module::instance($cm->id);
//require_capability('mod/assignment:grade', $context); //para chequear si el usuario tiene esta capacidad

$oj = $DB->get_record('assignment_oj', array('assignment' => $assignment->id), '*', MUST_EXIST);

$res = $DBDOM->q("SELECT * FROM indentation_style WHERE langid = %s", $oj->language);
$styleform = new style_form($res->count(), $oj->language);

if ($styleform->is_cancelled()) {
    redirect($CFG->wwwroot . '/mod/assignment/view.php?id=' . $id);
} else if ($fromform = $styleform->get_data()) {
    indentation_styles_manage($fromform, $assignment, $oj);
    redirect($CFG->wwwroot . '/mod/assignment/view.php?id=' . $id);
} else {
    $assignmentinstance = new assignment_onlinejudge($cm->id, $assignment, $cm, $course);
    $assignmentinstance->view_header();
    $toformdata = fill_indentation_styles($assignment, $context, $oj);
    $styleform->set_data($toformdata);
    $styleform->display();
    $assignmentinstance->view_footer();
}
