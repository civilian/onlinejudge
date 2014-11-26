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

class local_onlinejudge_change_grades_testcase extends local_onlinejudge_base_testcase {

    function test_change_grades() {
        global $DB, $CFG, $DBDOM;
        $this->resetAfterTest(true);
        set_config('maxcpulimit', 10, 'local_onlinejudge');

        /** @var local_onlinejudge_generator $generator */           
        $generator = new assignment_oj_generator();
        $assignment = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.6, 'documentation_grade_worth' => 0.2, 'indentation_grade_worth' => 0.2,
            'ratiope'=> 0.5));
        $this->assertEquals(1, $DB->count_records('assignment_oj'));
        
        //TESTCASES ENTERING
        $context = context_module::instance($assignment->cm->id);
        $oj = $DB->get_record('assignment_oj', array('assignment' => $assignment->id));
        $testcase->input = 'hello';
        $testcase->output = 'hello';
        $testcase->feedback = ONLINEJUDGE_STATUS_ACCEPTED;
        $testcase->subgrade = 0.5;
        $testcase->usefile = false;
        $testcase->id = -1;
        $case = $generator->add_testcase($assignment, $context, $oj, $testcase);
        //SECOND TESTCASE
        $case->input = 'hello';
        $case->output = 'hello';
        $case->subgrade = 0.5;
        $case->id = -1;
        $case1 = $generator->add_testcase($assignment, $context, $oj, $case);
        $this->assertEquals(2, $DB->count_records('assignment_oj_testcases'));
       
        //SUBMISSION SENDING
        $judgeclass = 'judge_ideone';
        if (!$judgeclass::is_available()) {
            // skip unavailable judge
            return;
        }
        
        //SENDING A SUBMISSION
        $files= array(
            '/i.h' => '
/*  @author peterxD
    @version pruebas
    @license expensive
*/
#define STRING "hello"

void print(void);
',
            '/main.c' => '
/*  @author peterxD
    @version pruebas
    @license expensive
*/
#include <stdio.h>
#include "i.h"

int main (void)
{
  print ();
  return 0;
}
',
            '/print.c' => '
#include "i.h"
#include <stdio.h>
/*  @author peterxD
    @version pruebas
    @license expensive
*/
void print (void)
{
  printf (STRING);
}
');
      
//        ONLINEJUDGE_STATUS_ACCEPTED);
        $this->setUser($this->students[0]);
        $sub = $generator->add_submission($assignment, $assignment->cm, $this->course, $files);
        $this->assertEquals(2, $DB->count_records('assignment_oj_submissions'));// 2 one for each testcase

        judge_all_unjudged();
        $submission = $assignment->get_submission($this->students[0]->id);
        $onlinejudge_result = $assignment->get_onlinejudge_result($submission);
        
        $oj_submission = $DB->get_record('assignment_oj_submissions', 
            array('submission' => $submission->id, 'latest' => 1), '*', IGNORE_MULTIPLE);
        $row = $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission->dom_submission, 1);
        // Indentation
        $indentation_grade = floor($row['indentation_grade'] * 100);
        $this->assertGreaterThanOrEqual(99, $indentation_grade);
        // documentation
        $documentation_grade = floor($row['documentation_grade'] * 100);
        $this->assertGreaterThanOrEqual(99, $documentation_grade);
        
        $this->assertEquals(judge_ideone::$status_judge[$row['result']], ONLINEJUDGE_STATUS_ACCEPTED);
        $submission_graded = $assignment->get_submission($this->students[0]->id);
        $this->assertGreaterThanOrEqual(99, $submission_graded->grade);
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
        
        $assignment->change_indentation_grade($submission, 0.96);
        $assignment->change_documentation_grade($submission, 0.96);
        $row = $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission->dom_submission, 1);
        // Indentation
        $indentation_grade = floor($row['indentation_grade'] * 100);
        $this->assertEquals(96, $indentation_grade);
        // documentation
        $documentation_grade = floor($row['documentation_grade'] * 100);
        $this->assertEquals(96, $documentation_grade);
        $this->assertEquals(judge_ideone::$status_judge[$row['result']], ONLINEJUDGE_STATUS_ACCEPTED);
        $submission_graded = $assignment->get_submission($this->students[0]->id);
        $this->assertGreaterThanOrEqual(98, $submission_graded->grade);
        
        foreach (judge_ideone::$status_judge as $key => $status) {
            $assignment->change_testcasesgrade_grade($submission, $status);
            $submission_graded = $assignment->get_submission($this->students[0]->id);
            switch ($key) {
                case "correct":
                    $suposed_grade = 100;
                    break;
                case "presentation-error":
                    $suposed_grade = 70;
                    break;
               default:
                    $suposed_grade = 40;
            }
            $this->assertGreaterThanOrEqual($submission_graded->grade, $suposed_grade);
        }
        $row = $DBDOM->q('MAYBETUPLE SELECT result FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission->dom_submission, 1);
        
        foreach ($onlinejudge_result->testcases as $case) {
            if (!is_null($case)) {
                $this->assertEquals($case->status, judge_ideone::$status_judge[$row['result']]);
            }
        }
    }

}