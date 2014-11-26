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
 * Strings for local_onlinejudge
 */

$string['about'] = 'Acerca de';
$string['aboutcontent'] = 'Online Judge Uv</a> esta en desarrollo, y es licenciado con <a href="http://www.gnu.org/copyleft/gpl.html">GPL</a>.';
$string['badvalue'] = 'Valor incorrecto';
$string['cannotrunsand'] = 'No puedes correr el sandbox';
$string['compileroutput'] = 'Mensajes del compilador';
$string['cpuusage'] = 'Uso de CPU';
$string['defaultlanguage'] = 'Lenguaje por defecto';
$string['defaultlanguage_help'] = 'Lenguaje por defecto para las nuevas tareas del juez en linea.';
$string['details'] = 'Detalles';
$string['ideoneautherror'] = 'Nombre de usuario incorrecto o contraseña incorrecta';
$string['ideonedelay'] = 'Retardo entre las peticiones a ideone.com (segundos)';
$string['ideonedelay_help'] = 'Si el tiempo transcurrido entre el envío de solicitudes al juez y el retorno de los resultados es demasiado corto, ideone.com los rechazará.';
$string['ideoneerror'] = 'Ideone retorna el error: {$a}';
$string['ideonelogo'] = '<a href="https://github.com/hit-moodle/moodle-local_onlinejudge">Juez Online moodle</a> uses <a href="http://ideone.com">Ideone API</a> &copy; by <a href="http://sphere-research.com">Sphere Research Labs</a>';
$string['ideoneresultlink'] = 'Vea los detalles en <a href="http://ideone.com/{$a}">http://ideone.com/{$a}</a>.';
$string['ideoneuserrequired'] = 'Es requerido si el juez ideone.com es seleccionado';
$string['judgehostdelay'] = 'Espacio de tiempo entre las peticiones a el servidor judgehost (segundos)';
$string['judgehostdelay_help'] = 'Si no hay espera entre las peticiones el juez remoto puede fallar';
$string['judgehostdbpass'] = 'Contraseña del usuario de la base de datos del Judgehost Domjudge';
$string['judgehostdbpass_help'] = 'Esta es la contraseña del usario que controla la base de datos de domjudge';
$string['judgehostdbname'] = 'Nombre de la base de datos de Judgehost Domjudge';
$string['judgehostdbname_help'] = 'Este es el nombre de la base de datos de judgehost domjudge';
$string['judgehostdbhost'] = 'Equipo de la base de datos de Domjudge Judgehost';
$string['judgehostdbhost_help'] = 'Esta es la dirección del equipo en donde esta la base de datos de judgehost domjudge, si es local entonces es localhost';
$string['judgehostdbuser'] = 'Usuario de la base de datos Domjudge Judgehost';
$string['judgehostdbuser_help'] = 'Este es el nombre de usuario en la base de datos del judgehost domjudge';
$string['judgehostdbexception'] = 'La información para la base de datos de Domjudge no esta propiamente configurada; No se puede conectar al servidor de la base de datos(host={$a->host},user={$a->user},password='.
            str_repeat('*', strlen($a->password)) . ' ,db={$a->db}). ';
$string['info'] = 'Información';
$string['info0'] = 'Si has esperado demasido, por favor informa al administrador';
$string['info1'] = 'Felicitaciones!!!';
$string['info2'] = 'Un buen programa debe retornar 0 si no tiene errores';
$string['info3'] = 'Al compilador no le gusto tu codigo';
$string['info4'] = 'Parece que al compilador le gusto tu código';
$string['info5'] = 'Utilizaste demasiada memoria';
$string['info6'] = 'Tu código ha generado demasiada stdout(demasiado texto)';
$string['info7'] = 'Casi perfecto, excepto por algunos espacios en blanco mal puestos o tabuladores o enters y etc';
$string['info8'] = 'LLamaste algunas funciones que son <em>prohibidas</em> ';
$string['info9'] = '[SIGSEGV, Falla de Segmento] Arreglo mal indexado, Mal acceso a un puntero o peor';
$string['info10'] = 'El envio ha estado corriendo por demasido tiempo';
$string['info11'] = 'Revisa tu código dos veces. No generes ningún error de tipografía o caracteres extra';
$string['info21'] = 'El motor del juez no funciona bien. Por favor contacte al administrador';
$string['info22'] = 'Si ha esperado demasido. Por favor contacte al administrador';
$string['infostudent'] = 'Información';
$string['infoteacher'] = 'Información sensitiva';
$string['invalidlanguage'] = 'ID de lenguaje no valida: {$a}';
$string['invalidjudgeclass'] = 'Clase de Juez no valida: {$a}';
$string['invalidtaskid'] = 'Id del envio no valida: {$a}';
$string['judgedcrashnotify'] = 'Notificación de que el demonio del juez ha fallado';
$string['judgedcrashnotify_help'] = 'El demonio del juez tal vez ha fallado o sea caido debido a fallas en el software o a una actualización. En ese caso, quien recibira la notificacion? Debe ser una persona que tenga acceso a la consola en el servidor para lanzar el demonio del juez.';
$string['judgednotifybody'] = 'Entre las {$a->count} tareas pendientes, la más vieja ha estado esperando en la cola por {$a->period}.
    
