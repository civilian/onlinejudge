<?php
/**
 * Include required files.
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

// Sanity check whether webserver basic authentication (e.g in
// apache.conf) is configured correctly
if (empty($_SERVER['REMOTE_USER']) || $_SERVER['AUTH_TYPE'] != "Basic") {
	die("Authentication not enabled, check webserver config");
}

require_once('../configure.php');

define('IS_JURY', TRUE);
define('IS_PUBLIC', false);

require_once(LIBDIR . '/init.php');

setup_database_connection();

require_once(LIBWWWDIR . '/common.php');
require_once(LIBWWWDIR . '/print.php');
require_once(LIBWWWDIR . '/forms.php');
require_once(LIBWWWDIR . '/printing.php');

require_once(LIBWWWDIR . '/validate.jury.php');
require_once(LIBWWWDIR . '/common.jury.php');

$cdata = getCurContest(TRUE);
$cid = (int)$cdata['cid'];

$nunread_clars = $DB->q('VALUE SELECT COUNT(*) FROM clarification
                         WHERE sender IS NOT NULL AND cid = %i
                         AND answered = 0', $cid);
