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
/*¡
 * Database management
 */
require('lib.database.php');

// Internal and output character set used, don't change.
define('DJ_CHARACTER_SET', 'utf-8');
define('DJ_CHARACTER_SET_MYSQL', 'utf8');

// MySQL connection flags.
define('DJ_MYSQL_CONNECT_FLAGS', null);

// Set DEBUG as a bitmask of the following settings.
// Of course never to be used on live systems!

define('DEBUG_PHP_NOTICE', 1); // Display PHP notice level warnings
define('DEBUG_TIMINGS', 2); // Display timings for loading webpages
define('DEBUG_SQL', 4); // Display SQL queries on webpages
define('DEBUG_JUDGE', 8); // Display judging scripts debug info

define('DEBUG', 0);

// By default report all PHP errors, except notices.
error_reporting(E_ALL & ~E_NOTICE);

// Set error reporting to all in debugging mode
if (DEBUG & DEBUG_PHP_NOTICE)
    error_reporting(E_ALL);

function setup_database_connection() {

    global $DBDOM;
    if ($DBDOM) {
        user_error("There already is a database-connection", E_USER_ERROR);
        exit();
    }
    
    //var_dump('judge/ideone/use_db.php setup_database_connection() ');

    $db = get_config('local_onlinejudge', 'judgehostdbname');
    $host = get_config('local_onlinejudge', 'judgehostdbhost');
    $user = get_config('local_onlinejudge', 'judgehostdbuser');
    $pass = get_config('local_onlinejudge', 'judgehostdbpass');
    
    /*var_dump($pass);
    var_dump($db);
    var_dump($user);
    var_dump($host);*/
    
//  $DBDOM = new dbdom ("domjudge", "localhost", "domjudge", "domjudge", null, DJ_MYSQL_CONNECT_FLAGS);
    try {
        $DBDOM = new dbdom($db, $host, $user, $pass, null, DJ_MYSQL_CONNECT_FLAGS);
    } catch (Exception $e) {
        return false;
    }

    if (!$DBDOM) {
//		user_error("Failed to create database connection", E_USER_ERROR);
        var_dump("Failed to create database connection");
//		exit();
        return false;
    }
    return true;
}

