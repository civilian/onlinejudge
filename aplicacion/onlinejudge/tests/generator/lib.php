<?php
// this file is part of moodle - http://moodle.org/
//
// moodle is free software: you can redistribute it and/or modify
// it under the terms of the gnu general public license as published by
// the free software foundation, either version 3 of the license, or
// (at your option) any later version.
//
// moodle is distributed in the hope that it will be useful,
// but without any warranty; without even the implied warranty of
// merchantability or fitness for a particular purpose.  see the
// gnu general public license for more details.
//
// you should have received a copy of the gnu general public license
// along with moodle.  if not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

/**
 * assignment online judge module data generator class
 *
 * @category test
 * @copyright 2014 oscar chamat 
 * @license http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */

global $CFG;
// make sure the code being tested is accessible
require_once($CFG->dirroot . '/mod/assignment/type/onlinejudge/assignment.class.php'); // include here to ensure set_config()
require_once($CFG->dirroot .'/mod/assignment/type/onlinejudge/management_lib.php');
require_once($CFG->dirroot . '/mod/assignment/type/onlinejudge/testcase_form.php');
require_once($CFG->dirroot . '/mod/assignment/type/onlinejudge/documentation_style_form.php'); 
require_once($CFG->dirroot . '/mod/assignment/type/onlinejudge/indentation_style_form.php');
require_once($CFG->libdir . '/filelib.php');
        
class assignment_oj_generator extends advanced_testcase {

    function setUp()  {

    }
    
    public function create_instance($record, array $options = null) {
        global $DB;
        //var_dump('generator/lib.php create_instance(');
        
        $record = (object)(array)$record;
        
        //a module for every new assignment unless is said otherwise
        $generator_assignmet = $this->getDataGenerator()->get_plugin_generator('mod_assignment');
        $tmp_assignment = $generator_assignmet->create_instance(array('course'=> $record->course, 
            'grade'=>$record->grade, 'name'=> 'new online assignment'));
        $cm = $this->cm = get_coursemodule_from_instance('assignment', $tmp_assignment->id);

        $defaultsettings = array(
            'id'                                => $tmp_assignment->id,
            'instance'                          => $tmp_assignment->id,
            'assignmenttype'                    => 'onlinejudge',
            'type'                              => 'onlinejudge',
            'name'                              => 'new online assignment',
            'timeavailable'                     => time() - (60 * 60 * 7), //in seconds
            'timedue'                           => time() + (60 * 60 * 24 * 7),
            'preventlate'                       => 0,
            'grade'                             => $record->grade,
            'gradecat'                          => 4,
            'maxbytes'                          => 0,
            'resubmit'                          => 1,
            'var1'                              => 100,//max files accepted
            'var2'                              => 0,
            'var3'                              => 0,
            'emailteachers'                     => 0,//when someone sends a file
            'compileimportance'                 => 0,
            'groupmode'                         => 0,
            'groupingid'                        => 0,
            'coursemodule'                      => $cm->id,
            'module'                            => $cm->module,
            'modulename'                        => 'assignment',
            'add'                               => 'assignment',
            'update'                            => 0,
            'return'                            => 0,
            'intro'                             => 'assignment description',
            'language'                          => 'cpp',
            'cpulimit'                          => 6,
            'compileonly'                       => 0,
            'testcases_grade_worth'             => 0.6,
            'documentation_grade_worth'         => 0.2,
            'indentation_grade_worth'           => 0.2,
            'ratiope'                           => 0.0,
            'ideoneuser'                        => null,
            'ideonepass'                        => null,
            );
        
        foreach ($defaultsettings as $name => $value) {
            if (!isset($record->{$name})) {
                $record->{$name} = $value;
            }
        }
        //var_dump($record);
        $assignment = new assignment_onlinejudge();
        $record->id = $assignment->add_instance($record);//this id is the id of the assignment asociated
        $course = $DB->get_record('course', array('id' => $record->course));
        $ans = new assignment_onlinejudge($cm->id, $record, $cm, $course);
        $ans->id = $tmp_assignment->id;
        return $ans;
    }
    
    public function add_testcase($assignment, $context, $oj, $testcase){
        global $DB;
        $testform = new testcase_form($DB->count_records('assignment_oj_testcases', array('assignment' => $assignment->id)));
        $toform_tmp = fill_tescases($assignment, $context, $oj);
        $testform->set_data($toform_tmp);
        $toform = $testform->get_data();
        $i = $testform->testcasecount;
        //var_dump($toform);
        $toform->input[$i] = $testcase->input;
        $toform->output[$i] = $testcase->output;
        $toform->feedback[$i] = $testcase->feedback;
        $toform->subgrade[$i] = $testcase->subgrade;
        if($testcase->usefile){
            $toform->usefile[$i] = $testcase->usefile;
        }
        $toform->caseid[$i] = $testcase->id;
        file_prepare_draft_area($toform->inputfile[$i], $context->id, 'mod_assignment', 'onlinejudge_input', $testcase->id, array('subdirs' => 0, 'maxfiles' => 1));
        file_prepare_draft_area($toform->outputfile[$i], $context->id, 'mod_assignment', 'onlinejudge_output', $testcase->id, array('subdirs' => 0, 'maxfiles' => 1));
        
        $toform->boundary_repeats = $i + 1;
        $toform->select_grade = $oj->testcases_grade_worth + 0.0;
        tescases_manage($toform, $assignment, $oj);
        
        $sql = "SELECT * FROM {assignment_oj_testcases} WHERE assignment = ? AND feedback = ? AND subgrade = ? AND usefile = ? "
        ." AND ". $DB->sql_compare_text('input') . " = ? AND " . $DB->sql_compare_text('output') . " = ? "
        ." ORDER BY id DESC"; //The order helps with equal testcases but is not a guaranty getting the same we put
        
        $ans = $DB->get_record_sql($sql,  array($assignment->id, $testcase->feedback, $testcase->subgrade,
            $testcase->usefile, $testcase->input, $testcase->output), IGNORE_MULTIPLE);
        return $ans;
    }
    
