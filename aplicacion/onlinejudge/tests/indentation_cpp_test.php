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

class local_onlinejudge_indentation_cpp_testcase extends local_onlinejudge_base_testcase {

    function test_indentation_grade() {
        global $DB, $CFG, $DBDOM;
        $this->resetAfterTest(true);
        set_config('maxcpulimit', 10, 'local_onlinejudge');

        /** @var local_onlinejudge_generator $generator */           
        $generator = new assignment_oj_generator();
        $assignment_low_grade = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.1, 'indentation_grade_worth' => 0.8));
        $assignment_mid_grade = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.1, 'indentation_grade_worth' => 0.4));
        $assignment_perfect_grade = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.1, 'indentation_grade_worth' => 0.8));
        
        $assignment_not_compile = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.1, 'indentation_grade_worth' => 0.8));
        $assignment_weird_files = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.1, 'indentation_grade_worth' => 0.8));
        $this->assertEquals(5, $DB->count_records('assignment_oj'));
        
        //TESTCASES ENTERING
        $context_low = context_module::instance($assignment_low_grade->cm->id);
        $context_mid = context_module::instance($assignment_mid_grade->cm->id);
        $context_perfect = context_module::instance($assignment_perfect_grade->cm->id);
        $context_not_compile = context_module::instance($assignment_not_compile->cm->id);
        $context_weird_files = context_module::instance($assignment_weird_files->cm->id);
        
        $oj_low = $DB->get_record('assignment_oj', array('assignment' => $assignment_low_grade->id));
        $oj_mid = $DB->get_record('assignment_oj', array('assignment' => $assignment_mid_grade->id));
        $oj_perfect = $DB->get_record('assignment_oj', array('assignment' => $assignment_perfect_grade->id));
        $oj_not_compile = $DB->get_record('assignment_oj', array('assignment' => $assignment_not_compile->id));
        $oj_weird_files = $DB->get_record('assignment_oj', array('assignment' => $assignment_weird_files->id));
        
        $testcase->input = 'hello';
        $testcase->output = 'hello';
        $testcase->feedback = ONLINEJUDGE_STATUS_ACCEPTED;
        $testcase->subgrade = 1;
        $testcase->usefile = false;
        $testcase->id = -1;
        $generator->add_testcase($assignment_low_grade, $context_low, $oj_low, $testcase);
        $generator->add_testcase($assignment_mid_grade, $context_mid, $oj_mid, $testcase);
        $generator->add_testcase($assignment_perfect_grade, $context_perfect, $oj_perfect, $testcase);
        $generator->add_testcase($assignment_not_compile, $context_not_compile, $oj_not_compile, $testcase);
        $generator->add_testcase($assignment_weird_files, $context_weird_files, $oj_weird_files, $testcase);
        
        //CHANGING PROBLEM STYLE
        $old_style = $DBDOM->q("VALUE SELECT indentation_style FROM problem 
        WHERE probid = %s", $oj_mid->id);
        $put_style = $DBDOM->q("VALUE SELECT indentation_styleid FROM indentation_style
        WHERE name = 'kr' AND langid = %s", $oj_mid->language);
        
        $style->select_indent = $put_style;
        $generator->change_indentation_style($assignment_low_grade, $context_low, $oj_low, $style);
        $generator->change_indentation_style($assignment_mid_grade, $context_mid, $oj_mid, $style);
        $generator->change_indentation_style($assignment_perfect_grade, $context_perfect, $oj_perfect, $style);
        $generator->change_indentation_style($assignment_not_compile, $context_not_compile, $oj_not_compile, $style);
        $generator->change_indentation_style($assignment_weird_files, $context_weird_files, $oj_weird_files, $style);
 
        $new_style = $DBDOM->q("VALUE SELECT indentation_style FROM problem 
        WHERE probid = %s", $oj_perfect->id);
        $this->assertFalse($old_style == $new_style);
        $this->assertEquals($new_style, $put_style);  
        
        //SUBMISSION SENDING
        $judgeclass = 'judge_ideone';
        if (!$judgeclass::is_available()) {
            // skip unavailable judge
            return;
        }
        
        //SENDING A SUBMISSION
        $files_low = array(
            '/i.h' => ' 
    #define
STRING
    "hello"
void print(void);',
            '/main.c' => '
            #include <stdio.h>
            #include "i.h"
            int 
            main
            (void)
            { print();return 0;}',
            '/print.c' => '#include"
            i.h" 
            #include
            <stdio.h> 
            void print
            (void){printf(STRING);}');

        $files_mid = array(
            '/i.h' => '
#define STRING "hello"
void print(void);
',
            '/main.c' => '
include <stdio.h>
#include "i.h"
int main(void){
    print(); return 0;
}
',
            '/print.c' => '
#include "i.h"
#include <stdio.h>
void print(void){
    printf(STRING);}
');
        $files_perfect = array(
            '/i.h' => '

#define STRING "hello"

void print(void);
',
            '/main.c' => '

#include <stdio.h>
#include "i.h"

int main(void)
{
    print();
    return 0;
}
',
            '/print.c' => '
#include "i.h"
#include <stdio.h>
void print(void)
{
    printf(STRING);
}
');
        $files_not_compile = array(
            '/i.h' => '
/* @author Osq
        @package pruebas
*/
#define STRING "hello"

void print(void)
',
            '/main.c' => '
/* @author Osq
        @package pruebas
*/
#include <stdio.h>
#include "i.h"

int main(void)
{
    print();
    return 0;
}
',
            '/print.c' => '
#include "i.h"
#include <stdio.h>
/* @author civili 
        @package pruebas
*/
void print(void)
{
    printf(STRING);
}
');
        $files_weird_files = array(
            '/i.h' => '
/* @author Osq
        @package pruebas
*/
#define STRING "hello"

void print(void);
',
            '/main.c' => '
/* @author Osq
        @package pruebas
*/
#include <stdio.h>
#include "i.h"

int main(void)
{
    print();
    return 0;
}
',
            '/print.c' => '
#include "i.h"
#include <stdio.h>
/* @author civili 
        @package pruebas
*/
void print(void)
{
    printf(STRING);
}
',      'weird.txt' =>'
Then two female bears came out of the woods 
and tore up forty-two lads of their number.
');

        $this->setUser($this->students[0]);
        $sub = $generator->add_submission($assignment_low_grade, $assignment_low_grade->cm, $this->course, $files_low);
        $sub = $generator->add_submission($assignment_mid_grade, $assignment_mid_grade->cm, $this->course, $files_mid);
        $sub = $generator->add_submission($assignment_perfect_grade, $assignment_perfect_grade->cm, $this->course, $files_perfect);
        $sub = $generator->add_submission($assignment_not_compile, $assignment_not_compile->cm, $this->course, $files_not_compile);
        $sub = $generator->add_submission($assignment_weird_files, $assignment_weird_files->cm, $this->course, $files_weird_files);
        $this->assertEquals(5, $DB->count_records('assignment_oj_submissions'));// one for each testcase in assignment

        judge_all_unjudged();
        $submission_low = $assignment_low_grade->get_submission($this->students[0]->id);
        $submission_mid = $assignment_mid_grade->get_submission($this->students[0]->id);
        $submission_perfect = $assignment_perfect_grade->get_submission($this->students[0]->id);
        $submission_not_compile = $assignment_not_compile->get_submission($this->students[0]->id);
        $submission_weird_files = $assignment_weird_files->get_submission($this->students[0]->id);
        
        $oj_submission_low = $DB->get_record('assignment_oj_submissions', 
            array('submission' => $submission_low->id, 'latest' => 1), '*', IGNORE_MULTIPLE);
        $oj_submission_mid = $DB->get_record('assignment_oj_submissions', 
            array('submission' => $submission_mid->id, 'latest' => 1), '*', IGNORE_MULTIPLE); 
        $oj_submission_perfect = $DB->get_record('assignment_oj_submissions', 
            array('submission' => $submission_perfect->id, 'latest' => 1), '*', IGNORE_MULTIPLE);
        $oj_submission_not_compile = $DB->get_record('assignment_oj_submissions', 
            array('submission' => $submission_not_compile->id, 'latest' => 1), '*', IGNORE_MULTIPLE);
        $oj_submission_weird_files= $DB->get_record('assignment_oj_submissions', 
            array('submission' => $submission_weird_files->id, 'latest' => 1), '*', IGNORE_MULTIPLE);
            
        $row_low = $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_low->dom_submission, 1);
        $row_mid = $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_mid->dom_submission, 1);
        $row_perfect = $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_perfect->dom_submission, 1);
        $row_not_compile= $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_not_compile->dom_submission, 1);
        $row_weird_files= $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_weird_files->dom_submission, 1);
        
        // indentation
        $indentation_grade = floor($row_low['indentation_grade'] * 100);
        $this->assertEquals($indentation_grade, 75);
        
        $indentation_grade = floor($row_mid['indentation_grade'] * 100);
        $this->assertEquals($indentation_grade, 98);
        
        $indentation_grade = floor($row_perfect['indentation_grade'] * 100);
        $this->assertEquals($indentation_grade, 100);
        
        $indentation_grade = floor($row_not_compile['indentation_grade'] * 100);
        $this->assertEquals($indentation_grade, 100);
        
        $indentation_grade = floor($row_weird_files['indentation_grade'] * 100);
        $this->assertEquals($indentation_grade, 99);
        
        $submission_graded = $assignment_perfect_grade->get_submission($this->students[0]->id);
        $this->assertEquals($submission_graded->grade,90);
        $submission_graded = $assignment_low_grade->get_submission($this->students[0]->id);
        $this->assertEquals($submission_graded->grade,60);
       
       
    }
}