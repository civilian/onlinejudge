<?php

///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
//                      Online UV Judge for Moodle                       //
//        https://bitbucket.org/civilian/uv_moodle_judge/src             //
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
 * Testcase management
 * 
 * @package   local_online_uv_judge
 * @author    Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function tescases_manage($fromform, $assignment, $oj){
    global $DBDOM, $DB;
//    $DBDOM->dbg("tescases_manage");
    //var_dump("management_lib.php tescases_manage");
    for ($i = 0; $i < $fromform->boundary_repeats; $i++) {
        //var_dump(" i "+  $i);
        if (emptycase($fromform, $i)) {
            if ($fromform->caseid[$i] != -1) {
                $DB->delete_records('assignment_oj_testcases', array('id' => $fromform->caseid[$i]));
                $fs = get_file_storage();
                $fs->delete_area_files($context->id, 'mod_assignment', 'onlinejudge_input', $fromform->caseid[$i]);
                $fs->delete_area_files($context->id, 'mod_assignment', 'onlinejudge_output', $fromform->caseid[$i]);
                $k = array(
                    'mdl_testcaseid' => $fromform->caseid[$i],
                );
                //Judging_run solo se utiliza para guardar los resultados entonces no importa
//                $DBDOM->q("DELETE FROM judging_run WHERE %S LIMIT 1", $k);
                $DBDOM->q("DELETE FROM testcase WHERE %S LIMIT 1", $k);
                $DBDOM->auditlog("testcase", implode(', ', $k), 'deleted');
            }
            continue;
        }
        if (isset($fromform->usefile[$i]) AND $fromform->usefile[$i] == 1) {
            $testcase->usefile = true;
            // Keep file as is
            $testcase->inputfile = $fromform->inputfile[$i]; //esto es mas bien un entero como una numeración de donde esta el archivo
            $testcase->outputfile = $fromform->outputfile[$i];
        } else {
            $testcase->usefile = false;
            // Translate textbox inputs to Unix text format
            $testcase->input = crlf2lf($fromform->input[$i]);
            $testcase->output = crlf2lf($fromform->output[$i]);
        }

        $testcase->feedback = $fromform->feedback[$i];
        $testcase->subgrade = $fromform->subgrade[$i];
        $testcase->assignment = $assignment->id;
        $testcase->id = $fromform->caseid[$i];
        $testcase->sortorder = $i;

        if ($testcase->id != -1) {
            $probid = $oj->id;
            $rank = $testcase->id;

            $DBDOM->q("UPDATE testcase SET md5sum_input  = %s, input = %s , md5sum_output  = %s, output = %s , description = %s
                                        WHERE probid = %s AND mdl_testcaseid = %i", md5($testcase->input), $testcase->input, md5($testcase->output), $testcase->output, $testcase->feedback, $probid, $testcase->id);
            $DBDOM->auditlog('testcase', $probid, 'updated', "output input description rank $rank");

            $DB->update_record('assignment_oj_testcases', $testcase);
        } else {

            if ($testcase->usefile) {//TODO: Posible
                $fs = get_file_storage();
//                if ($files = $fs->get_area_files($context->id, 'mod_assignment', 'onlinejudge_input', $testcase->inputfile)) {
//                    $file = array_pop($files);
//                    $record->input = $file->get_content();
//                }
//                if ($files = $fs->get_area_files($context->id, 'mod_assignment', 'onlinejudge_output', $record->id)) {
//                    $file = array_pop($files);
//                    $record->output = $file->get_content();
//                }
//                $testcase->input=file_save_draft_area_files($testcase->inputfile, $context->id, 'mod_assignment', 'onlinejudge_input', $testcase->id);
//                $testcase->output=file_save_draft_area_files($testcase->outputfile, $context->id, 'mod_assignment', 'onlinejudge_output', $testcase->id);
                //                $testcase->input=file_get_contents($testcase->inputfile);
//                $testcase->output=file_get_contents($testcase->outputfile);
                $testcase->usefile = false;
            }
            //var_dump("management_lib.php tescases_manage insert_record assignment_oj_testcases");
            $testcase->id = $DB->insert_record('assignment_oj_testcases', $testcase);
            $probid = $oj->id;
//            $rank = $DBDOM->q("VALUE SELECT count(rank) FROM testcase
//                   WHERE probid = %i", $probid) + 1;

            $DBDOM->q("INSERT INTO testcase
                                (mdl_testcaseid,probid,rank,md5sum_input,md5sum_output,input,output,description)
                                VALUES (%i,%s,%i,%s,%s,%s,%s,%s)", $testcase->id, $probid, $testcase->id, md5($testcase->input), md5($testcase->output), $testcase->input, $testcase->output, $testcase->feedback);
            $DBDOM->auditlog('testcase', $probid, 'added', "rank $testcase->id");
        }


        unset($testcase);
    }

//    $itemdata = array(
//        'testcases_grade' => 
//    );
//    $prikey = array(
//        'probid' => $oj->id
//    );
//    $DBDOM->q("UPDATE problem SET %S WHERE %S", $itemdata, $prikey);
    $oj->testcases_grade_worth = $fromform->select_grade;
    $DB->update_record('assignment_oj', $oj);
}

function fill_tescases($assignment, $context, $oj){
    global $DBDOM, $DB;
    $testcases = $DB->get_records('assignment_oj_testcases', array('assignment' => $assignment->id), 'sortorder ASC');
    $toform = array();
    if ($testcases) {
        $i = 0;
        foreach ($testcases as $tstObj => $tstValue) {
            $toform["input[$i]"] = $tstValue->input;
            $toform["output[$i]"] = $tstValue->output;
            $toform["feedback[$i]"] = $tstValue->feedback;
            $toform["subgrade[$i]"] = $tstValue->subgrade;
            $toform["usefile[$i]"] = $tstValue->usefile;
            $toform["caseid[$i]"] = $tstValue->id;
            file_prepare_draft_area($toform["inputfile[$i]"], $context->id, 'mod_assignment', 'onlinejudge_input', $tstValue->id, array('subdirs' => 0, 'maxfiles' => 1));
            file_prepare_draft_area($toform["outputfile[$i]"], $context->id, 'mod_assignment', 'onlinejudge_output', $tstValue->id, array('subdirs' => 0, 'maxfiles' => 1));

            $i++;
        }
        
        $toform["select_grade"] = $oj->testcases_grade_worth;
    }
    return $toform;
}

function styles_manage($fromform, $assignment, $oj){
    global $DBDOM, $DB, $USER;
    
    for ($i = 0; $i < $fromform->boundary_repeats; $i++) {
        if (emptystyle($fromform, $i)) {
            if ($fromform->styleid[$i] != -1) {

                //Deleting documentation style in all the problem who has it.
                $probs = $DBDOM->q('SELECT * FROM problem WHERE
                    documentation_style = %s', $fromform->styleid[$i]);
                $defaultid = $DBDOM->q("VALUE SELECT documentation_styleid 
                                        FROM documentation_style 
                                        WHERE name = 'default' AND langid = %s", $oj->language);
                while ($prob = $probs->next()) {
                    $itemdata = array(
                        'documentation_style' => $defaultid
                    );
                    $prikey = array(
                        'probid' => $prob['probid']
                    );
                    $DBDOM->q("UPDATE problem SET %S WHERE %S", $itemdata, $prikey);
                    $DBDOM->auditlog('problem', implode(', ', $prikey), 'updated', implode(', ', $itemdata), null);
                }

                $DBDOM->q("DELETE FROM documentation_style WHERE  documentation_styleid = %s AND name != 'default' LIMIT 1", $fromform->styleid[$i]);
                $k = array('documentation_styleid' => $fromform->styleid[$i]);
                $DBDOM->auditlog("documentation_style", implode(', ', $k), 'deleted');
            }
            continue;
        }

        $style->name = $fromform->name[$i];
        $style->author = $fromform->author[$i];
        $style->version = $fromform->version[$i];
        $style->copyright = $fromform->copyright[$i];
        $style->license = $fromform->license[$i];
        $style->package = $fromform->package[$i];
        $style->id = $fromform->styleid[$i];

//        $style->subgrade = $fromform->subgrade[$i];
        //var_dump("style");
        //var_dump($style);

        if ($style->id != -1) {

            $DBDOM->q("UPDATE documentation_style SET name = %s, author = %i , version  = %i, copyright = %i , license = %s, package = %i,
                                        langid = %s 
                                        WHERE documentation_styleid = %i", $style->name, $style->author, $style->version, $style->copyright, $style->license, $style->package, $oj->language, $style->id);
            $DBDOM->auditlog('documentation_style', $style->id, 'updated', "documentation update");

//            $DB->update_record('assignment_oj_testcases', $style);
        } else {
//            $rank = $DBDOM->q("VALUE SELECT count(rank) FROM testcase
//                   WHERE probid = %i", $probid) + 1;

            $DBDOM->q("INSERT INTO documentation_style
                                (name,author,version,copyright,license,package, langid, owner)
                                VALUES (%s, %i, %i, %i, %i, %i, %s, %i)", $style->name, $style->author, $style->version, $style->copyright, $style->license, $style->package, $oj->language, $USER->id);
            $DBDOM->auditlog('documentation_style', $style->id, 'added', "documentation added $style->id");
        }


        unset($style);
    }

    $exist = $DBDOM->q("MAYBEVALUE SELECT documentation_styleid
                            FROM documentation_style
                            WHERE documentation_styleid = %s AND langid = %s", $fromform->select_docum, $oj->language);
    if (!$exist) {
        $defaultid = $DBDOM->q("VALUE SELECT documentation_styleid
                            FROM documentation_style
                            WHERE name = 'default' AND langid = %s", $oj->language);
        //Avoid reallocating the deleted style
        $fromform->select_docum = $defaultid;
    }

    $itemdata = array(
        'documentation_style' => $fromform->select_docum);
    $prikey = array(
        'probid' => $oj->id);
    $DBDOM->q("UPDATE problem SET %S WHERE %S", $itemdata, $prikey);

    $oj->documentation_grade_worth = $fromform->select_grade;
    $DB->update_record('assignment_oj', $oj);
}

function fill_styles($assignment, $context, $oj){
    global $DBDOM, $DB;
    
    $res = $DBDOM->q("SELECT * FROM documentation_style WHERE langid = %s ", $oj->language);
//    $styles = $DB->get_records('assignment_oj_testcases', array('assignment' => $assignment->id), 'sortorder ASC');

    $toform = array();
    if ($res) {
        $i = 0;
        while ($row = $res->next()) {
            $toform["name[$i]"] = $row["name"];
            $toform["author[$i]"] = $row["author"];
            $toform["version[$i]"] = $row["version"];
            $toform["copyright[$i]"] = $row["copyright"];
            $toform["license[$i]"] = $row["license"];
            $toform["package[$i]"] = $row["package"];
            $toform["styleid[$i]"] = $row["documentation_styleid"];
            $toform["owner[$i]"] = $row["owner"];
//            $toform["retrn[$i]"] = $row["retrn"];//tener en cuenta que en la base de datos no se puede llamar return un campo

            $i++;
        }

        $st = $DBDOM->q("SELECT documentation_style, documentation_grade FROM problem WHERE probid = %s", $oj->id);
        $row = $st->next();
        $toform["select_docum"] = $row["documentation_style"]; //TODO: Solo consultarlo cuando se cambie
        $toform["select_grade"] = $oj->documentation_grade_worth;
    }

    return $toform;
}


function indentation_styles_manage($fromform, $assignment, $oj){
    global $DBDOM, $DB;
    for ($i = 0; $i < $fromform->boundary_repeats; $i++) {
        //emptystyle($fromform, $i);
        if (false) {//The crud for this is not yet neccesary
            if ($fromform->styleid[$i] != -1) {
//                $DB->delete_records('assignment_oj_testcases', array('id' => $fromform->caseid[$i]));
                $k = array(
                    'indentation_styleid' => $fromform->styleid[$i],
                );
//                $DBDOM->q("DELETE FROM judging_run WHERE %S LIMIT 1", $k);//TODO: OBLIGATORIO PRIMERO SABER COMO FUNCIONA EL DEMONIO
                $DBDOM->q("DELETE FROM indentation_style WHERE %S LIMIT 1", $k);
                $DBDOM->auditlog("indentation_style", implode(', ', $k), 'deleted');
            }
            continue;
        }

//        $style->assignment = $assignment->id;
        $style->id = $fromform->styleid[$i];
//        $style->sortorder = $i;

        if ($style->id != -1) {
            //UPDATE
            //de indentacion no se esta guardando nada
        } else {
            //INSERT
        }

        unset($style);
    }

    $itemdata = array(
        'indentation_style' => $fromform->select_indent
    );
    $prikey = array(
        'probid' => $oj->id
    );
    $DBDOM->q("UPDATE problem SET %S WHERE %S", $itemdata, $prikey);
    
    $oj->indentation_grade_worth = $fromform->select_grade;
    $DB->update_record('assignment_oj', $oj);
}
    
function fill_indentation_styles($assignment, $context, $oj){
    global $DBDOM, $DB;
    $res = $DBDOM->q("SELECT * FROM indentation_style WHERE langid = %s", $oj->language);
//    $styles = $DB->get_records('assignment_oj_testcases', array('assignment' => $assignment->id), 'sortorder ASC');

    $toform = array();
    if ($res) {
        $i = 0;
        while ($row = $res->next()) {
            $toform["name[$i]"] = $row["name"];
            $toform["sample[$i]"] = $row["sample"];
            $toform["styleid[$i]"] = $row["indentation_styleid"];
            $i++;
        }

        $st = $DBDOM->q("SELECT indentation_style, indentation_grade FROM problem WHERE probid = %s", $oj->id);
        $row = $st->next();
        $toform["select_indent"] = $row["indentation_style"];//TODO: Solo consultarlo cuando se cambie
        $toform["select_grade"] = $oj->indentation_grade_worth;
    }
    return $toform;
}

function emptystyle(&$form, $i) {
    return ( empty($form->name[$i]));
}

function emptycase(&$form, $i) {
    if ($form->subgrade[$i] != 0.0)
        return false;

    if (isset($form->usefile[$i]))
        return empty($form->inputfile[$i]) && empty($form->outputfile[$i]);
    else
        return empty($form->input[$i]) && empty($form->output[$i]);
}

/* translate cr+lf (\r\n) to lf (\n) */

function crlf2lf(&$text) {
    return strtr($text, array("\r\n" => "\n", "\n\r" => "\n"));
}