DROP USER 'domjudge'@'%';

CREATE USER 'domjudge'@'%' IDENTIFIED BY  'domjudge';
CREATE USER 'domjudge'@'localhost' IDENTIFIED BY  'domjudge';
GRANT USAGE ON * . * TO  'domjudge'@'%' IDENTIFIED BY  'domjudge' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
GRANT ALL PRIVILEGES ON  `domjudge` . * TO  'domjudge'@'%';
--
-- Servidor: localhost
-- Tiempo de generación: 23-11-2013 a las 23:03:58
-- Versión del servidor: 5.5.30-log
-- Versión de PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `domjudge`
--
CREATE DATABASE IF NOT EXISTS `domjudge` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `domjudge`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditlog`
--

CREATE TABLE IF NOT EXISTS `auditlog` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `logtime` datetime NOT NULL COMMENT 'Timestamp of the logentry',
  `cid` int(4) unsigned DEFAULT NULL COMMENT 'Contest ID associated to this entry',
  `user` varchar(255) DEFAULT NULL COMMENT 'User who performed this action',
  `datatype` varchar(25) DEFAULT NULL COMMENT 'Reference to DB table associated to this entry',
  `dataid` varchar(50) DEFAULT NULL COMMENT 'Identifier in reference table',
  `action` varchar(30) DEFAULT NULL COMMENT 'Description of action performed',
  `extrainfo` varchar(255) DEFAULT NULL COMMENT 'Optional additional description of the entry',
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Log of all actions performed' AUTO_INCREMENT=6732 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `balloon`
--

CREATE TABLE IF NOT EXISTS `balloon` (
  `balloonid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `submitid` int(4) unsigned NOT NULL COMMENT 'Submission for which balloon was earned',
  `done` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Has been handed out yet?',
  PRIMARY KEY (`balloonid`),
  KEY `submitid` (`submitid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Balloons to be handed out' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clarification`
--

CREATE TABLE IF NOT EXISTS `clarification` (
  `clarid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `cid` int(4) unsigned NOT NULL COMMENT 'Contest ID',
  `respid` int(4) unsigned DEFAULT NULL COMMENT 'In reply to clarification ID',
  `submittime` datetime NOT NULL COMMENT 'Time sent',
  `sender` varchar(15) DEFAULT NULL COMMENT 'Team login, null means jury',
  `recipient` varchar(15) DEFAULT NULL COMMENT 'Team login, null means to jury or to all',
  `jury_member` varchar(15) DEFAULT NULL COMMENT 'Name of jury member who answered this',
  `probid` varchar(8) DEFAULT NULL COMMENT 'Problem associated to this clarification',
  `body` longtext NOT NULL COMMENT 'Clarification text',
  `answered` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Has been answered by jury?',
  PRIMARY KEY (`clarid`),
  KEY `cid` (`cid`,`answered`,`submittime`),
  KEY `respid` (`respid`),
  KEY `probid` (`probid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Clarification requests by teams and responses by the jury' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `configid` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Configuration ID',
  `name` varchar(25) NOT NULL COMMENT 'Name of the configuration variable',
  `value` longtext NOT NULL COMMENT 'Content of the configuration variable',
  `type` varchar(25) DEFAULT NULL COMMENT 'Type of the value (metatype for use in the webinterface)',
  `description` varchar(255) DEFAULT NULL COMMENT 'Description for in the webinterface',
  PRIMARY KEY (`configid`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Global configuration variables' AUTO_INCREMENT=18 ;

--
-- Volcado de datos para la tabla `configuration`
--

INSERT INTO `configuration` (`configid`, `name`, `value`, `type`, `description`) VALUES
(1, 'compile_time', '30', 'int', 'Maximum seconds available for compiling.'),
(2, 'memory_limit', '393216', 'int', 'Maximum memory usage (in kB) by submissions. This includes the shell which starts the compiled solution and also any interpreter like the Java VM, which takes away approx. 300MB!'),
(3, 'filesize_limit', '4096', 'int', 'Maximum filesize (in kB) submissions may write. Solutions will abort when trying to write more, so this should be greater than the maximum testdata output.'),
(4, 'process_limit', '15', 'int', 'Maximum number of processes that the submission is allowed to start (including shell and possibly interpreters).'),
(5, 'sourcesize_limit', '256', 'int', 'Maximum source code size (in kB) of a submission. This setting should be kept in sync with that in "etc/submit-config.h.in".'),
(6, 'sourcefiles_limit', '100', 'int', 'Maximum number of source files in one submission. Set to one to disable multiple file submissions.'),
(7, 'verification_required', '0', 'bool', 'Is verification of judgings by jury required before publication?'),
(8, 'show_affiliations', '1', 'bool', 'Show affiliations names and icons in the scoreboard?'),
(9, 'show_pending', '0', 'bool', 'Show pending submissions on the scoreboard?'),
(10, 'show_compile', '2', 'int', 'Show compile output in team webinterface? Choices: 0 = never, 1 = only on compilation error(s), 2 = always.'),
(11, 'show_balloons_postfreeze', '0', 'bool', 'Give out balloon notifications after the scoreboard has been frozen?'),
(12, 'penalty_time', '20', 'int', 'Penalty time in minutes per wrong submission (if finally solved).'),
(13, 'results_prio', '{"memory-limit":99,"output-limit":99,"run-error":99,"timelimit":99,"wrong-answer":30,"presentation-error":20,"no-output":10,"correct":1}', 'array_keyval', 'Priorities of results for determining final result with multiple testcases. Higher priority is used first as final result. With equal priority, the first occurring result determines the final result.'),
(14, 'results_remap', '{"presentation-error":"wrong-answer"}', 'array_keyval', 'Remap testcase result, e.g. to disable a specific result type such as ''presentation-error''.'),
(15, 'lazy_eval_results', '1', 'bool', 'Lazy evaluation of results? If enabled, stops judging as soon as a highest priority result is found, otherwise always all testcases will be judged.'),
(16, 'enable_printing', '0', 'bool', 'Enable teams and jury to send source code to a printer via the DOMjudge web interface.'),
(17, 'time_format', '"H:i"', 'string', 'The format used to print times. For formatting options see the PHP ''date'' function.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contest`
--

CREATE TABLE IF NOT EXISTS `contest` (
  `cid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Contest ID',
  `contestname` varchar(255) NOT NULL COMMENT 'Descriptive name',
  `activatetime` datetime NOT NULL COMMENT 'Time contest becomes visible in team/public views',
  `starttime` datetime NOT NULL COMMENT 'Time contest starts, submissions accepted',
  `freezetime` datetime DEFAULT NULL COMMENT 'Time scoreboard is frozen',
  `endtime` datetime NOT NULL COMMENT 'Time after which no more submissions are accepted',
  `unfreezetime` datetime DEFAULT NULL COMMENT 'Unfreeze a frozen scoreboard at this time',
  `activatetime_string` varchar(20) NOT NULL COMMENT 'Authoritative absolute or relative string representation of activatetime',
  `freezetime_string` varchar(20) DEFAULT NULL COMMENT 'Authoritative absolute or relative string representation of freezetime',
  `endtime_string` varchar(20) NOT NULL COMMENT 'Authoritative absolute or relative string representation of endtime',
  `unfreezetime_string` varchar(20) DEFAULT NULL COMMENT 'Authoritative absolute or relative string representation of unfreezetrime',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Whether this contest can be active',
  PRIMARY KEY (`cid`),
  KEY `cid` (`cid`,`enabled`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contests that will be run with this install' AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `contest`
--

INSERT INTO `contest` (`cid`, `contestname`, `activatetime`, `starttime`, `freezetime`, `endtime`, `unfreezetime`, `activatetime_string`, `freezetime_string`, `endtime_string`, `unfreezetime_string`, `enabled`) VALUES
(1, 'Demo practice session', '2010-01-01 08:30:00', '2010-01-01 09:00:00', NULL, '2010-01-01 11:00:00', NULL, '-1:00', NULL, '+2:00', NULL, 1),
(2, 'Demo contest', '2012-01-01 11:30:00', '2012-01-01 12:00:00', '2014-01-01 16:00:00', '2099-01-01 17:00:00', '2099-01-01 17:30:00', '2012-01-01 11:30:00', '2014-01-01 16:00:00', '2099-01-01 17:00:00', '2099-01-01 17:30:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentation_style`
--

CREATE TABLE IF NOT EXISTS `documentation_style` (
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `author` tinyint(1) DEFAULT '0',
  `version` tinyint(1) DEFAULT '0',
  `copyright` tinyint(1) DEFAULT '0',
  `license` tinyint(1) DEFAULT '0',
  `package` tinyint(1) DEFAULT '0',
  `param` tinyint(1) DEFAULT '0',
  `retrn` tinyint(1) DEFAULT '0',
  `param_pub` tinyint(1) DEFAULT '0',
  `return_pub` tinyint(1) DEFAULT '0',
  `see` tinyint(1) DEFAULT '0',
  `documentation_styleid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `langid` varchar(8) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`documentation_styleid`),
  UNIQUE KEY `id` (`documentation_styleid`),
  UNIQUE KEY `style-langid` (`name`,`langid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `documentation_style`
--

INSERT INTO `documentation_style` (`name`, `author`, `version`, `copyright`, `license`, `package`, `param`, `retrn`, `param_pub`, `return_pub`, `see`, `documentation_styleid`, `langid`) VALUES
('default', 1, 1, NULL, 1, NULL, 0, 0, 0, 0, 0, 1, 'scheme'),
('default', 1, 1, NULL, 1, NULL, 0, 0, 0, 0, 0, 2, 'java'),
('default', 1, 1, NULL, 1, NULL, 0, 0, 0, 0, 0, 3, 'cpp'),
('a', 1, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, 6, 'cpp'),
('b', 1, 1, NULL, NULL, NULL, 0, 0, 0, 0, 0, 19, 'cpp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `eventid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `eventtime` datetime NOT NULL COMMENT 'When the event occurred',
  `cid` int(4) unsigned NOT NULL COMMENT 'Contest ID',
  `clarid` int(4) unsigned DEFAULT NULL COMMENT 'Clarification ID',
  `langid` varchar(8) DEFAULT NULL COMMENT 'Language ID',
  `probid` varchar(8) DEFAULT NULL COMMENT 'Problem ID',
  `submitid` int(4) unsigned DEFAULT NULL COMMENT 'Submission ID',
  `judgingid` int(4) unsigned DEFAULT NULL COMMENT 'Judging ID',
  `teamid` varchar(15) DEFAULT NULL COMMENT 'Team login',
  `description` longtext NOT NULL COMMENT 'Event description',
  PRIMARY KEY (`eventid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Log of all events during a contest' AUTO_INCREMENT=237 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `indentation_style`
--

CREATE TABLE IF NOT EXISTS `indentation_style` (
  `indentation_styleid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `langid` varchar(8) CHARACTER SET utf8 NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sample` longtext COLLATE utf8_unicode_ci,
  UNIQUE KEY `documentation_styleid` (`indentation_styleid`),
  UNIQUE KEY `style-langid` (`name`,`langid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `indentation_style`
--

INSERT INTO `indentation_style` (`indentation_styleid`, `langid`, `name`, `sample`) VALUES
(1, 'cpp', 'gnu', NULL),
(2, 'cpp', 'linux', NULL),
(3, 'cpp', 'bsd', NULL),
(4, 'cpp', 'ellemtel', NULL),
(5, 'cpp', 'default', NULL),
(6, 'java', 'default', NULL),
(7, 'java', 'linux', NULL),
(8, 'java', 'bsd', NULL),
(9, 'java', 'ellemtel', NULL),
(10, 'scheme', 'default', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `judgehost`
--

CREATE TABLE IF NOT EXISTS `judgehost` (
  `hostname` varchar(50) NOT NULL COMMENT 'Resolvable hostname of judgehost',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Should this host take on judgings?',
  `polltime` datetime DEFAULT NULL COMMENT 'Time of last poll by autojudger',
  PRIMARY KEY (`hostname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Hostnames of the autojudgers';

--
-- Volcado de datos para la tabla `judgehost`
--

INSERT INTO `judgehost` (`hostname`, `active`, `polltime`) VALUES
('example-judgehost1', 0, NULL),
('lucy', 1, '2013-11-13 22:15:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `judging`
--

CREATE TABLE IF NOT EXISTS `judging` (
  `judgingid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `cid` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Contest ID',
  `submitid` int(4) unsigned NOT NULL COMMENT 'Submission ID being judged',
  `starttime` datetime NOT NULL COMMENT 'Time judging started',
  `endtime` datetime DEFAULT NULL COMMENT 'Time judging ended, null = still busy',
  `judgehost` varchar(50) DEFAULT NULL COMMENT 'Judgehost that performed the judging',
  `result` varchar(25) DEFAULT NULL COMMENT 'Result string as defined in config.php',
  `verified` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Result verified by jury member?',
  `jury_member` varchar(15) DEFAULT NULL COMMENT 'Name of jury member who verified this',
  `verify_comment` varchar(255) DEFAULT NULL COMMENT 'Optional additional information provided by the verifier',
  `valid` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Old judging is marked as invalid when rejudging',
  `output_compile` longblob COMMENT 'Output of the compiling the program',
  `seen` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Whether the team has seen this judging',
  `indentation_grade` double NOT NULL DEFAULT '0' COMMENT 'Result from indentation grade.',
  `documentation_grade` double NOT NULL DEFAULT '0' COMMENT 'Result from documentation grade.',
  PRIMARY KEY (`judgingid`),
  KEY `submitid` (`submitid`),
  KEY `judgehost` (`judgehost`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Result of judging a submission' AUTO_INCREMENT=177 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `judging_run`
--

CREATE TABLE IF NOT EXISTS `judging_run` (
  `runid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `judgingid` int(4) unsigned NOT NULL COMMENT 'Judging ID',
  `testcaseid` int(4) unsigned NOT NULL COMMENT 'Testcase ID',
  `runresult` varchar(25) DEFAULT NULL COMMENT 'Result of this run, NULL if not finished yet',
  `runtime` float DEFAULT NULL COMMENT 'Submission running time on this testcase',
  `output_run` longblob COMMENT 'Output of running the program',
  `output_diff` longblob COMMENT 'Diffing the program output and testcase output',
  `output_error` longblob COMMENT 'Standard error output of the program',
  PRIMARY KEY (`runid`),
  UNIQUE KEY `testcaseid` (`judgingid`,`testcaseid`),
  KEY `judgingid` (`judgingid`),
  KEY `testcaseid_2` (`testcaseid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Result of a testcase run within a judging' AUTO_INCREMENT=82 ;

--
-- Volcado de datos para la tabla `judging_run`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `langid` varchar(8) NOT NULL COMMENT 'Unique ID (string), used for source file extension',
  `name` varchar(255) NOT NULL COMMENT 'Descriptive language name',
  `allow_submit` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Are submissions accepted in this language?',
  `allow_judge` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Are submissions in this language judged?',
  `time_factor` float NOT NULL DEFAULT '1' COMMENT 'Language-specific factor multiplied by problem run times',
  PRIMARY KEY (`langid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Programming languages in which teams can submit solutions';

--
-- Volcado de datos para la tabla `language`
--

INSERT INTO `language` (`langid`, `name`, `allow_submit`, `allow_judge`, `time_factor`) VALUES
('adb', 'Ada', 0, 1, 1),
('awk', 'AWK', 0, 1, 1),
('bash', 'Bash shell', 0, 1, 1),
('c', 'C', 0, 1, 1),
('cpp', 'C++', 1, 1, 1),
('csharp', 'C#', 0, 1, 1),
('f95', 'Fortran', 0, 1, 1),
('hs', 'Haskell', 0, 1, 2),
('java', 'Java', 1, 1, 1),
('lua', 'Lua', 0, 1, 1),
('pas', 'Pascal', 0, 1, 1),
('pl', 'Perl', 0, 1, 1),
('py2', 'Python 2', 0, 1, 1),
('py3', 'Python 3', 0, 1, 1),
('scala', 'Scala', 0, 1, 1.5),
('scheme', 'Racket 5.3.6 \\ drScheme', 1, 1, 1),
('sh', 'POSIX shell', 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problem`
--

CREATE TABLE IF NOT EXISTS `problem` (
  `probid` varchar(8) NOT NULL COMMENT 'Unique ID (string)',
  `cid` int(4) unsigned NOT NULL COMMENT 'Contest ID',
  `name` varchar(255) NOT NULL COMMENT 'Descriptive name',
  `allow_submit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Are submissions accepted for this problem?',
  `allow_judge` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Are submissions for this problem judged?',
  `timelimit` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Maximum run time for this problem',
  `special_run` varchar(25) DEFAULT NULL COMMENT 'Script to run submissions for this problem',
  `special_compare` varchar(25) DEFAULT NULL COMMENT 'Script to compare problem and jury output for this problem',
  `color` varchar(25) DEFAULT NULL COMMENT 'Balloon colour to display on the scoreboard',
  `problemtext` longblob COMMENT 'Problem text in HTML/PDF/ASCII',
  `problemtext_type` varchar(4) DEFAULT NULL COMMENT 'File type of problem text',
  `indentation_style` bigint(20) unsigned NOT NULL,
  `documentation_style` bigint(20) unsigned NOT NULL,
  `testcases_grade` double NOT NULL DEFAULT '0.6',
  `documentation_grade` double NOT NULL DEFAULT '0.2',
  `indentation_grade` double NOT NULL DEFAULT '0.2',
  PRIMARY KEY (`probid`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Problems the teams can submit solutions for';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `scoreboard_jury`
--

CREATE TABLE IF NOT EXISTS `scoreboard_jury` (
  `cid` int(4) unsigned NOT NULL COMMENT 'Contest ID',
  `teamid` varchar(15) NOT NULL COMMENT 'Team login',
  `probid` varchar(8) NOT NULL COMMENT 'Problem ID',
  `submissions` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Number of submissions made',
  `pending` int(4) NOT NULL DEFAULT '0' COMMENT 'Number of submissions pending judgement',
  `totaltime` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Total time spent',
  `is_correct` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Has there been a correct submission?',
  PRIMARY KEY (`cid`,`teamid`,`probid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Scoreboard cache (jury version)';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `scoreboard_public`
--

CREATE TABLE IF NOT EXISTS `scoreboard_public` (
  `cid` int(4) unsigned NOT NULL COMMENT 'Contest ID',
  `teamid` varchar(15) NOT NULL COMMENT 'Team login',
  `probid` varchar(8) NOT NULL COMMENT 'Problem ID',
  `submissions` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Number of submissions made',
  `pending` int(4) NOT NULL DEFAULT '0' COMMENT 'Number of submissions pending judgement',
  `totaltime` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Total time spent',
  `is_correct` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Has there been a correct submission?',
  PRIMARY KEY (`cid`,`teamid`,`probid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Scoreboard cache (public/team version)';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `submission`
--

CREATE TABLE IF NOT EXISTS `submission` (
  `submitid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `origsubmitid` int(4) unsigned DEFAULT NULL COMMENT 'If set, specifies original submission in case of edit/resubmit',
  `cid` int(4) unsigned NOT NULL COMMENT 'Contest ID',
  `teamid` varchar(15) NOT NULL COMMENT 'Team login',
  `probid` varchar(8) NOT NULL COMMENT 'Problem ID',
  `langid` varchar(8) NOT NULL COMMENT 'Language ID',
  `submittime` datetime NOT NULL COMMENT 'Time submitted',
  `judgehost` varchar(50) DEFAULT NULL COMMENT 'Current/last judgehost judging this submission',
  `judgemark` varchar(255) DEFAULT NULL COMMENT 'Unique identifier for taking a submission by a judgehost',
  `valid` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'If false ignore this submission in all scoreboard calculations',
  PRIMARY KEY (`submitid`),
  UNIQUE KEY `judgemark` (`judgemark`),
  KEY `teamid` (`cid`,`teamid`),
  KEY `judgehost` (`cid`,`judgehost`),
  KEY `teamid_2` (`teamid`),
  KEY `probid` (`probid`),
  KEY `langid` (`langid`),
  KEY `judgehost_2` (`judgehost`),
  KEY `origsubmitid` (`origsubmitid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='All incoming submissions' AUTO_INCREMENT=122 ;

--
-- Volcado de datos para la tabla `submission`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `submission_file`
--

CREATE TABLE IF NOT EXISTS `submission_file` (
  `submitfileid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `submitid` int(4) unsigned NOT NULL COMMENT 'Submission this file belongs to',
  `sourcecode` longblob NOT NULL COMMENT 'Full source code',
  `filename` varchar(255) NOT NULL COMMENT 'Filename as submitted',
  `rank` int(4) unsigned NOT NULL COMMENT 'Order of the submission files, zero-indexed',
  PRIMARY KEY (`submitfileid`),
  UNIQUE KEY `filename` (`submitid`,`filename`),
  UNIQUE KEY `rank` (`submitid`,`rank`),
  KEY `submitid` (`submitid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Files associated to a submission' AUTO_INCREMENT=255 ;

--
-- Volcado de datos para la tabla `submission_file`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `login` varchar(15) NOT NULL COMMENT 'Team login name',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Team name',
  `categoryid` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Team category ID',
  `affilid` varchar(10) DEFAULT NULL COMMENT 'Team affiliation ID',
  `authtoken` varchar(255) DEFAULT NULL COMMENT 'Identifying token for this team',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Whether the team is visible and operational',
  `members` longtext COMMENT 'Team member names (freeform)',
  `room` varchar(15) DEFAULT NULL COMMENT 'Physical location of team',
  `comments` longtext COMMENT 'Comments about this team',
  `judging_last_started` datetime DEFAULT NULL COMMENT 'Start time of last judging for priorization',
  `teampage_first_visited` datetime DEFAULT NULL COMMENT 'Time of first teampage view',
  `hostname` varchar(255) DEFAULT NULL COMMENT 'Teampage first visited from this address',
  PRIMARY KEY (`login`),
  UNIQUE KEY `name` (`name`),
  KEY `affilid` (`affilid`),
  KEY `categoryid` (`categoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='All teams participating in the contest';

--
-- Volcado de datos para la tabla `team`
--

INSERT INTO `team` (`login`, `name`, `categoryid`, `affilid`, `authtoken`, `enabled`, `members`, `room`, `comments`, `judging_last_started`, `teampage_first_visited`, `hostname`) VALUES
('2', '2', 2, NULL, NULL, 1, NULL, NULL, NULL, '2013-11-14 02:05:24', NULL, NULL),
('3', '3', 2, NULL, NULL, 1, NULL, NULL, NULL, '2013-11-12 20:32:49', NULL, NULL),
('coolteam', 'Some very cool teamname!', 2, 'UU', 'ac0821468fc9b438d3e39de1a7d9d300', 1, NULL, NULL, NULL, NULL, NULL, NULL),
('domjudge', 'DOMjudge', 1, NULL, '127.0.0.1', 1, NULL, NULL, NULL, NULL, NULL, NULL),
('otro', 'otro', 2, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team_affiliation`
--

CREATE TABLE IF NOT EXISTS `team_affiliation` (
  `affilid` varchar(10) NOT NULL COMMENT 'Unique ID',
  `name` varchar(255) NOT NULL COMMENT 'Descriptive name',
  `country` char(3) DEFAULT NULL COMMENT 'ISO 3166-1 alpha-3 country code',
  `comments` longtext COMMENT 'Comments',
  PRIMARY KEY (`affilid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Affilitations for teams (e.g.: university, company)';

--
-- Volcado de datos para la tabla `team_affiliation`
--

INSERT INTO `team_affiliation` (`affilid`, `name`, `country`, `comments`) VALUES
('UU', 'Utrecht University', 'NLD', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team_category`
--

CREATE TABLE IF NOT EXISTS `team_category` (
  `categoryid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `name` varchar(255) NOT NULL COMMENT 'Descriptive name',
  `sortorder` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Where to sort this category on the scoreboard',
  `color` varchar(25) DEFAULT NULL COMMENT 'Background colour on the scoreboard',
  `visible` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Are teams in this category visible?',
  PRIMARY KEY (`categoryid`),
  KEY `sortorder` (`sortorder`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Categories for teams (e.g.: participants, observers, ...)' AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `team_category`
--

INSERT INTO `team_category` (`categoryid`, `name`, `sortorder`, `color`, `visible`) VALUES
(1, 'System', 9, '#ff2bea', 0),
(2, 'Participants', 0, NULL, 1),
(3, 'Observers', 1, '#ffcc33', 1),
(4, 'Organisation', 1, '#ff99cc', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team_unread`
--

CREATE TABLE IF NOT EXISTS `team_unread` (
  `teamid` varchar(15) NOT NULL DEFAULT '' COMMENT 'Team login',
  `mesgid` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Clarification ID',
  `type` varchar(25) NOT NULL DEFAULT 'clarification' COMMENT 'Type of message (now always "clarification")',
  PRIMARY KEY (`teamid`,`type`,`mesgid`),
  KEY `mesgid` (`mesgid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='List of items a team has not viewed yet';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testcase`
--

CREATE TABLE IF NOT EXISTS `testcase` (
  `testcaseid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `md5sum_input` char(32) DEFAULT NULL COMMENT 'Checksum of input data',
  `md5sum_output` char(32) DEFAULT NULL COMMENT 'Checksum of output data',
  `input` longblob COMMENT 'Input data',
  `output` longblob COMMENT 'Output data',
  `probid` varchar(8) NOT NULL COMMENT 'Corresponding problem ID',
  `rank` int(4) NOT NULL COMMENT 'Determines order of the testcases in judging',
  `description` varchar(255) DEFAULT NULL COMMENT 'Description of this testcase',
  PRIMARY KEY (`testcaseid`),
  UNIQUE KEY `rank` (`probid`,`rank`),
  KEY `probid` (`probid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Stores testcases per problem' AUTO_INCREMENT=26 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `balloon`
--
ALTER TABLE `balloon`
  ADD CONSTRAINT `balloon_ibfk_1` FOREIGN KEY (`submitid`) REFERENCES `submission` (`submitid`) ON DELETE CASCADE;

--
-- Filtros para la tabla `clarification`
--
ALTER TABLE `clarification`
  ADD CONSTRAINT `clarification_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `contest` (`cid`) ON DELETE CASCADE,
  ADD CONSTRAINT `clarification_ibfk_2` FOREIGN KEY (`respid`) REFERENCES `clarification` (`clarid`) ON DELETE SET NULL,
  ADD CONSTRAINT `clarification_ibfk_3` FOREIGN KEY (`probid`) REFERENCES `problem` (`probid`) ON DELETE SET NULL;

--
-- Filtros para la tabla `judging`
--
ALTER TABLE `judging`
  ADD CONSTRAINT `judging_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `contest` (`cid`) ON DELETE CASCADE,
  ADD CONSTRAINT `judging_ibfk_2` FOREIGN KEY (`submitid`) REFERENCES `submission` (`submitid`) ON DELETE CASCADE,
  ADD CONSTRAINT `judging_ibfk_3` FOREIGN KEY (`judgehost`) REFERENCES `judgehost` (`hostname`) ON DELETE SET NULL;

--
-- Filtros para la tabla `judging_run`
--
ALTER TABLE `judging_run`
  ADD CONSTRAINT `judging_run_ibfk_1` FOREIGN KEY (`testcaseid`) REFERENCES `testcase` (`testcaseid`),
  ADD CONSTRAINT `judging_run_ibfk_2` FOREIGN KEY (`judgingid`) REFERENCES `judging` (`judgingid`) ON DELETE CASCADE;

--
-- Filtros para la tabla `problem`
--
ALTER TABLE `problem`
  ADD CONSTRAINT `problem_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `contest` (`cid`) ON DELETE CASCADE;

--
-- Filtros para la tabla `submission`
--
ALTER TABLE `submission`
  ADD CONSTRAINT `submission_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `contest` (`cid`) ON DELETE CASCADE,
  ADD CONSTRAINT `submission_ibfk_2` FOREIGN KEY (`teamid`) REFERENCES `team` (`login`) ON DELETE CASCADE,
  ADD CONSTRAINT `submission_ibfk_3` FOREIGN KEY (`probid`) REFERENCES `problem` (`probid`) ON DELETE CASCADE,
  ADD CONSTRAINT `submission_ibfk_4` FOREIGN KEY (`langid`) REFERENCES `language` (`langid`) ON DELETE CASCADE,
  ADD CONSTRAINT `submission_ibfk_5` FOREIGN KEY (`judgehost`) REFERENCES `judgehost` (`hostname`) ON DELETE SET NULL,
  ADD CONSTRAINT `submission_ibfk_6` FOREIGN KEY (`origsubmitid`) REFERENCES `submission` (`submitid`) ON DELETE SET NULL;

--
-- Filtros para la tabla `submission_file`
--
ALTER TABLE `submission_file`
  ADD CONSTRAINT `submission_file_ibfk_1` FOREIGN KEY (`submitid`) REFERENCES `submission` (`submitid`) ON DELETE CASCADE;

--
-- Filtros para la tabla `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `team_category` (`categoryid`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_ibfk_2` FOREIGN KEY (`affilid`) REFERENCES `team_affiliation` (`affilid`) ON DELETE SET NULL;

--
-- Filtros para la tabla `team_unread`
--
ALTER TABLE `team_unread`
  ADD CONSTRAINT `team_unread_ibfk_1` FOREIGN KEY (`teamid`) REFERENCES `team` (`login`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_unread_ibfk_2` FOREIGN KEY (`mesgid`) REFERENCES `clarification` (`clarid`) ON DELETE CASCADE;

--
-- Filtros para la tabla `testcase`
--
ALTER TABLE `testcase`
  ADD CONSTRAINT `testcase_ibfk_1` FOREIGN KEY (`probid`) REFERENCES `problem` (`probid`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