Es posible que el demonio del juez online halla fallado o se halla caido. Usted debe levantarlo tan pronto como sea posible!

O, es posible que hallan demasiados envios en la cola y debe considerar en correr multiples demonios para juzgar.';
$string['judgednotifysubject'] = '{$a->count} envios pendientes han esperado demasiado tiempo';
$string['judgestatus'] = 'El juez online ha juzgado <strong>{$a->judged}</strong> envios y hay <strong>{$a->pending}</strong> envios en la cola.';
$string['langc_sandbox'] = 'C (se corre localmente)';
$string['langc_warn2err_sandbox'] = 'C (se corre localmente, los warnings aparecen como errores)';
$string['langcpp_sandbox'] = 'C++ (corrido localmente)';
$string['langcpp_warn2err_sandbox'] = 'C++ (se corre localmente, los warnings aparecen como errores)';
$string['timechecktask'] = 'Lapso de tiempo hasta calificar el próximo envio(tarea) en segundos';
$string['timechecktask_help'] = 'Por cuánto tiempo el demonio debe esperar para chequear si hay un envio a una tarea para calificar.';
$string['timedaemonout'] = 'Lapso de tiempo para considerar que el demonio judgehost esta caido (second)';
$string['timedaemonout_help'] = 'Cuánto tiempo puede pasar una tarea sin juzgar para considerar que el demonio judgehost esta caido.';
$string['maxcpulimit'] = 'Maximo tiempo de uso de la CPU  (segundos)';
$string['maxcpulimit_help'] = 'Cuanto tiempo puede un programa correr en las pruebas.';
$string['maxmemlimit'] = 'Maximo uso de memoria (MB)';
$string['maxmemlimit_help'] = 'Cuanta memoria puede usar un programa al ser juzgado.';
$string['memusage'] = 'Uso de memoria';
$string['messageprovider:judgedcrashed'] = 'Notificación de fallo del demonio del juez online';
$string['mystat'] = 'Mis estadisticas';
$string['notesensitive'] = '* Mostrado solo a profesores';
$string['onefileonlyideone'] = 'Ideone.com no soporta archivos multiples';
$string['onlinejudge:viewjudgestatus'] = 'Ver el estatus del juez';
$string['onlinejudge:viewmystat'] = 'Ver estadísticas propias';
$string['onlinejudge:viewsensitive'] = 'Ver detalles sensibles';
$string['pluginname'] = 'Juez Online';
$string['sandboxerror'] = 'Se produce un error Sandbox: {$a}';
$string['settingsform'] = 'Ajustes del Juez Online';
$string['settingsupdated'] = 'Ajustes actualizados.';
$string['status0'] = 'Pendiente...';
$string['status1'] = 'Aceptado';
$string['status2'] = 'Terminado Abnormalmente';
$string['status3'] = 'Error en la compilación';
$string['status4'] = 'Compilación Exitosa';
$string['status5'] = 'Limite de Memoria Excedido';
$string['status6'] = 'Limite de Salida Excedido';
$string['status7'] = 'Error de Presentacion';
$string['status9'] = 'Error en tiempo de ejecución';
$string['status8'] = 'Utiliza funciones prohibidas';
$string['status10'] = 'Tiempo limite excedido';
$string['status11'] = 'Respuesta incorrecta';
$string['status21'] = 'Error interno';
$string['status22'] = 'Juzgando...';
$string['status23'] = 'Multi-estatus';
$string['status255'] = 'No ha sido enviado';
$string['stderr'] = 'Salida estandar de errores';
$string['stdout'] = 'Salida estandar';
$string['var1'] = 'Diferencias de las salidas';
$string['upgradenotify'] = 'No olvide correr cli/install_assignment_type y cli/judged.php. Detalles en <a href="https://github.com/hit-moodle/moodle-local_onlinejudge/blob/master/README.md" target="_blank">README</a>.';
$string['connect_error'] = 'No se puede conectar a la base de datos externa';

