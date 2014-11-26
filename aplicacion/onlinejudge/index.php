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
 * online judge home page
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

$context = get_system_context();

$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url('/local/onlinejudge/index.php');
$PAGE->set_title(get_string('pluginname', 'local_onlinejudge'));
$PAGE->set_heading("$SITE->shortname: ".get_string('pluginname', 'local_onlinejudge'));

$output = $PAGE->get_renderer('local_onlinejudge');

/// Output starts here
echo $output->header();

/// Judge status
if (has_capability('local/onlinejudge:viewjudgestatus', $context)) {
    echo $output->heading(get_string('status'), 1);
    echo $output->judgestatus();
}

/// My statistics
if (has_capability('local/onlinejudge:viewmystat', $context)) {
    echo $output->heading(get_string('mystat', 'local_onlinejudge'), 1);
    echo $output->mystatistics();
}

/// About
echo $output->heading(get_string('about', 'local_onlinejudge'), 1);//TODO: cambiar esto para que no solo diga de ese instituto.
//echo $output->container(get_string('aboutcontent', 'local_onlinejudge'), 'box copyright');

echo $output->footer();

