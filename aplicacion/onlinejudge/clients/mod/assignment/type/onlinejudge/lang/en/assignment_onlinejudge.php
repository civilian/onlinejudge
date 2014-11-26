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
 * Indentation management
 * 
 * @package   local_online_uv_judge
 * @author    Sun Zhigang, Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/*!
 * Strings for Online Judge Assignment Type
 */

$string['addtestcases'] = 'Add {$a} more testcase(s)';
$string['adddocumentationstyles'] = 'Add {$a} documentation style(s)';
$string['assignmentlangs'] = 'Programming language';
$string['badtestcasefile'] = 'This file does not exist or can not be read';
$string['cannotruncompiler'] = 'Can not execute the script of compiler';
$string['case'] = 'Case {$a}:';
$string['compileonly'] = 'Compile only';
$string['compileonly_help'] = 'If select yes, the submissions will be compiled but not executed. Teachers must grade manually.';
$string['compiler'] = 'Compiler';
$string['configmaxcpu'] = 'Default maximum assignment cpu time for all assignments on the site (subject to other local settings)';
$string['configmaxmem'] = 'Default maximum assignment memory usage for all assignments on the site (subject to other local settings)';
$string['cpulimit'] = 'Maximum CPU time';
$string['denytoreadfile'] = 'You have not the permission to read this file.';
$string['download'] = 'Download ';
$string['duejudge'] = 'Judge after due date';
$string['feedback'] = 'Feedback for Wrong Answer';
$string['feedback_help'] = 'The message would be showen to the students who did not pass the testcase. It is helpful if you want to give some hints or instructions.';
$string['filereaderror'] = 'Can not read this file';
$string['forcejudge'] = 'Force judge';
$string['changedocumentationgrade'] = 'Change grade';
$string['changeindentationgrade'] = 'Change grade';
$string['ideoneuser'] = 'Ideone username';
$string['ideoneuser_help'] = 'If you choose a language which is run in ideone.com, you must provide a <a href="http://ideone.com">ideone.com</a> username.';
$string['ideonepass'] = 'Ideone API password';
$string['ideonepass_help'] = 'It is NOT the ideone password but the ideone <em>API</em> password. Change API password at <a href="https://ideone.com/account/">https://ideone.com/account/</a>.';
$string['ideonepass2'] = 'Retype API password';
$string['ideonepassmismatch'] = 'The two passwords are mismatch';
$string['input'] = 'Input';
$string['input_help'] = 'The input data will be sent to the stdin of submitted programs.

NOTE: Windows flavor new line characters (CR + LF or \r\n) will be converted to Unix flavor (LF or \n).';
$string['inputfile'] = 'Input file';
$string['inputfile_help'] = 'The data in the file will be sent to the stdin of submitted programs.

If the file is missing, the testcase will be skipped.';
$string['judgetime'] = 'Judge time';
$string['managetestcases'] = 'Manage testcases';
$string['manageindentationstyles'] = 'Manage programing styles';
$string['managedocumentationtyles'] = 'Manage documentation styles';
$string['maxcpuusage'] = 'Maximum CPU usage';
$string['maximumfilesize'] = 'Maximum source file size';
$string['maxmemusage'] = 'Maximum memory usage';
$string['memlimit'] = 'Maximum memory usage';
$string['notestcases'] = 'No defined testcases';
$string['onlinejudgeinfo'] = 'Online Judge Information';
$string['output'] = 'Output';
$string['output_help'] = 'The output data will be compared with submissions\' stdout to judge correctness.

NOTE: Windows flavor new line characters (CR + LF or \r\n) will be converted to Unix flavor (LF or \n).';
$string['outputfile'] = 'Output file';
$string['outputfile_help'] = 'The data in the file will be compared with submissions\' stdout to judge correctness.

If the file is missing, the testcase will be skipped.';
$string['pluginname'] = 'Online Judge';
$string['ratiope'] = 'Ratio for presentation error';
$string['ratiope_help'] = 'Grade for presentation error is equal to testcase\'s max grade times this ratio.

