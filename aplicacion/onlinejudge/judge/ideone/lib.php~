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
 * ideone.com judge engine
 */
defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . "/../../../../config.php");
require_once($CFG->dirroot . "/local/onlinejudge/judgelib.php");

/** Constant to define MySQL datetime format in strftime() function notation. */
define('MYSQL_DATETIME_FORMAT', '%Y-%m-%d %H:%M:%S');

/** Perl regex class of allowed characters in identifier strings. */
define('IDENTIFIER_CHARS', '[a-zA-Z0-9_-]');

/** Perl regex of allowed filenames. */
define('FILENAME_REGEX', '/^[a-zA-Z0-9][a-zA-Z0-9+_\.-]*$/');

require_once("use_db.php");

class judge_ideone extends judge_base {
    // Possible exitcodes from judging and their meaning.
    public static $status_judge = array(
        'correct' => ONLINEJUDGE_STATUS_ACCEPTED,
        'compiler-error' => ONLINEJUDGE_STATUS_COMPILATION_ERROR,
        'run-error' => ONLINEJUDGE_STATUS_RUNTIME_ERROR,
        'timelimit' => ONLINEJUDGE_STATUS_TIME_LIMIT_EXCEED,
        'memory-limit' => ONLINEJUDGE_STATUS_MEMORY_LIMIT_EXCEED,
        'output-limit' => ONLINEJUDGE_STATUS_OUTPUT_LIMIT_EXCEED,
        'presentation-error' => ONLINEJUDGE_STATUS_PRESENTATION_ERROR,
        'wrong-answer' => ONLINEJUDGE_STATUS_WRONG_ANSWER,
        'no-output' => ONLINEJUDGE_STATUS_WRONG_ANSWER,
        'internal-error' => ONLINEJUDGE_STATUS_INTERNAL_ERROR,
     );
    
    //TODO: update latest language list through ideone API
    protected static $supported_languages = array('C++', 'Java', 'Racket 5.3.6 \ drScheme');

    static function get_languages() {
        $langs = array();
        if (self::is_available()) {
            global $DBDOM;
            $langs = $DBDOM->q('KEYVALUETABLE SELECT langid, name FROM language
                                 WHERE allow_submit = 1 ORDER BY name');
            return $langs;
        }

//        $langs[''] = 'language';
//        
        foreach (self::$supported_languages as $langid => $name) {
            $langs[$langid] = $name;
        }
        return $langs;
    }

    /**
     * Judge the current task
     *
     * @return updated task
     */
    function judge() {
        global $DBDOM, $DB;
        $task = &$this->task;

        // create client.
//        $client = new SoapClient("http://ideone.com/api/1/service.wsdl");

        $user = $task->var1;
        $pass = $task->var2;
        $language = $this->language;
        $input = $task->input;

        //TODO: OBLIG una prueba de aceptación o una prueba unitaria con cada uno de estos estados con su combinatoria
//        define("ONLINEJUDGE_STATUS_PENDING", 0);
//        define("ONLINEJUDGE_STATUS_ABNORMAL_TERMINATION", 2);
//        define("", 7);
//        define("ONLINEJUDGE_STATUS_RESTRICTED_FUNCTIONS", 8);
//        define("ONLINEJUDGE_STATUS_MULTI_STATUS", 23);
//        104 => 'no-output',


//        $webid = $client->createSubmission($user, $pass, $source, $language, $input, true, true);
        $delay = get_config('local_onlinejudge', 'judgehostdelay');
        sleep($delay);  // ideone reject bulk access

        if ($DBDOM === NULL) {
            //TODO: Como funciona este error?, probar cosa de aceptación
            throw new onlinejudge_exception('ideoneerror', get_string('connect_error', 'local_onlinejudge'));
        }

        //$DB->insert_record('assignment_oj_submissions', array('submission' => $submission->id, 'testcase' => $test->id, 'task' => $taskid, 'latest' => 1));
        $oj_submission = $DB->get_record('assignment_oj_submissions', array('task' => $task->id));
        $judging = false;
        $judging_run;

        // Get judgehost results
        $submission = $DBDOM->q('MAYBETUPLE SELECT *
                       FROM submission
                       WHERE submitid = %i AND valid = %i', $oj_submission->dom_submission, 1);
        if ($submission) {
            do {
//            $waittime = 5
                $judging = $DBDOM->q('MAYBETUPLE SELECT *
                       FROM judging
                       WHERE submitid = %i AND valid = %i', $oj_submission->dom_submission, 1);
                sleep($delay);  // Avoid bulk access. Always add delay between accesses
            } while (!$judging || $judging["result"] === NULL);
        }
        //cuando se ejecuta cada caso simplifica la programacion bastante y los 
        //errores de recurrencia

        $judging_run = $DBDOM->q('MAYBETUPLE SELECT *
                           FROM judging_run
                           WHERE judgingid = %i AND testcaseid = %i', $judging["judgingid"], $oj_submission->testcase);

        $task->stdout = $judging_run['output_run'];
        $task->stderr = $judging_run['output_error'];
        $task->compileroutput = $judging['output_compile'];
//        $task->memusage = $details['memory'] * 1024;
        $task->cpuusage = $judging_run['runtime'];
//        $task->infoteacher = get_string('ideoneresultlink', 'local_onlinejudge', $link);//TODO: Incluir una dirección de una instalacion de domserver
//        $task->infoteacher = $task->id;
        $status = ($judging_run === NULL) ? self::$status_judge[$judging['result']] :
                self::$status_judge[$judging_run['runresult']];
        $task->infostudent = get_string('status' . $status, 'local_onlinejudge');
        $task->var1 = $judging_run['output_diff'];
//      get_string('ideonelogo', 'local_onlinejudge');

        $status = ($submission === NULL) ? ONLINEJUDGE_STATUS_INTERNAL_ERROR :
                $status;
        
        $task->status = $status;
        if ($task->compileonly) {
            if ($task->status != ONLINEJUDGE_STATUS_COMPILATION_ERROR && $task->status != ONLINEJUDGE_STATUS_INTERNAL_ERROR) {
                $task->status = ONLINEJUDGE_STATUS_COMPILATION_OK;
            }
        }
        return $task;
    }

    /**
     * Whether the judge is avaliable
     *
     * @return true for yes, false for no
     */
    static function is_available() {
        global $DBDOM;
        return $DBDOM != NULL;
    }

}

?>