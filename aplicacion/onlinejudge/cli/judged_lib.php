<?php

///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
//                      Online Judge for Moodle                          //
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
 * Helps Judging all unjudged tasks
 *
 * @package    local_online_uv_judge
 * @author     Sun Zhigang, Oscar Chamat
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/clilib.php');      // cli only functions
require_once($CFG->dirroot.'/local/onlinejudge/judgelib.php');
require_once($CFG->dirroot . '/local/onlinejudge/judge/ideone/use_db.php');

/**
 * Return one unjudged task and set it status as JUDGING
 *
 * @return an unjudged task or null;
 */
function get_one_unjudged_task() {
    global $CFG, $DB, $LOCK;

    $task = null;
    flock($LOCK, LOCK_EX); // try locking, but ignore if not available (eg. on NFS and FAT)
    try {
        if ($task = $DB->get_record('onlinejudge_tasks', array('status' => ONLINEJUDGE_STATUS_PENDING), '*', IGNORE_MULTIPLE)) {
            $DB->set_field('onlinejudge_tasks', 'status', ONLINEJUDGE_STATUS_JUDGING, array('id' => $task->id));
        }
    } catch (Exception $e) {
        flock($LOCK, LOCK_UN);
        throw $e;
    }

    flock($LOCK, LOCK_UN);

    return $task;
}

// Judge all unjudged tasks
function judge_all_unjudged() {
    while ($task = get_one_unjudged_task()) {
        verbose(cli_heading('TASK: '.$task->id, true));
        verbose('Judging...');
        try {
            $task = onlinejudge_judge($task);
            verbose("Successfully judged: $task->status");
        } catch (Exception $e) {
            $info = get_exception_info($e);
            $errmsg = "Judged inner level exception handler: ".$info->message.' Debug: '.$info->debuginfo."\n".format_backtrace($info->backtrace, true);
            cli_problem($errmsg);
            // Continue to get next unjudged task
        }
    }
}

function sigterm_handler($signo) {
    global $forcestop;
    $forcestop = true;
    verbose("Signal $signo catched");
}

function verbose($msg) {
    global $options;
    if ($options['verbose']) {
        mtrace(rtrim($msg));
    }
}