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

/*!
 * Administration forms of the online judge
 */
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // needs this condition or there is error on login page
    require_once($CFG->dirroot . '/local/onlinejudge/judgelib.php');

    $temp = new admin_settingpage('onlinejudge', get_string('pluginname', 'local_onlinejudge'));

    $temp->add(new admin_setting_configtext('local_onlinejudge/timechecktask', 
            get_string('timechecktask', 'local_onlinejudge'), get_string('timechecktask_help', 'local_onlinejudge'), 5, PARAM_INT));
    $temp->add(new admin_setting_configtext('local_onlinejudge/timedaemonout', 
            get_string('timedaemonout', 'local_onlinejudge'), get_string('timedaemonout_help', 'local_onlinejudge'), 60 * 20, PARAM_INT));
    
//    $temp->add(new admin_setting_configtext('local_onlinejudge/maxmemlimit', get_string('maxmemlimit', 'local_onlinejudge'), get_string('maxmemlimit_help', 'local_onlinejudge'), 64, PARAM_INT));
    $temp->add(new admin_setting_configtext('local_onlinejudge/maxcpulimit', get_string('maxcpulimit', 'local_onlinejudge'), get_string('maxcpulimit_help', 'local_onlinejudge'), 6, PARAM_INT));
    $temp->add(new admin_setting_configtext('local_onlinejudge/judgehostdelay', get_string('judgehostdelay', 'local_onlinejudge'), get_string('judgehostdelay_help', 'local_onlinejudge'), 3, PARAM_INT));
//    $temp->add(new admin_setting_configtext('local_onlinejudge/ideonedelay', get_string('ideonedelay', 'local_onlinejudge'), get_string('ideonedelay_help', 'local_onlinejudge'), 0, PARAM_INT));
    $choices = onlinejudge_get_languages();
    $temp->add(new admin_setting_configselect('local_onlinejudge/defaultlanguage',
            get_string('defaultlanguage', 'local_onlinejudge'), get_string('defaultlanguage_help', 'local_onlinejudge'), '', $choices));
    //domjudge judehost db
    $temp->add(new admin_setting_configtext('local_onlinejudge/judgehostdbname', 
            get_string('judgehostdbname', 'local_onlinejudge'), get_string('judgehostdbname_help', 'local_onlinejudge'), '', PARAM_TEXT));
    $temp->add(new admin_setting_configtext('local_onlinejudge/judgehostdbhost', 
            get_string('judgehostdbhost', 'local_onlinejudge'), get_string('judgehostdbhost_help', 'local_onlinejudge'), '', PARAM_TEXT));
    $temp->add(new admin_setting_configtext('local_onlinejudge/judgehostdbuser', 
            get_string('judgehostdbuser', 'local_onlinejudge'), get_string('judgehostdbuser_help', 'local_onlinejudge'), '', PARAM_TEXT));
    $temp->add(new admin_setting_configpasswordunmask('local_onlinejudge/judgehostdbpass', 
            get_string('judgehostdbpass', 'local_onlinejudge'), get_string('judgehostdbpass_help', 'local_onlinejudge'), NULL));

    $temp->add(new admin_setting_users_with_capability('local_onlinejudge/judgedcrashnotify', 
            get_string('judgedcrashnotify', 'local_onlinejudge'), get_string('judgedcrashnotify_help', 'local_onlinejudge'), array(), 'moodle/site:config'));

    $ADMIN->add('localplugins', $temp);
}

