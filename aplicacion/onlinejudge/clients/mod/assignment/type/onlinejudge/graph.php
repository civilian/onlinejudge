<?php
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
//                      Online UV Judge for Moodle                       //
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
 * Documentation management form
 * 
 * @package   local_online_uv_judge
 * @author    Oscar Chamat
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../../../config.php');
include $CFG->dirroot . '/lib/graphlib.php';

$graph = new graph(700,700);
$graph->parameter['title']         = 'Histograma Notas Estudiantes';
$graph->parameter['x_label']       = 'Nota Entregada';
$graph->parameter['y_label_left']  = 'Cantidad Estudiantes';
$graph->parameter['legend']        = 'outside-top';
$graph->parameter['legend_border'] = 'black';
$graph->parameter['legend_offset'] = 4;

$graph->x_data          = array('100%', '80%', '60%', '40%', '20%', '0%');

$graph->y_data['XYZ40'] = array(8.610, 7.940, 3.670, 3.670, 6.940, 8.650);
$graph->y_data['DEF20'] = array(4.896, 4.500, 4.190, 3.450, 2.888, 3.678);
$graph->y_data['ABC10'] = array(1.456, 3.001, 5.145, 3.150, 1.998, 1.678);


$graph->y_format['XYZ40'] =
  array('colour' => 'blue', 'bar' => 'fill', 'shadow_offset' => 2, 'legend' => 'Correctitud');

$graph->y_format['ABC10'] =
  array('colour' => 'red', 'bar' => 'fill', 'brush_size' => 2, 'shadow_offset' => 4, 'legend' => 'Documentacion');

$graph->y_format['DEF20'] =
  array('colour' => 'green', 'bar' => 'fill', 'shadow_offset' => 2, 'legend' => 'Indentacion');


$graph->parameter['bar_size']    = 1.5; // make size > 1 to get overlap effect
$graph->parameter['bar_spacing'] = 30; // don't forget to increase spacing so that graph doesn't become one big block of colour

$graph->y_order = array('XYZ40', 'DEF20', 'ABC10');

$graph->parameter['x_axis_rot']  = 60; // rotate x_axis text to 60 degrees.
$graph->parameter['x_grid']      = 'none'; // no x grid

$graph->parameter['y_min_left']  = 0;
$graph->parameter['y_max_left']  = 10;
$graph->parameter['y_decimal_left'] = 2;
$graph->draw();