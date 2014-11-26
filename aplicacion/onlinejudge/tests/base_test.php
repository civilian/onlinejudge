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
* @author    Sun Zhigang, Oscar Chamat
* @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
/**
* Unit tests for (some of) the main features.
* @group local_onlinejudge
*/

 
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page
}

class local_onlinejudge_base_testcase extends advanced_testcase {

    /** @const Default number of students to create */
    const DEFAULT_STUDENT_COUNT = 3;
    /** @const Default number of teachers to create */
    const DEFAULT_TEACHER_COUNT = 2;
    /** @const Default number of editing teachers to create */
    const DEFAULT_EDITING_TEACHER_COUNT = 2;
    /** @const Optional extra number of students to create */
    const EXTRA_STUDENT_COUNT = 40;
    /** @const Optional number of suspended students */
    const EXTRA_SUSPENDED_COUNT = 10;
    /** @const Optional extra number of teachers to create */
    const EXTRA_TEACHER_COUNT = 5;
    /** @const Optional extra number of editing teachers to create */
    const EXTRA_EDITING_TEACHER_COUNT = 5;
    /** @const Number of groups to create */
    const GROUP_COUNT = 6;

    /** @var stdClass $course New course created to hold the assignments */
    protected $course = null;
    
    /** @var stdClass $cm New course module created to hold the assignments */
    protected $cm = null;

    /** @var array $teachers List of DEFAULT_TEACHER_COUNT teachers in the course*/
    protected $teachers = null;

    /** @var array $editingteachers List of DEFAULT_EDITING_TEACHER_COUNT editing teachers in the course */
    protected $editingteachers = null;

    /** @var array $students List of DEFAULT_STUDENT_COUNT students in the course*/
    protected $students = null;

    /** @var array $extrateachers List of EXTRA_TEACHER_COUNT teachers in the course*/
    protected $extrateachers = null;

    /** @var array $extraeditingteachers List of EXTRA_EDITING_TEACHER_COUNT editing teachers in the course*/
    protected $extraeditingteachers = null;

    /** @var array $extrastudents List of EXTRA_STUDENT_COUNT students in the course*/
    protected $extrastudents = null;

    /** @var array $extrasuspendedstudents List of EXTRA_SUSPENDED_COUNT students in the course*/
    protected $extrasuspendedstudents = null;

    /** @var array $groups List of 10 groups in the course */
    protected $groups = null;
    
    protected function setUp() {
        global $CFG, $DB, $cm;
        $this->configure_dbdom_database();
        
        // Make sure the code being tested is accessible
        require_once($CFG->dirroot . '/local/onlinejudge/judgelib.php'); // Include here to ensure set_config()
        require_once('generator/lib.php'); // Include the generator for the oj assignment
        require_once($CFG->dirroot . '/mod/assignment/type/onlinejudge/assignment.class.php'); // Include here to ensure set_config()
        require_once($CFG->dirroot . '/local/onlinejudge/cli/judged_lib.php');

        //set_config('maxmemlimit', 64, 'local_onlinejudge');
        set_config('judgehostdelay', 0, 'local_onlinejudge');
        $this->setAdminUser();//by default admin every permission       
        $this->clean_db_domjudge();
        
        $this->course = $this->getDataGenerator()->create_course();
        
        $this->teachers = array();
        for ($i = 0; $i < self::DEFAULT_TEACHER_COUNT; $i++) {
            array_push($this->teachers, $this->getDataGenerator()->create_user());
        }

        $this->editingteachers = array();
        for ($i = 0; $i < self::DEFAULT_EDITING_TEACHER_COUNT; $i++) {
            array_push($this->editingteachers, $this->getDataGenerator()->create_user());
        }

        $this->students = array();
        for ($i = 0; $i < self::DEFAULT_STUDENT_COUNT; $i++) {
            array_push($this->students, $this->getDataGenerator()->create_user());
        }

        $this->groups = array();
        for ($i = 0; $i < self::GROUP_COUNT; $i++) {
            array_push($this->groups, $this->getDataGenerator()->create_group(array('courseid'=>$this->course->id)));
        }

        $teacherrole = $DB->get_record('role', array('shortname'=>'teacher'));
        foreach ($this->teachers as $i => $teacher) {
            $this->getDataGenerator()->enrol_user($teacher->id,
                                                  $this->course->id,
                                                  $teacherrole->id);
            groups_add_member($this->groups[$i % self::GROUP_COUNT], $teacher);
        }

        $editingteacherrole = $DB->get_record('role', array('shortname'=>'editingteacher'));
        foreach ($this->editingteachers as $i => $editingteacher) {
            $this->getDataGenerator()->enrol_user($editingteacher->id,
                                                  $this->course->id,
                                                  $editingteacherrole->id);
            groups_add_member($this->groups[$i % self::GROUP_COUNT], $editingteacher);
        }

        $studentrole = $DB->get_record('role', array('shortname'=>'student'));
        foreach ($this->students as $i => $student) {
            $this->getDataGenerator()->enrol_user($student->id,
                                                  $this->course->id,
                                                  $studentrole->id);
            groups_add_member($this->groups[$i % self::GROUP_COUNT], $student);
        }
        
        $this->configure_judged();
    }
    