Presentation error means the data outputted by the program is correct, but the seperators between each data tokens are mismatched with testcases. It is usually caused by extra white spaces or line breaks. If you want to be strict, set it to 0% and a presentation error will worth zero. If you don\'t mind such trival issues, set it to 100% and a presentation error will be equivalent to an accepted.';
$string['compileimportance'] = 'Is compiling important in the styles note';
$string['compileimportance_help'] = 'If is set to yes the submission for the assignment has to compile correctly for the styles grade (documentation, indentation)  to count in the total grade';
$string['illegalfilename'] = 'Illegal filename';
$string['problemnotfoundindatabase'] = 'problem  not found in database.';
$string['nofilesspecified'] = "No files specified.";
$string['nonmatchingnumberoffilenamesspecified'] = "Nonmatching (number of) filenames specified.";
$string['duplicatefilenamesdetected'] = "Duplicate filenames detected.";
$string['language'] = "Language";
$string['notfoundindatabaseornotsubmittable'] = "not found in database or not submittable.";
$string['readytojudge'] = 'Ready to be judged';
$string['rejudgeall'] = 'Rejudge all';
$string['rejudgeallnotice'] = 'Rejudging all submissions may take a long time. Do you want to continue?';
$string['rejudgeallrequestsent'] = 'The requests of rejudging all submissions have been sent.';
$string['rejudgefailed'] = 'Can not submitted rejudge request.';
$string['rejudgelater'] = 'The judge daemon is very busy. Please retry later.';
$string['rejudgesuccess'] = 'Rejudge request has been submitted successfully.';
$string['requestjudge'] = 'Request judge';
$string['runtimeout'] = 'Runtime output';
$string['statistics'] = 'Statistics';
$string['status'] = 'Status';
$string['status_help'] = 'Status indicates the results given by the online judge. The meanings are listed below:

* Abnormal Termination - Your program did not return 0 after exiting. Grade is 0.
* Accepted - Pass. Grade is the sum of all grades got from all avaliable test cases.
* Compilation Error - The compiler does not believe the code is correct. Grade is 0.
* Compilation OK - If the assignment is set as <em>compile only</em>, and your code pass the compilation, then this status is returned. No grade.
* Internal Error - The internal system is misconfigured or the judge does not work. Only administrator can solve this problem. No grade.
* Memory-Limit Exceed - Your program has used up the maximum memory allowed. Grade is 0.
* Multi-Status - There are more than one test case and the judge results of each test case are not unique. Check <em>information</em> for details. Grade is sum of all grades got from each passed test case.
* Output-Limit Exceed - Your program has outputted too much. Check whether there is any infinite loop which keep outputting. Grade is 0.
* Pending - Your program is waiting in the judge queue. Be patient please. However, if you have been waiting for a long time, perhaps there is something wrong with the online judge. No grade.
* Presentation Error - All the tokens in your output are correct. But the separators (e.g. White spaces, carriage returns, tabs) are different with the standard answer.  Grade is from 0 to maximum. Depends on assignment setting.
* Restricted Functions - Your program has called some dangerous system functions. Grade is 0.
* Runtime Error - Your program performed an illegal operation. Perhaps it was an attempt to access unaccessible memory or call illegal instructions. Grade is 0.
* Time-Limit Exceed - Your program has used up the maximum CPU time allowed. Grade is 0.
* Wrong Answer - The output of your program does not match with the standard answer. Grade is 0.';
$string['subgrade'] = 'Grade';
$string['subgrade_help'] = 'How many points can students obtain after passing the test.

If the assignment\'s max grade is set to 50, and this testcase\'s grade is set to 20%, then students who pass the test will earn 10 points and who can not pass will get zero. The final grade is the sum of all points gotten from each testcase. If the sum is larger than the assignment\'s max grade, the max grade will be used as the final grade.

The sum of all testcases\' grades is <em>not</em> required to be 100%. Therefore, you can leave some points for manual grading if the sum is below 100%. And also, you can make the sum be over 100% so that not all testcases are required to pass.';
$string['successrate'] = 'Success rate';
$string['testcases'] = 'Test Cases';
$string['testcases_help'] = 'Each testcase will be applied to the submissions and judged seperately. E.g. if there are three testcases, one submission will run three times to test different case.';
$string['typeonlinejudge'] = 'Online Judge';
$string['usefile'] = 'Testcase from files';
$string['waitingforjudge'] = 'Waiting for judge';
$string['gradefortestcases'] = 'Part of grade for test cases';

$string['sample'] = 'Sample';
$string['name'] = 'Name';
$string['indentation_style'] = 'Indentation style';
$string['indentationstyleassignment'] = 'Indentation style for this assigment';
$string['gradeforindentation'] = 'Part of grade for indentation';

$string['documentatiostyle'] = 'Documentatio style';
$string['requireauthortag'] = 'Require @author tag';
$string['requireversiontag'] = 'Require @version tag';
$string['requirecopyrighttag'] = 'Require @copyright tag';
$string['requirelicensetag'] = 'Require @license tag';
$string['requirepackagetag'] = 'Require @package tag';
$string['name_help'] = 'This is the name of the style, is mandatory that each style has a name that is different from the rest. Ps; You can not delete or update a style you have not created';
$string['documentationstyleassignment'] = 'Documentation style for this assigment';
$string['gradefordocumentation'] = 'Part of grade for documentation';

$string['checkassignmentnotes'] = 'Check assignment notes';
$string['checkcourseperformance'] = 'Check course performance';


