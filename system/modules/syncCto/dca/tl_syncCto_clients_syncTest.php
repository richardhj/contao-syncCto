<?php

if (!defined('TL_ROOT'))
    die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */
$GLOBALS['TL_DCA']['tl_syncCto_clients_syncTest'] = array(
    // Config
    'config' => array(
        'dataContainer' => 'Memory',
//        'closed'          => true,
//        'disableSubmit'   => false,
//        'onload_callback' => array(
//            array('tl_syncCto_clients_syncTest', 'onload_callback'),
//            array('tl_syncCto_clients_syncTest', 'checkVersion'),
//            array('tl_syncCto_clients_syncTest', 'checkPermission'),
//        ),
//        'onsubmit_callback' => array(
//            array('tl_syncCto_clients_syncTest', 'onsubmit_callback'),
//        )
    ),
    // Palettes
    'palettes'      => array(
        '__selector__' => array('database_check', 'systemoperations_check'),
        'default'     => '{Datei Synchronisation},syncro_choose;{Datenbank Syncronisation},database_check;{Optimierung / Aufräumen},systemoperations_check;',
    ),
    'subpalettes' => array(
        'database_check' => 'database_tables_recommended,database_tables_none_recommended',
        'systemoperations_check' => 'systemoperations_maintenance',
    ),
    // Fields
    'fields' => array(
        'syncro_choose' => array(
            'label' => array("Datei Syncronisation", "Wählen Sie aus welche Teile synchronisiert werden sollen."),
            'inputType' => 'checkbox',
            'options'   => array(
                "Contao Kern" => array("Neue / Veränderte Dateien", "Löschbare Dateien"),
                "Benutzer Dateien" => array("Neue / Veränderte Dateien", "Löschbare Dateien"),
            ),
            'exclude' => true,
            'eval'    => array('multiple'      => true, 'checkAll' => true)
        ),
         //------------------------
        'systemoperations_check' => array(
            'label' => array("Systemwartung", "Wählen Sie dies Option wenn Sie die Datenbank synchronisieren wollen."),
            'inputType' => 'checkbox',
            'exclude' => true,
            'eval' => array('submitOnChange' => true, 'tl_class'=>'clr'),
        ),
       'systemoperations_maintenance' => array(
            'label' => array("Systemwartung", "Hier können Sie Systemwartung auf dem entfernten System starten."),
            'inputType' => 'checkbox',
            'options'   => array(
                "tl_Search neu aufbauen",
                "Temporäretabellen leeren",
                "Temporäreordner leeren",
                "CSS-Dateien erstellen",
                "XML-Dateien erstellen"
            ),
            'exclude' => true,
            'eval'    => array('multiple'      => true, 'checkAll' => true)
        ),
        //------------------------
        'database_check' => array(
            'label' => array("Datenbank Syncronisation", "Wählen Sie dies Option wenn Sie die Datenbank synchronisieren wollen."),
            'inputType' => 'checkbox',
            'exclude' => true,
            'eval' => array('submitOnChange' => true, 'tl_class'=>'clr'),
        ),
        'database_tables_recommended' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncTest']['database_tables_recommended'],
            'inputType' => 'checkbox',
            'exclude'   => true,
            'eval'      => array('multiple'         => true),
            'options_callback' => array('SyncCtoHelper', 'databaseTablesRecommended'),
        ),
        'database_tables_none_recommended' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncTest']['database_tables_none_recommended'],
            'inputType' => 'checkbox',
            'exclude'   => true,
            'eval'      => array('multiple'         => true),
            'options_callback' => array('SyncCtoHelper', 'databaseTablesNoneRecommended'),
        ),
    )
);

/**
 * Class for syncTo configurations
 */
