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

class local_onlinejudge_accepted_java_testcase extends local_onlinejudge_base_testcase {

    function test_accepted() {
        global $DB, $CFG, $DBDOM;
        $this->resetAfterTest(true);
        set_config('maxcpulimit', 10, 'local_onlinejudge');

        /** @var local_onlinejudge_generator $generator */           
        $generator = new assignment_oj_generator();
        $assignment = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.6, 'documentation_grade_worth' => 0.2, 'indentation_grade_worth' => 0.2,
            'language' => 'java'));
        $this->assertEquals(1, $DB->count_records('assignment_oj'));
        //TESTCASES ENTERING
        $context = context_module::instance($assignment->cm->id);
        $oj = $DB->get_record('assignment_oj', array('assignment' => $assignment->id));
        $testcase = new stdClass();
        $testcase->input = 'hello';
        $testcase->output = 'hello';
        $testcase->feedback = ONLINEJUDGE_STATUS_ACCEPTED;
        $testcase->subgrade = 0.5;
        $testcase->usefile = false;
        $testcase->id = -1;
        $case = $generator->add_testcase($assignment, $context, $oj, $testcase);
        //SECOND TESTCASE
        $case->input = 'CHANCHAN';
        $case->output = 'CHANCHAN';
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
        $files = array(
            '/i.java' => '
import java.util.Scanner;
/* @author Osq
        @package pruebas
*/
public class i
{
  public Scanner sc;
}
',
            '/mai.java' => '
import java.util.Scanner;
public class mai
{
/* @author Osq
        @package pruebas
*/
  public static void main (String[]args)
  {
    i object_i = new i ();
      object_i.sc = new Scanner (System.in);
      print.print (object_i.sc.next ());
  }
}
',
            '/print.java' => '
/* @author civili 
        @package pruebas
*/
public class print
{
  static public void print (String s)
  {
    System.out.print (s);
  }
}
'
        );
//        ONLINEJUDGE_STATUS_ACCEPTED);
        $this->setUser($this->students[0]);
        $sub = $generator->add_submission($assignment, $assignment->cm, $this->course, $files);
        $this->assertEquals(2, $DB->count_records('assignment_oj_submissions'));// 2 one for each testcase
        
        /*$row = $DBDOM->q('MAYBETUPLE SELECT documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %s AND j.valid = %s ', $oj_submission->dom_submission, 1);
        $oj_submission = $DB->get_record('assignment_oj_submissions', array('submission' => $submission->id, 'latest' => 1), '*', IGNORE_MULTIPLE);
        */
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
        $this->assertGreaterThanOrEqual(50, $indentation_grade);
        // documentation
        $documentation_grade = floor($row['documentation_grade'] * 100);
        $this->assertGreaterThanOrEqual(30, $documentation_grade);
        $this->assertEquals(judge_ideone::$status_judge[$row['result']], ONLINEJUDGE_STATUS_ACCEPTED);
        $submission_graded = $assignment->get_submission($this->students[0]->id);
        $this->assertGreaterThanOrEqual(60, $submission_graded->grade);
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