    function configure_judged(){
        global $LOCK, $CFG;
        $lockfile = $CFG->dataroot .'/temp/onlinejudge/lock';
        if (!check_dir_exists(dirname($lockfile))) {
            throw new moodle_exception('errorcreatingdirectory', '', '', $lockfile);
        }
        $LOCK = fopen($lockfile, 'w');
        if (!$LOCK) {
            mtrace('Can not create'.$CFG->dataroot.LOCK_FILE);
        }
    }
    
   function configure_dbdom_database(){
        global $DB, $CFG;
        //var_dump('base_test.php configure_dbdom_database');
        $tables_prefix = $DB->get_records_sql('SHOW TABLES LIKE ?', 
                                    array('%config_plugins'));
        $db_prefix;
        foreach( $tables_prefix as $key => $object){
            if( !$this->startsWith($key, $CFG->prefix)){
                $db_prefix = substr($key, 0, strlen($key) - strlen('config_plugins'));
//                var_dump($db_prefix);
            }
        }

        $name = $DB->get_record_sql('select value from '. $db_prefix .'config_plugins where plugin = ?  and name = ? ', 
                                    array('local_onlinejudge', 'judgehostdbname'));
        set_config('judgehostdbname', $name->value."_tests", 'local_onlinejudge');

        $host = $DB->get_record_sql('select value from '. $db_prefix .'config_plugins where plugin = ?  and name = ? ', 
                                    array('local_onlinejudge', 'judgehostdbhost'));
        set_config('judgehostdbhost', $host->value, 'local_onlinejudge');

        $user = $DB->get_record_sql('select value from '. $db_prefix .'config_plugins where plugin = ?  and name = ? ', 
                                    array('local_onlinejudge', 'judgehostdbuser'));
        set_config('judgehostdbuser', $user->value."_tests", 'local_onlinejudge');
        
        $pass = $DB->get_record_sql('select value from '. $db_prefix .'config_plugins where plugin = ?  and name = ? ', 
                                    array('local_onlinejudge', 'judgehostdbpass'));
        set_config('judgehostdbpass', $pass->value, 'local_onlinejudge');
        
        //var_dump(get_config('local_onlinejudge', 'judgehostdbname') );
    }


    function clean_db_domjudge() {
        global $DB, $DBDOM, $CFG;
        $tables = array('scoreboard_public','scoreboard_jury',
        'auditlog', 'event', 'balloon', 'judging_run', 'judging', 'testcase',
        'submission_file', 'submission', 'clarification', 'problem');
        $DBDOM->q('START TRANSACTION');
        foreach($tables as $table){
           $DBDOM->q("DELETE FROM $table");
           //$DBDOM->q("MAYBEVALUE ALTER TABLE $table AUTO_INCREMENT = 1;");
        }
        
        //there is not need to delete 'team_category' or 'judgehost' and it causes problems
        
        $DBDOM->q("DELETE FROM documentation_style WHERE owner != -1");
        $DBDOM->q('COMMIT');
        //$res = $DBDOM->q("SELECT * FROM documentation_style");
        //$DBDOM->q("ALTER TABLE $table AUTO_INCREMENT = $res->count()+2;");
       
       //new indentation_styles can't be created
//       $DBDOM->q("DELETE FROM indentation_style WHERE owner != -1");
    }
    
    function startsWith($haystack, $needle) {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }
    
    /* translate cr+lf (\r\n) to lf (\n) */

    function crlf2lf(&$text) {
        return strtr($text, array("\r\n" => "\n", "\n\r" => "\n"));
    }
}