class tl_syncCto_clients_syncTest extends Backend
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->BackendUser = BackendUser::getInstance();

        parent::__construct();
    }

    /**
     * Set new and remove old buttons
     * 
     * @param DataContainer $dc 
     */
    public function onload_callback(DataContainer $dc)
    {
        // Add/Remove some buttons

        $dc->removeButton('save');
        $dc->removeButton('saveNclose');

        $arrData = array
            (
            'id'              => 'start_sync',
            'formkey'         => 'start_sync',
            'class'           => '',
            'accesskey'       => 'g',
            'value'           => specialchars("Client synchronisieren"),
            'button_callback' => array('tl_syncCto_clients_syncTest', 'onsubmit_callback')
        );

        $dc->addButton('start_sync', $arrData);

//        // Update a field with last sync information
//        $objSyncTime = $this->Database->prepare("SELECT cl.syncTo_tstamp as syncTo_tstamp, user.name as syncTo_user, user.username as syncTo_alias
//                                            FROM tl_synccto_clients as cl 
//                                            INNER JOIN tl_user as user
//                                            ON cl.syncTo_user = user.id
//                                            WHERE cl.id = ?")
//                ->limit(1)
//                ->execute($this->Input->get("id"));
//
//        if (strlen($objSyncTime->syncTo_tstamp) != 0 && strlen($objSyncTime->syncTo_user) != 0 && strlen($objSyncTime->syncTo_alias) != 0)
//        {
//            $strLastSync = vsprintf($GLOBALS['TL_LANG']['MSC']['information_last_sync'], array(
//                date($GLOBALS['TL_CONFIG']['timeFormat'], $objSyncTime->syncTo_tstamp),
//                date($GLOBALS['TL_CONFIG']['dateFormat'], $objSyncTime->syncTo_tstamp),
//                $objSyncTime->syncTo_user,
//                $objSyncTime->syncTo_alias)
//            );
//
//            // Set data
//            $dc->setData("lastSync", "<p class='tl_info'>" . $strLastSync . "</p><br />");
//        }
//        else
//        {
//            $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTo']['palettes']['default'] = str_replace(",lastSync", "", $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTo']['palettes']['default']);
//        }
    }

    /**
     * Set new and remove old buttons
     * 
     * @param DataContainer $dc 
     */
    public function checkVersion(DataContainer $dc)
    {
//        if (version_compare(VERSION . '.' . BUILD, '2.10.0', '<'))
//        {
//            $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTest']['palettes']['default'] = str_replace('sync_type,purgeData', 'sync_type', $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTo']['palettes']['default']);
//        }
    }

    /**
     * Handle syncTo configurations
     * 
     * @param DataContainer $dc
     * @return array 
     */
    public function onsubmit_callback(DataContainer $dc)
    {

//        // Check sync. typ
//        if (strlen($this->Input->post('sync_type')) != 0)
//        {
//            if ($this->Input->post('sync_type') == SYNCCTO_FULL || $this->Input->post('sync_type') == SYNCCTO_SMALL)
//            {
//                $this->Session->set("syncCto_Typ", $this->Input->post('sync_type'));
//            }
//            else
//            {
//                $_SESSION["TL_ERROR"][] = $GLOBALS['TL_LANG']['ERR']['unknown_function'];
//                return;
//            }
//        }
//        else
//        {
//            $this->Session->set("syncCto_Typ", SYNCCTO_SMALL);
//        }
//
//        // Load table lists and merge them
//        if ($this->Input->post("database_tables_recommended") != "" || $this->Input->post("database_tables_none_recommended") != "")
//        {
//            if ($this->Input->post("database_tables_recommended") != "" && $this->Input->post("database_tables_none_recommended") != "")
//            {
//                $arrSyncTables = array_merge($this->Input->post("database_tables_recommended"), $this->Input->post("database_tables_none_recommended"));
//            }
//            else if ($this->Input->post("database_tables_recommended"))
//            {
//                $arrSyncTables = $this->Input->post("database_tables_recommended");
//            }
//            else if ($this->Input->post("database_tables_none_recommended"))
//            {
//                $arrSyncTables = $this->Input->post("database_tables_none_recommended");
//            }
//
//            $this->Session->set("syncCto_SyncTables", $arrSyncTables);
//        }
//        else
//        {
//            $this->Session->set("syncCto_SyncTables", FALSE);
//        }
//
//        // Set purgeDataflag    
//        if ($this->Input->post("purgeData") == 1)
//        {
//            $this->Session->set("syncCto_PurgeData", TRUE);
//        }
//        else
//        {
//            $this->Session->set("syncCto_PurgeData", FALSE);
//        }
//
//        // Files for backup siles       
//        if (is_array($this->Input->post('filelist')) && count($this->Input->post('filelist')) != 0)
//        {
//            $this->Session->set("syncCto_Filelist", $this->Input->post('filelist', true));
//        }
//        else
//        {
//            $this->Session->set("syncCto_Filelist", FALSE);
//        }
//
//        $this->Session->set("syncCto_Start", microtime(true));
//
//        $this->Session->set("syncCto_StepPool1", FALSE);
//        $this->Session->set("syncCto_StepPool2", FALSE);
//        $this->Session->set("syncCto_StepPool3", FALSE);
//        $this->Session->set("syncCto_StepPool4", FALSE);
//        $this->Session->set("syncCto_StepPool5", FALSE);
//        $this->Session->set("syncCto_StepPool6", FALSE);
//
//        $arrContenData = array(
//            "error"       => false,
//            "error_msg"   => "",
//            "refresh"     => true,
//            "finished"    => false,
//            "step"        => 1,
//            "url"         => "contao/main.php?do=synccto_clients&amp;table=tl_syncCto_clients_syncTo&amp;act=start&amp;id=" . (int) $this->Input->get("id"),
//            "goBack"      => "contao/main.php?do=synccto_clients",
//            "start"       => microtime(true),
//            "headline"    => $GLOBALS['TL_LANG']['tl_syncCto_clients_syncTo']['edit'],
//            "information" => "",
//            "data"        => array(),
//            "abort" => false,
//        );
//
//        $this->Session->set("syncCto_Content", $arrContenData);
//
//        $this->redirect($this->Environment->base . "contao/main.php?do=synccto_clients&amp;table=tl_syncCto_clients_syncTo&amp;act=start&amp;id=" . $this->Input->get("id"));
    }

    /**
     * Check user permission
     * 
     * @return string
     */
    public function checkPermission()
    {
        if ($this->BackendUser->isAdmin)
        {
            return;
        }

        $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTest']['list']['sorting']['root'] = $this->BackendUser->filemounts;
    }

}

?>