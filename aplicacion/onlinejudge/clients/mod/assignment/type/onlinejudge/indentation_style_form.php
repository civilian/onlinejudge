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
 * Indentation management form
 * 
 * @package   local_online_uv_judge
 * @author    Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");
require_once("$CFG->dirroot/mod/assignment/type/onlinejudge/assignment.class.php");

class style_form extends moodleform {

    var $testcasecount;
    var $language;

    function style_form($testcasecount, $language) {
        $this->testcasecount = $testcasecount;
        $this->language = $language;
        parent::moodleform();
    }

    function definition() {
        //TODO: Implentar los diferentes estilos por clases
//            $method=$this->language."_syle()";
        $this->cpp_style();
//        if ($this->language === "cpp" || $this->language === "java") {
//            $this->cpp_style();
//        } else if ($this->language === "scheme") {
//            $this->cpp_style();
//        }
    }

    function cpp_style() {
        global $CFG, $COURSE, $cm, $id, $DBDOM, $USER;

        $mform = & $this->_form; // Don't forget the underscore!

        $repeatarray = array();
        $repeatarray[] = &$mform->createElement('header', 'testcases', get_string('indentation_style', 'assignment_onlinejudge') . ' {no}');
        $repeatarray[] = &$mform->createElement('text', 'name', get_string('name', 'assignment_onlinejudge'), array('size' => 50));
        $repeatarray[] = &$mform->createElement('textarea', 'sample', get_string('sample', 'assignment_onlinejudge'), 'wrap="virtual" rows="5" cols="50"');
//                $repeatarray[] = &$mform->createElement('checkbox', 'usefile', get_string('usefile', 'assignment_onlinejudge'));
        $repeatarray[] = &$mform->createElement('hidden', 'styleid', -1);
        $repeateloptions = array();
        $repeateloptions['sample']['type'] = PARAM_RAW;
        $repeateloptions['name']['type'] = PARAM_RAW;
        $repeateloptions['styleid']['type'] = PARAM_INT;
//		$repeateloptions['testcases']['helpbutton'] =  array('testcases', 'assignment_onlinejudge');
//                $repeateloptions['sample']['disabledif'] = true;
        $repeateloptions['sample']['disabledif'] = array('styleid', 'notchecked'); //TODO: Buscar alguna opcion que sirva para actualizar el estilo
        $repeateloptions['name']['disabledif'] = array('styleid', 'notchecked');

//                $repeatnumber = max($this->testcasecount + 1, 3);
        $repeatnumber = $this->testcasecount;
//		$this->repeat_elements($repeatarray, $repeatnumber, $repeateloptions, 'boundary_repeats', 'add_testcases', 0, get_string('addtestcases', 'assignment_onlinejudge', 1), true);
        $this->repeat_elements($repeatarray, $repeatnumber, $repeateloptions, 'boundary_repeats', 'add_testcases', 0);

        //TODO: create a method in lib class judge_ideone extends judge_base for getting the styles
        $choices = $DBDOM->q('KEYVALUETABLE SELECT indentation_styleid, name FROM indentation_style
				 WHERE langid = "' . $this->language . '" ORDER BY name');
        $mform->addElement('select', 'select_indent', get_string('indentationstyleassignment', 'assignment_onlinejudge'), $choices);

        require_once($CFG->dirroot . '/lib/questionlib.php'); //for get_grade_options()
        $choices_grade = get_grade_options()->gradeoptions; // Steal from question lib
        $mform->addElement('select', 'select_grade', get_string('gradeforindentation', 'assignment_onlinejudge'), $choices_grade);
        $mform->setDefault('select_grade', /* isset($onlinejudge) ? $onlinejudge->cpulimit : 4 */ 0.2);

        $buttonarray = array();

        $context = context_module::instance($cm->id);
        if (has_capability('mod/assignment:grade', $context)) {
            $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        }
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('select_indent');
    }

    function scheme_style() {
        
    }

}

