<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  MEN AT WORK 2011 
 * @package    Language
 * @license    GNU/LGPL 
 * @filesource
 */
 
/**
 * Headline
 */
$GLOBALS['TL_LANG']['syncCto_check']['check'] = "Systemcheck";
$GLOBALS['TL_LANG']['syncCto_check']['configuration'] = "PHP Konfiguration";
 
/**
 * Table
 */
$GLOBALS['TL_LANG']['syncCto_check']['parameter'] = "Parameter";
$GLOBALS['TL_LANG']['syncCto_check']['value'] = "Wert";
$GLOBALS['TL_LANG']['syncCto_check']['description'] = "Beschreibung";
$GLOBALS['TL_LANG']['syncCto_check']['on'] = "An";
$GLOBALS['TL_LANG']['syncCto_check']['off'] = "Aus";
$GLOBALS['TL_LANG']['syncCto_check']['safemode'] = array("Safe mode", "Die empfohlene Einstellung ist Aus.");
$GLOBALS['TL_LANG']['syncCto_check']['met'] = array("Maximum execution time", "Die empfohlene Einstellung ist 30 oder höher.");
$GLOBALS['TL_LANG']['syncCto_check']['memory_limit'] = array("Memory limit", "Die empfohlene Einstellung ist 128M oder höher.");
$GLOBALS['TL_LANG']['syncCto_check']['register_globals'] = array("Register globals", "Die empfohlene Einstellung ist Aus.");
$GLOBALS['TL_LANG']['syncCto_check']['file_uploads'] = array("File uploads", "Die empfohlene Einstellung ist An.");
$GLOBALS['TL_LANG']['syncCto_check']['umf'] = array("Upload maximum filesize", "Die empfohlene Einstellung ist 8M oder höher.");
$GLOBALS['TL_LANG']['syncCto_check']['pms'] = array("Post maximum size", "Die empfohlene Einstellung ist 8M oder höher.");
$GLOBALS['TL_LANG']['syncCto_check']['mit'] = array("Maximum input time", "Die empfohlene Einstellung ist -1, 60 oder höher.");
$GLOBALS['TL_LANG']['syncCto_check']['dst'] = array("Default socket timeout", "Die empfohlene Einstellung ist 30 oder höher.");
$GLOBALS['TL_LANG']['syncCto_check']['fsocket'] = array("Fsockopen", "Die empfohlene Einstellung ist An.");
$GLOBALS['TL_LANG']['syncCto_check']['fopen'] = array("Fopen", "Die empfohlene Einstellung ist An.");
$GLOBALS['TL_LANG']['syncCto_check']['zip_archive'] = array("ZipArchive", "Die empfohlene Einstellung ist An.");

/**
 * Text
 */
$GLOBALS['TL_LANG']['syncCto_check']['other_sync_issues'] = "Weitere Problemfälle";
$GLOBALS['TL_LANG']['syncCto_check']['explanation_sync_issues'] = "In machen Fällen kann eine Synchronisation trotz eines guten Systemchecks nicht ausgeführt werden.";
$GLOBALS['TL_LANG']['syncCto_check']['known_issues'] = "Folgende fehlerhafte Konfigurationen sind bekannt:";
$GLOBALS['TL_LANG']['syncCto_check']['suhosin'] = "Suhosin verhindert die Synchronisation";
$GLOBALS['TL_LANG']['syncCto_check']['max_request_len'] = "Der MaxRequestLen ist zu gering";

$GLOBALS['TL_LANG']['syncCto_check']['safemodehack'] = 'syncCto_check kann aufgrund fehlender Schreibrechte nicht ausgeführt werden.';

?>