    /**
    crud of documentation styles note: Remember two styles can't be name equal
    in the same languaje
    */
    public function add_documentation_style($assignment, $context, $oj, $style){
        global $DB, $DBDOM;
        //var_dump("generator/lib.php add_documentation_style");
        $res = $DBDOM->q("SELECT * FROM documentation_style WHERE langid = %s ", $oj->language);
        $styleform = new documentation_form($res->count(), $oj->language);
        $toform_tmp = fill_styles($assignment, $context, $oj);
        $styleform->set_data($toform_tmp);
        $toform = $styleform->get_data();
        $i = $styleform->testcasecount;
        //var_dump($toform);
        $toform->name[$i] = $style->name;
        $toform->author[$i] = $style->author;
        $toform->version[$i] = $style->version;
        $toform->copyright[$i] = $style->copyright;
        $toform->license[$i] = $style->license;
        $toform->package[$i] = $style->package;
        $toform->styleid[$i] = $style->styleid;
        $toform->boundary_repeats = $i + 1;
        $toform->select_grade = $oj->documentation_grade_worth + 0.0;
        $toform->select_docum = $style->select_docum;
        styles_manage($toform, $assignment, $oj);
        //maybe is used when delete
        $ans = $DBDOM->q("MAYBETUPLE SELECT *
            FROM documentation_style 
            WHERE name = %s AND langid = %s", $style->name, $oj->language);
        $object_ans;
        if($ans){
            foreach ($ans as $name => $value) {
                $object_ans->{$name} = $value;
            }
        }
        $object_ans->styleid = $object_ans->documentation_styleid;
        return $object_ans;
    }
    
    /**
    */
    public function change_indentation_style($assignment, $context, $oj, $style){
        //no need for crud in indentation yet
        global $DB, $DBDOM, $cm;
        //var_dump("generator/lib.php change_indentation_style");
        $res = $DBDOM->q("SELECT * FROM indentation_style WHERE langid = %s ", $oj->language);
        $cm = $assignment->cm;
        $styleform = new style_form($res->count(), $oj->language);
        $toform_tmp = fill_indentation_styles($assignment, $context, $oj);
        $styleform->set_data($toform_tmp);
        $toform = $styleform->get_data();
        $i = $styleform->testcasecount;
        //var_dump($toform);
        $toform->boundary_repeats = $i;
        $toform->select_grade = $oj->indentation_grade_worth + 0.0;
        $toform->select_indent = $style->select_indent;
        indentation_styles_manage($toform, $assignment, $oj);
        //maybe is used when delete
        $ans = $DBDOM->q("MAYBETUPLE SELECT *
            FROM indentation_style 
            WHERE name = %s AND langid = %s", $style->name, $oj->language);
        $object_ans;
        if($ans){
            foreach ($ans as $name => $value) {
                $object_ans->{$name} = $value;
            }
        }
        $object_ans->styleid = $object_ans->indentation_styleid;
        return $object_ans;
    }

    public function add_submission($assignment, $cm, $course, $files){
        global $USER;

        $assignment_obj = new assignment_onlinejudge($cm->id, $assignment, $cm, $course);
        $submission = $assignment_obj->get_submission($USER->id, true); //create new submission if needed
        if (!$assignment_obj->can_upload_file($submission)) {
            return null;
        }
        
        $fs = get_file_storage();
        $fs->delete_area_files($assignment_obj->context->id, 'mod_assignment', 'submission', $submission->id);
        $file_record->contextid = $assignment_obj->context->id;
        $file_record->component = 'mod_assignment';
        $file_record->filearea = 'submission';
        $file_record->itemid = $submission->id;
        foreach ($files as $key => $value) {//los convierte
            if ($value instanceof stored_file) {
                $fs->create_file_from_storedfile($file_record, $value);
            } else {
                $file_record->filepath = dirname($key);
                if (strpos($file_record->filepath, '/') !== 0) {
                    $file_record->filepath = '/' . $file_record->filepath;
                }
                if (strrpos($file_record->filepath, '/') !== strlen($file_record->filepath) - 1) {
                    $file_record->filepath .= '/';
                }
                $file_record->filename = basename($key);
                $fs->create_file_from_string($file_record, $value);
            }
        }
        
        $assignment_obj->request_judge($submission);
        $ans_submission = $assignment_obj->get_submission($USER->id);
        return $ans_submission;
    }
}