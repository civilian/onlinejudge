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
 * Judges all unjudged tasks
 *
 * In Linux, it will create a daemon and exit
 * In Windows, it will never exit except killed by users
 *
 * @package    local_online_uv_judge
 * @author     Sun Zhigang, Oscar Chamat
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('CLI_SCRIPT')) {
    define('CLI_SCRIPT', true);
}
define('LOCK_FILE', '/temp/onlinejudge/lock');

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/clilib.php');      // cli only functions
require_once($CFG->dirroot.'/local/onlinejudge/judgelib.php');
require_once($CFG->dirroot . '/local/onlinejudge/judge/ideone/use_db.php');
require_once('judged_lib.php');

global $DBDOM, $LOCK;
if ($DBDOM === NULL) {
    setup_database_connection();
}

// Ensure errors are well explained
if ($CFG->debug < DEBUG_NORMAL) {
    $CFG->debug = DEBUG_NORMAL;
}

// now get cli options
$longoptions  = array('help'=>false, 'nodaemon'=>false, 'once'=>false, 'verbose'=>false);//TODO: Aqui configurar cuando vaya a arreglar el demonio
#TODO: Quitar para la instalacion las opciones abajo
#$longoptions  = array('help'=>false, 'nodaemon'=>true, 'once'=>false, 'verbose'=>true, 'test'=>false);
$shortoptions = array('h'=>'help', 'n'=>'nodaemon', 'o'=>'once', 'v'=>'verbose', 't' => 'test');
list($options, $unrecognized) = cli_get_params($longoptions, $shortoptions);

//$delete = array('local_onlinejudge_generator_testcase', 'local/onlinejudge/tests/judged_test.php');
//foreach($delete as $val){
//    $pos = array_search($val, $unrecognized);
//    unset($unrecognized[$pos]);
//}
if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help']) {
    $help =
"Judge all unjudged tasks.

Options:
-h, --help            Print out this help
-n, --nodaemon        Do not run as daemon (Linux only)
-o, --once            Exit while no more to judge
-v, --verbose         Verbose output

Example:
\$sudo -u www-data /usr/bin/php local/onlinejudge/cli/judged.php
";

    echo $help;
    die;
}

if ($CFG->ostype != 'WINDOWS' and !$options['nodaemon']) {
    // create daemon
    verbose(cli_heading('Creating daemon', true));

    if (!extension_loaded('pcntl') || !extension_loaded('posix')) {
        cli_error('PHP pcntl and posix extension must be installed!');
    }

    $pid = pcntl_fork();

    if ($pid == -1) {
        cli_error('Could not fork');
    } else if ($pid > 0) { // parent process
        mtrace('Judge daemon successfully created. PID = '.$pid);
        die;
    } else { // child process
        // make the current process a session leader.
        $sid = posix_setsid();
        if ($sid < 0) {
            cli_error('Can not setsid()');
        }

        // reconnect DB
        unset($DB);
        setup_DB();
    }
}

verbose(cli_separator(true));
verbose('Judge daemon is running now.');

if ($CFG->ostype != 'WINDOWS' and function_exists('pcntl_signal')) {
    // Handle SIGTERM and SIGINT so that can be killed without pain
    declare(ticks = 1); // tick use required as of PHP 4.3.0
    pcntl_signal(SIGTERM, 'sigterm_handler');
    pcntl_signal(SIGINT, 'sigterm_handler');
}

// Run forever until being killed or the plugin was upgraded
$lockfile = $CFG->dataroot . LOCK_FILE;
if (!check_dir_exists(dirname($lockfile))) {
    throw new moodle_exception('errorcreatingdirectory', '', '', $lockfile);
}
$LOCK = fopen($lockfile, 'w');
if (!$LOCK) {
    mtrace('Can not create'.$CFG->dataroot.LOCK_FILE);
    die;
}
$forcestop = false;
$plugin_version = get_config('local_onlinejudge', 'version');
while (!$forcestop) {
    judge_all_unjudged();

    if ($options['once']) {
        break;
    }

    //Check interval was 5 seconds
    sleep(get_config('local_onlinejudge', 'timechecktask'));

    if ($plugin_version < get_config('local_onlinejudge', 'version')) {
        verbose('Plugin was upgraded.');
        break;
    }
}

verbose('Clean temp files.');
onlinejudge_clean_temp_dir(false);  // Clean full tree of temp dir
verbose('Judge daemon exits.');
fclose($LOCK);


