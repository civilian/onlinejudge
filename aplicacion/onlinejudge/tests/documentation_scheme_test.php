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

class local_onlinejudge_documentation_scheme_testcase extends local_onlinejudge_base_testcase {

    function test_documentation_grade() {
        global $DB, $CFG, $DBDOM;
        $this->resetAfterTest(true);
        set_config('maxcpulimit', 10, 'local_onlinejudge');

        /** @var local_onlinejudge_generator $generator */           
        $generator = new assignment_oj_generator();
        $assignment_low_grade = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.8, 'indentation_grade_worth' => 0.1,
            'language' => 'scheme'));
        $assignment_mid_grade = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.4, 'indentation_grade_worth' => 0.1,
            'language' => 'scheme'));
        $assignment_perfect_grade = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.8, 'indentation_grade_worth' => 0.1,
            'language' => 'scheme'));
        
        $assignment_not_compile = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.8, 'indentation_grade_worth' => 0.1,
            'language' => 'scheme'));
        $assignment_weird_files = $generator->create_instance(array('course'=>$this->course->id, 
            'grade'=>100, 'testcases_grade_worth' => 0.1, 'documentation_grade_worth' => 0.8, 'indentation_grade_worth' => 0.1,
            'language' => 'scheme'));
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
        
        $testcase->input = '2';
        $testcase->output = '4';
        $testcase->feedback = ONLINEJUDGE_STATUS_ACCEPTED;
        $testcase->subgrade = 1;
        $testcase->usefile = false;
        $testcase->id = -1;
        $generator->add_testcase($assignment_low_grade, $context_low, $oj_low, $testcase);
        $generator->add_testcase($assignment_mid_grade, $context_mid, $oj_mid, $testcase);
        $generator->add_testcase($assignment_perfect_grade, $context_perfect, $oj_perfect, $testcase);
        $generator->add_testcase($assignment_not_compile, $context_not_compile, $oj_not_compile, $testcase);
        $generator->add_testcase($assignment_weird_files, $context_weird_files, $oj_weird_files, $testcase);
        
        //DOCUMENTATION STYLES ENTERING
        $style->name = "new style";
        $style->author = 1;
        $style->version = 0;
        $style->copyright = 0;
        $style->license = 0;
        $style->package = 1;
        $style->styleid = -1;
        $docstyle_one = $generator->add_documentation_style($assignment_low_grade, $context_low, $oj_low, $style);
        
        //CHANGING PROBLEM STYLE
        $docstyle_one->select_docum = $docstyle_one->styleid;
        $generator->add_documentation_style($assignment_low_grade, $context_low, $oj_low, $docstyle_one);
        $generator->add_documentation_style($assignment_mid_grade, $context_mid, $oj_mid, $docstyle_one);
        $generator->add_documentation_style($assignment_perfect_grade, $context_perfect, $oj_perfect, $docstyle_one);
        $generator->add_documentation_style($assignment_not_compile, $context_not_compile, $oj_not_compile, $docstyle_one);
        $generator->add_documentation_style($assignment_weird_files, $context_weird_files, $oj_weird_files, $docstyle_one);
        
        
        //SUBMISSION SENDING
        $judgeclass = 'judge_ideone';
        if (!$judgeclass::is_available()) {
            // skip unavailable judge
            return;
        }
        
        //SENDING A SUBMISSION
         $files_low = array(
            '/c.rkt' => '
#| @version 3
        @pakage pruebas
|#
#lang racket
(provide c)
(define c 1)
',
            '/main.rkt' => '
#| @version 3
        @pakage pruebas
|#
#lang racket
(require "b.rkt" "c.rkt")
(display (+ (read)  b c) )
',
            '/b.rkt' => '
#lang racket
(provide b)
(define b 1)
'
        );
        
          $files_mid = array(
            '/c.rkt' => '
;; @author somebody

#lang racket
(provide c)
(define c 1)
',
            '/main.rkt' => '
#|
    @package pruebas
|#
#lang racket
(require "b.rkt" "c.rkt")
(display (+ (read)  b c) )
',
            '/b.rkt' => '
#| @author people
        @ pakage pruebas
|#
#lang racket
(provide b)
(define b 1)
'
        );
        
        $files_perfect = array(
            //If the tag appears twice must count whit the others
            '/c.rkt' => '
#| @author somebody
|#
#lang racket
(provide c)
(define c 1)
#| @author Osq
        @package pruebas
|#
',
            '/main.rkt' => '
#lang racket
;; @author Osq
#|        @package pruebas
|#
(require "b.rkt" "c.rkt")
(display (+ (read)  b c) )
',
            '/b.rkt' => '

#lang racket
(provide b)
#| @author civili 
        @package pruebas
|#
(define b 1)
'
        );
        
        $files_not_compile = array(
            //If the tag appears twice must count whit the others
            '/c.rkt' => ' 
#|  @author Osq
    @package pruebas
|#
#lang racket
(provide c
(define c 1)

',
            '/main.rkt' => '
#| @author Osq
    @package pruebas
|#     
#lang racket
(require "b.rkt" "c.rkt")
(display (+ (read)  b c) )
',
            '/b.rkt' => '
#| @author civili 
        @package pruebas
|#
#lang racket
(provide b)
(define b 1)
'
        );
        
        $files_weird_files = array(
            //If the tag appears twice must count whit the others
            '/c.rkt' => ' 
#|  @author Osq
    @package pruebas
|#
#lang racket
(provide c)
(define c 1)

',
            '/main.rkt' => '
#| @author Osq
    @package pruebas
|#     
#lang racket
(require "b.rkt" "c.rkt")
(display (+ (read)  b c) )
',
            '/b.rkt' => '
#| @author civili 
        @package pruebas
|#
#lang racket
(provide b)
(define b 1)
',           'weird.txt' =>'
Then two female bears came out of the woods 
and tore up forty-two lads of their number.
'
        );
 
//        ONLINEJUDGE_STATUS_ACCEPTED);
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
        
        $delay = get_config('local_onlinejudge', 'judgehostdelay');
        
        $row_low = $DBDOM->q('maybetuple select endtime, result, documentation_grade, indentation_grade from judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_low->dom_submission, 1);
        $row_mid = $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_mid->dom_submission, 1);
        $row_perfect = $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_perfect->dom_submission, 1);
        $row_not_compile= $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_not_compile->dom_submission, 1);
        $row_weird_files= $DBDOM->q('MAYBETUPLE SELECT endtime, result, documentation_grade, indentation_grade FROM judging j 
            WHERE j.submitid = %i AND j.valid = %s ', $oj_submission_weird_files->dom_submission, 1);
        // documentation
        $documentation_grade = floor($row_low['documentation_grade'] * 100);
        $this->assertEquals($documentation_grade,0);
        
        $documentation_grade = floor($row_mid['documentation_grade'] * 100);
        $this->assertEquals($documentation_grade,50);
        
        $documentation_grade = floor($row_perfect['documentation_grade'] * 100);
        $this->assertEquals($documentation_grade,100);
        
        $documentation_grade = floor($row_not_compile['documentation_grade'] * 100);
        $this->assertEquals($documentation_grade, 100);
        
        $documentation_grade = floor($row_weird_files['documentation_grade'] * 100);
        $this->assertEquals($documentation_grade,75);
        
        $submission_graded = $assignment_perfect_grade->get_submission($this->students[0]->id);
        $this->assertEquals($submission_graded->grade,99);
        $submission_graded = $assignment_low_grade->get_submission($this->students[0]->id);
        $this->assertEquals($submission_graded->grade,19);
       
       
    }
}