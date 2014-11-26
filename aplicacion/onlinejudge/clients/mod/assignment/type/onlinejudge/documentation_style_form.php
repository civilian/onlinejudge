<?php
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
//                      Online UV Judge for Moodle                       //
//              https://github.com/civilian/onlinejudge                  //
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
 * Documentation management form
 * 
 * @package   local_online_uv_judge
 * @author    Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");
require_once("$CFG->dirroot/mod/assignment/type/onlinejudge/assignment.class.php");

class documentation_form extends moodleform {

    var $testcasecount;
    var $language;

    function documentation_form($testcasecount, $language) {
        $this->testcasecount = $testcasecount;
        $this->language = $language;
        parent::moodleform();
    }

    function definition() {
        //TODO: Implentar los diferentes estilos por clases
        $this->cpp_style();
//            $method=$this->language."_syle()";
//        if ($this->language === "cpp" || $this->language === "java") {
//            $this->cpp_style();
//        } else if ($this->language === "scheme") {
////                $this->scheme_style();
//            $this->cpp_style();
//        }
    }

    private function cpp_style() {
        global $CFG, $COURSE, $cm, $id, $DBDOM, $USER;;

        $mform = & $this->_form; // Don't forget the underscore!
        $repeatarray = array();
        $repeatarray[] = &$mform->createElement('header', 'testcases', get_string('documentatiostyle', 'assignment_onlinejudge') . ' {no}');
        $repeatarray[] = &$mform->createElement('text', 'name', get_string('name', 'assignment_onlinejudge'));
        $repeatarray[] = &$mform->createElement('checkbox', 'author', get_string('requireauthortag', 'assignment_onlinejudge'));
        $repeatarray[] = &$mform->createElement('checkbox', 'version', get_string('requireversiontag', 'assignment_onlinejudge'));
        $repeatarray[] = &$mform->createElement('checkbox', 'copyright', get_string('requirecopyrighttag', 'assignment_onlinejudge'));
        $repeatarray[] = &$mform->createElement('checkbox', 'license', get_string('requirelicensetag', 'assignment_onlinejudge'));
        $repeatarray[] = &$mform->createElement('checkbox', 'package', get_string('requirepackagetag', 'assignment_onlinejudge'));
        //TODO: poder calificar param y return de los metodos
//                $repeatarray[] = &$mform->createElement('checkbox', 'param', get_string('usefile', 'assignment_onlinejudge'));
//                $repeatarray[] = &$mform->createElement('checkbox', 'return', get_string('usefile', 'assignment_onlinejudge'));
//                $repeatarray[] = &$mform->createElement('checkbox', 'see', get_string('usefile', 'assignment_onlinejudge'));
        //TODO: poder calificar param y return de los metodos publicos o protected
//                $repeatarray[] = &$mform->createElement('checkbox', 'param_pub', get_string('usefile', 'assignment_onlinejudge'));
//                $repeatarray[] = &$mform->createElement('checkbox', 'return_pub', get_string('usefile', 'assignment_onlinejudge'));

        $repeatarray[] = &$mform->createElement('hidden', 'styleid', -1);
        $repeatarray[] = &$mform->createElement('hidden', 'owner', $USER->id);

        $repeateloptions = array();
        $repeateloptions['name']['type'] = PARAM_RAW;
        $repeateloptions['styleid']['type'] = PARAM_INT;
        $repeateloptions['owner']['type'] = PARAM_INT;
        $repeateloptions['name']['helpbutton'] = array('name', 'assignment_onlinejudge');
        
        $repeateloptions['name']['disabledif'] = array('owner', 'neq' , $USER->id);
        $repeateloptions['author']['disabledif'] = array('owner', 'neq' , $USER->id);
        $repeateloptions['version']['disabledif'] = array('owner', 'neq' , $USER->id);
        $repeateloptions['copyright']['disabledif'] = array('owner', 'neq' , $USER->id);
        $repeateloptions['license']['disabledif'] = array('owner', 'neq' , $USER->id);
        $repeateloptions['package']['disabledif'] = array('owner', 'neq' , $USER->id);

        $repeatnumber = max($this->testcasecount + 1, 3);
        $this->repeat_elements($repeatarray, $repeatnumber, $repeateloptions, 'boundary_repeats', 'add_testcases', 1, get_string('adddocumentationstyles', 'assignment_onlinejudge', 1), true);

        
        $choices = $DBDOM->q('KEYVALUETABLE SELECT documentation_styleid, name FROM documentation_style
				 WHERE langid = %s ORDER BY name', $this->language);
        $mform->addElement('select', 'select_docum', get_string('documentationstyleassignment', 'assignment_onlinejudge'), $choices);
        
        require_once($CFG->dirroot . '/lib/questionlib.php'); //for get_grade_options()
        $choices_grade = get_grade_options()->gradeoptions; // Steal from question lib
        $mform->addElement('select', 'select_grade', get_string('gradefordocumentation', 'assignment_onlinejudge'), $choices_grade);
        $mform->setDefault('select_grade', /*isset($onlinejudge) ? $onlinejudge->cpulimit : 4*/ 0.2);
        
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addGroup($buttonarray, 'sel', '', array(' '), false);

        $mform->closeHeaderBefore('select_docum');
    }

    function scheme_style() {
        
    }

}

