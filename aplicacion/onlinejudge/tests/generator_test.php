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
 * @author    Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Unit tests for (some of) the main features.
 * @group local_onlinejudge
*/
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page
}
// access to use global variables.
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
global $CFG;
require_once($CFG->dirroot . '/local/onlinejudge/tests/base_test.php');
require_once($CFG->dirroot . '/local/onlinejudge/judge/ideone/lib.php');

class local_onlinejudge_generator_testcase extends local_onlinejudge_base_testcase {

    function test_generator() {
        global $DB, $CFG, $DBDOM;
        $this->resetAfterTest(true);
        set_config('maxcpulimit', 10, 'local_onlinejudge');

        /** @var local_onlinejudge_generator $generator */           
        $generator = new assignment_oj_generator();
        $generator->create_instance(array('course'=>$this->course->id, 'grade'=>0));
        $generator->create_instance(array('course'=>$this->course->id, 'grade'=>100,
            'language' => 'java'));
        $assignment = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100));
        $this->assertEquals(3, $DB->count_records('assignment_oj'));
        $this->assertEquals(3, $DB->count_records('assignment'));
        //$this->assertEquals($this->course->id, $this->cm->course);
        
        //TESTCASES ENTERING
        $context = context_module::instance($assignment->cm->id);
        $oj = $DB->get_record('assignment_oj', array('assignment' => $assignment->id));
        $testcase->input = 'hello';
        $testcase->output = 'hello';
        $testcase->feedback = "1";
        $testcase->subgrade = 0.6;
        $testcase->usefile = false;
        $testcase->id = -1;
        $case2 = $generator->add_testcase($assignment, $context, $oj, $testcase);
        $case1 = $generator->add_testcase($assignment, $context, $oj, $testcase);
        $case = $generator->add_testcase($assignment, $context, $oj, $testcase);
        $this->assertEquals(3, $DB->count_records('assignment_oj_testcases'));
        //DELETEING TESTCASES adding a testcase with the same id but empty counts as deleting
        //like with the original forma
        $case->input = '';
        $case->output = '';
        $case->subgrade = 0.0;
        $generator->add_testcase($assignment, $context, $oj, $case);
        $this->assertEquals(2, $DB->count_records('assignment_oj_testcases'));
        //UPDATING TESTCASESand adding a testcase with the same id but different counts as updating
        //like with the original form
        $case1->input = 'a new hello';
        $case1->output = 'second bla';
        $case1->subgrade = 0.3;
        $case_updated = $generator->add_testcase($assignment, $context, $oj, $case1);
        $this->assertEquals('a new hello', $case_updated->input);
        /*$testcases = $DB->get_records('assignment_oj_testcases', array('assignment' => $assignment->id), 'sortorder ASC');
        var_dump($testcases);*/
        
        //DOCUMENTATION STYLES ENTERING
        $context = context_module::instance($assignment->cm->id);
        $oj = $DB->get_record('assignment_oj', array('assignment' => $assignment->id));
        $style->name = "new style";
        $style->author = 0;
        $style->version = 0;
        $style->copyright = 0;
        $style->license = 0;
        $style->package = 0;
        $style->styleid = -1;
        $docstyle_one = $generator->add_documentation_style($assignment, $context, $oj, $style);
        
        //can't have to styles with the same name and language
        $style->name ="different name";
        $docstyle = $generator->add_documentation_style($assignment, $context, $oj, $style);
        $res = $DBDOM->q("SELECT * FROM documentation_style WHERE langid = %s ", $oj->language);
        $this->assertEquals(3, $res->count());//inluding default style
        
        //DELETEING STYLES adding a style with the same id but empty counts as deleting
        //like with the original forma
        $docstyle->name = '';
        $generator->add_documentation_style($assignment, $context, $oj, $docstyle);
        $res = $DBDOM->q("SELECT * FROM documentation_style WHERE langid = %s ", $oj->language);
        $this->assertEquals(2, $res->count());//inluding default style
        
        //UPDATING STYLE
        //and adding a testcase with the same id but different counts as updating
        //like with the original form
        $docstyle_one->name = 'update';
        $docstyle_one->autor = 1;
        $updated = $generator->add_documentation_style($assignment, $context, $oj, $docstyle_one);
        $this->assertEquals('update', $updated->name);
        
        //CHANGING PROBLEM STYLE
        $old_style = $DBDOM->q("VALUE SELECT documentation_style FROM problem 
        WHERE probid = %s", $oj->id);
        $docstyle_one->select_docum = $docstyle_one->styleid;
        $docstyle_updated = $generator->add_documentation_style($assignment, $context, $oj, $docstyle_one);
        $new_style = $DBDOM->q("VALUE SELECT documentation_style FROM problem 
        WHERE probid = %s", $oj->id);      
        $this->assertFalse($old_style == $new_style);
        $this->assertEquals($new_style, $docstyle_one->styleid);
        
        //INDENTATION STYLES
        
        //CHANGING PROBLEM STYLE
        $context = context_module::instance($assignment->cm->id);
        $oj = $DB->get_record('assignment_oj', array('assignment' => $assignment->id));
        $old_style = $DBDOM->q("VALUE SELECT indentation_style FROM problem 
        WHERE probid = %s", $oj->id);
        $put_style = $DBDOM->q("VALUE SELECT indentation_styleid FROM indentation_style
        WHERE name = 'bsd' AND langid = %s", $oj->language);
        
        $style->select_indent = $put_style;
        $style_updated = $generator->change_indentation_style($assignment, $context, $oj, $style);
        $new_style = $DBDOM->q("VALUE SELECT indentation_style FROM problem 
        WHERE probid = %s", $oj->id);
        $this->assertFalse($old_style == $new_style);
        $this->assertEquals($new_style, $put_style);
        
        //SUBMISSION SENDING
        $judgeclass = 'judge_ideone';
        if (!$judgeclass::is_available()) {
            // skip unavailable judge
            return;
        }
        
        //SENDING A SUBMISSION
        $language = 'cpp';
        $files = array('/test.cpp' => '
#include <stdio.h>

int main(void)
{
    int c;
    while ( (c = getchar()) != EOF)
        putchar(c);
    return 0;
}
        ');
        $this->setUser($this->students[0]);
        $sub = $generator->add_submission($assignment, $assignment->cm, $this->course, $files);
        $this->assertEquals(2, $DB->count_records('assignment_oj_submissions'));// 2 one for each testcase

        judge_all_unjudged();
        $submission = $assignment->get_submission($this->students[0]->id);
        $onlinejudge_result = $assignment->get_onlinejudge_result($submission);
        
       //TODO: OBLIG una prueba de aceptación o una prueba unitaria que muestre una calificacion de indentation 0 que no muestre el null
        $oj_submission = $DB->get_record('assignment_oj_submissions', 
            array('submission' => $submission->id, 'latest' => 1), '*', IGNORE_MULTIPLE);
        $row = $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission->dom_submission, 1);
        // Indentation
        $indentation_grade = floor($row['indentation_grade'] * 100);
        $this->assertGreaterThanOrEqual(90, $indentation_grade);
        // documentation
        $documentation_grade = floor($row['documentation_grade'] * 100);
        $this->assertEquals($documentation_grade,0);
        $this->assertEquals(judge_ideone::$status_judge[$row['result']], ONLINEJUDGE_STATUS_WRONG_ANSWER);
        $submission_graded = $assignment->get_submission($this->students[0]->id);
        
        $this->assertNotEmpty($onlinejudge_result->testcases);//or assertEmpty depends the case
        foreach ($onlinejudge_result->testcases as $case) {
            if (!is_null($case)) {
                $this->assertEquals($case->status, judge_ideone::$status_judge[$row['result']]);
 //               var_dump($case->id);
                // details icon link
//                if ($onlinejudge_result->status == ONLINEJUDGE_STATUS_COMPILATION_ERROR ||
//                $onlinejudge_result->status == ONLINEJUDGE_STATUS_TIME_LIMIT_EXCEED) {}
            }
        }
    }
}
