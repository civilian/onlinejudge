<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
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
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @copyright 2011 onwards Sun Zhigang (sunner) {@link http://sunner.cn}
 * @subpackage backup-moodle2
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/*!
 * restore subplugin class that provides the necessary information
 * needed to restore one assignment->onlinejudge subplugin.
 *
 * Note: Offline assignments really haven't any special subplugin
 * information to backup/restore, hence code below is skipped (return false)
 * but it's a good example of subplugins supported at different
 * elements (assignment and submission)
 */
require_once($CFG->dirroot . '/local/onlinejudge/judge/ideone/use_db.php');
if ($DBDOM === NULL) {
    setup_database_connection();
}

class restore_assignment_onlinejudge_subplugin extends restore_subplugin {

    /**
     * Returns the paths to be handled by the subplugin at assignment level
     */
    protected function define_assignment_subplugin_structure() {

        $paths = array();

        $elename = $this->get_namefor('onlinejudge');
        $elepath = $this->get_pathfor('/onlinejudges/onlinejudge'); // because we used get_recommended_name() in backup this works
        $paths[] = new restore_path_element($elename, $elepath);

        $elename = $this->get_namefor('testcase');
        $elepath = $this->get_pathfor('/testcases/testcase'); // because we used get_recommended_name() in backup this works
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths
    }

    /**
     * Returns the paths to be handled by the subplugin at submission level
     */
    protected function define_submission_subplugin_structure() {

        $paths = array();

        $elename = $this->get_namefor('onlinejudge_submission');
        $elepath = $this->get_pathfor('/onlinejudge_submissions/onlinejudge_submission'); // because we used get_recommended_name() in backup this works
        $paths[] = new restore_path_element($elename, $elepath);

        $elename = $this->get_namefor('task');
        $elepath = $this->get_pathfor('/onlinejudge_submissions/onlinejudge_submission/tasks/task'); // because we used get_recommended_name() in backup this works
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths
    }

    /**
     * This method processes the onlinejudge element inside one onlinejudge assignment (see onlinejudge subplugin backup)
     */
    public function process_assignment_onlinejudge_onlinejudge($data) {
        global $DB, $DBDOM;
        $data = (object) $data;
        $oldid = $data->id;
        $data->assignment = $this->get_new_parentid('assignment');
        $newitemid = $DB->insert_record('assignment_oj', $data);

        $st = $DBDOM->q("TUPLE SELECT documentation_style, indentation_style FROM problem WHERE probid = %s", $oldid);
        $assignment = $DB->get_record('assignment', array('id' => $data->assignment), '*', MUST_EXIST);

        $itemdata = array(
            'probid' => $newitemid,
            'cid' => 2,
            'name' => $assignment->name,
            'allow_submit' => 1,
            'allow_judge' => 1,
            'timelimit' => $data->cpulimit,
            'color' => "#000000",
            'special_run' => null,
            'special_compare' => null,
            'indentation_style' => $st['indentation_style'],
            'documentation_style' => $st['documentation_style'],
        );

        $DBDOM->q('RETURNID INSERT INTO problem SET %S', $itemdata);

        $this->set_mapping($this->get_namefor('onlinejudge'), $oldid, $newitemid);
    }

    /**
     * This method processes the testcase element inside one onlinejudge assignment (see onlinejudge subplugin backup)
     */
    public function process_assignment_onlinejudge_testcase($data) {
        global $DB, $DBDOM;

        $data = (object) $data;
        $oldid = $data->id;

        $data->assignment = $this->get_new_parentid('assignment');

        $newitemid = $DB->insert_record('assignment_oj_testcases', $data);
        $this->set_mapping($this->get_namefor('testcase'), $oldid, $newitemid, true);

        $this->add_related_files('mod_assignment', 'onlinejudge_input', $this->get_namefor('testcase'), null, $oldid);
        $this->add_related_files('mod_assignment', 'onlinejudge_output', $this->get_namefor('testcase'), null, $oldid);
        
        $assigment_oj = $DB->get_record('assignment_oj', array('assignment' => $data->assignment), '*', MUST_EXIST);
        
        $DBDOM->q("INSERT INTO testcase
                  (mdl_testcaseid,probid,rank,md5sum_input,md5sum_output,input,output,description)
                  VALUES (%i,%s,%i,%s,%s,%s,%s,%s)", $newitemid, $assigment_oj->id, $newitemid, md5($data->input), md5($data->output), $data->input, $data->output, $data->feedback);
        $DBDOM->auditlog('testcase', $data->assignment, 'added', "rank $newitemid");
    }

    /**
     * This method processes the task element inside one onlinejudge assignment (see onlinejudge subplugin backup)
     */
    public function process_assignment_onlinejudge_task($data) {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $data->cmid = $this->task->get_moduleid();
        $data->userid = $this->get_mappingid('user', $data->userid);

        $newitemid = $DB->insert_record('onlinejudge_tasks', $data);

        // Since process_assignment_onlinejudge_onlinejudge_submission() is called before this function,
        // we must update assignment_oj_submissions table's task by this way
        $DB->set_field('assignment_oj_submissions', 'task', $newitemid, array('task' => $oldid, 'submission' => $this->get_new_parentid('assignment_submission')));
    }

    /**
     * This method processes the onlinejudge_submission element inside one onlinejudge assignment (see onlinejudge subplugin backup)
     */
    public function process_assignment_onlinejudge_onlinejudge_submission($data) {
        global $DB, $DBDOM;

        $data = (object) $data;

        $data->testcase = $this->get_mappingid($this->get_namefor('testcase'), $data->testcase);
        $data->submission = $this->get_mappingid('assignment_submission', $data->submission);

        $exist = $DB->record_exists('assignment_oj_submissions', array('submission' => $data->submission, 'latest' => 1));

        $DB->insert_record('assignment_oj_submissions', $data);

        if (!$exist) {

            $submission = $DB->get_record('assignment_submissions', array('id' => $data->submission));
            $oj = $DB->get_record('assignment_oj', array('assignment' => $submission->assignment), '*', MUST_EXIST);
            $fs = get_file_storage();
            $files = $fs->get_area_files($this->context->id, 'mod_assignment', 'submission', $submission->id, 'sortorder, timemodified', false);

            //        $team, $prob, $lang, $files, $filenames, $origsubmitid = NULL,
            $team = $submission->userid;
            $prob = $oj->id;
            $lang = $oj->language;
            $origsubmitid = NULL;
            $filenames = array();

            foreach ($files as $file) {
                $filepath = $file->get_filepath();
                if (strpos($filepath, '/') === 0) {//le quito el primer / y pruebo
                    $filepath = substr($filepath, 1);
                }
//            if (strrpos($filepath, '/') !== strlen($filepath) - 1) {
//                $filepath .= '/';
//            }
                $filenames[] = $filepath . $file->get_filename();
            }

            if (!$login = $DBDOM->q('MAYBEVALUE SELECT login FROM team WHERE login = %s', $team)) {
                $itemdata = array(
                    'login' => $team,
                    'name' => $team,
                    'categoryid' => 2,
                    'members' => null,
                    'affilid' => null,
                    'authtoken' => null,
                    'room' => null,
                    'comments' => null,
                    'enabled' => 1
                );
                $DBDOM->q('INSERT INTO team SET %S', $itemdata);
            }
            $team = $login;

            // Reindex arrays numerically to allow simultaneously iterating
            // over both $files and $filenames.
            $files = array_values($files);
            $filenames = array_values($filenames);

//        if ($totalsize > $sourcesize * 1024) {//TODO: restricciones de tamaño para un envio de la base de datos
//            error("Submission file(s) are larger than $sourcesize kB.");
//        }
            // Insert submission into the database
            $now = strftime('%Y-%m-%d %H:%M:%S');
            $cid = 2;
            $id = $DBDOM->q('RETURNID INSERT INTO submission
				  (cid, teamid, probid, langid, submittime, origsubmitid)
				  VALUES (%i, %s, %s, %s, %s, %i)', $cid, $team, $prob, $lang, $now, $origsubmitid);

            for ($rank = 0; $rank < count($files); $rank++) {
                $DBDOM->q('INSERT INTO submission_file
		        (submitid, filename, rank, sourcecode) VALUES (%i, %s, %i, %s)', $id, $filenames[$rank], $rank, $files[$rank]->get_content()); //getFileContents($files[$rank], false)
            }

            // Log to event table
            $DBDOM->q('INSERT INTO event (eventtime, cid, teamid, langid, probid, submitid, description)
                VALUES(%s, %i, %s, %s, %s, %i, "problem submitted")', $now, $cid, $team, $lang, $probid, $id);
        }
    }

}
