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
 * Notes assigment graphic
 * 
 * @package   local_online_uv_judge
 * @author    Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../../../config.php');
require_once("$CFG->dirroot/mod/assignment/lib.php"); //TODO: para que lo incluyo?
require_once('notes_assignment_form.php');

$id = optional_param('id', 0, PARAM_INT);  // Course Module ID
$a = optional_param('a', 0, PARAM_INT);   // Assignment ID

$url = new moodle_url('/mod/assignment/type/onlinejudge/notes_assignment.php');
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

global $context, $DBDOM;
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_capability('mod/assignment:grade', $context); //para chequear si el usuario tiene esta capacidad

$oj = $DB->get_record('assignment_oj', array('assignment' => $assignment->id), '*', MUST_EXIST);

$res = $DBDOM->q("SELECT * FROM documentation_style WHERE langid = %s", $oj->language);
$styleform = new documentation_form($res->count(), $oj->language);

if ($styleform->is_cancelled()) {

    redirect($CFG->wwwroot . '/mod/assignment/view.php?id=' . $id);
} else if ($fromform = $styleform->get_data()) {

    for ($i = 0; $i < $fromform->boundary_repeats; $i++) {
        if (emptycase($fromform, $i)) {
            if ($fromform->styleid[$i] != -1) {

                $k = array(
                    'documentation_styleid' => $fromform->styleid[$i],
                );
                $DBDOM->q("DELETE FROM documentation_style WHERE %S LIMIT 1", $k);
                $DBDOM->auditlog("documentation_style", implode(', ', $k), 'deleted');
            }
            continue;
        }

        $style->name = $fromform->name[$i];
        $style->author = $fromform->author[$i];
        $style->version = $fromform->version[$i];
        $style->copyright = $fromform->copyright[$i];
        $style->license = $fromform->license[$i];
        $style->package = $fromform->package[$i];
        $style->id = $fromform->styleid[$i];

//        $style->subgrade = $fromform->subgrade[$i];

        if ($style->id != -1) {

            $DBDOM->q("UPDATE documentation_style SET name = %s, author = %i , version  = %i, copyright = %i , license = %s, package = %i,
                                        langid = %s 
				        WHERE documentation_styleid = %i", $style->name, $style->author, $style->version, $style->copyright, $style->license, $style->package, $oj->language, $style->id);
            $DBDOM->auditlog('documentation_style', $style->id, 'updated', "documentation update");

//            $DB->update_record('assignment_oj_testcases', $style);
        } else {
//            $rank = $DBDOM->q("VALUE SELECT count(rank) FROM testcase
//	             WHERE probid = %i", $probid) + 1;

            $DBDOM->q("INSERT INTO documentation_style
			        (name,author,version,copyright,license,package, langid)
			        VALUES (%s,%i,%i,%i,%i,%i,%s)", $style->name, $style->author, $style->version, $style->copyright, $style->license, $style->package, $oj->language);
            $DBDOM->auditlog('documentation_style', $style->id, 'added', "documentation added $style->id");
        }


        unset($style);
    }

    $itemdata = array(
        'documentation_style' => $fromform->select_docum,
        'documentation_grade' => $fromform->select_grade
    );
    $prikey = array(
        'probid' => $oj->id
    );
    $DBDOM->q("UPDATE problem SET %S WHERE %S", $itemdata, $prikey);

    redirect($CFG->wwwroot . '/mod/assignment/view.php?id=' . $id);
} else {

    $assignmentinstance = new assignment_onlinejudge($cm->id, $assignment, $cm, $course);
    $assignmentinstance->view_header();

    $res = $DBDOM->q("SELECT * FROM documentation_style WHERE langid = %s", $oj->language);
//    $styles = $DB->get_records('assignment_oj_testcases', array('assignment' => $assignment->id), 'sortorder ASC');

    $toform = array();
    if ($res) {
        $i = 0;
        while ($row = $res->next()) {
            $toform["name[$i]"] = $row["name"];
            $toform["author[$i]"] = $row["author"];
            $toform["version[$i]"] = $row["version"];
            $toform["copyright[$i]"] = $row["copyright"];
            $toform["license[$i]"] = $row["license"];
            $toform["package[$i]"] = $row["package"];
            $toform["styleid[$i]"] = $row["documentation_styleid"];

//            $toform["retrn[$i]"] = $row["retrn"];//tener en cuenta que en la base de datos no se puede llamar return un campo

            $i++;
        }

        $st = $DBDOM->q("SELECT documentation_style, documentation_grade FROM problem WHERE probid = %s", $oj->id);
        $row = $st->next();
        $toform["select_docum"] = $row["documentation_style"];
        $toform["select_grade"] = $row["documentation_grade"];
    }

    $styleform->set_data($toform);
    $styleform->display();

    $assignmentinstance->view_footer();
}

