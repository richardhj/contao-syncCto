<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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

$GLOBALS['TL_DCA']['tl_syncCto_clients_syncTo'] = array(
    // Config
    'config' => array
        (
        'dataContainer' => 'Memory',
        'closed' => true,
        'disableSubmit' => false,
        'onload_callback' => array(
            array('tl_syncCto_clients_syncTo', 'onload_callback'),
            array('tl_syncCto_clients_syncTo', 'checkPermission'),
        ),
        'onsubmit_callback' => array(
            array('tl_syncCto_clients_syncTo', 'onsubmit_callback'),
        )
    ),
    // Palettes
    'palettes' => array
        (
        '210' => '{sync_legend},sync_type,purgeData;{table_recommend_legend},database_tables_recommended;{table_none_recommend_legend},database_tables_none_recommended;{filelist_legend},filelist',
        '209' => '{sync_legend},sync_type;{table_recommend_legend},database_tables_recommended;{table_none_recommend_legend},database_tables_none_recommended;{filelist_legend},filelist',
    ),
    // Fields
    'fields' => array(
        'sync_type' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncTo']['sync_type'],
            'inputType' => 'select',
            'exclude' => true,
            'eval' => array('helpwizard' => true),
            'reference' => &$GLOBALS['TL_LANG']['SYC'],
            'options_callback' => array('SyncCtoHelper', 'getSyncType'),
        ),
        'database_tables_recommended' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncTo']['database_tables_recommended'],
            'inputType' => 'checkbox',
            'exclude' => true,
            'eval' => array('multiple' => true),
            'options_callback' => array('SyncCtoHelper', 'databaseTablesRecommended'),
        ),
        'database_tables_none_recommended' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncTo']['database_tables_none_recommended'],
            'inputType' => 'checkbox',
            'exclude' => true,
            'eval' => array('multiple' => true),
            'options_callback' => array('SyncCtoHelper', 'databaseTablesNoneRecommended'),
        ),
        'filelist' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncTo']['filelist'],
            'inputType' => 'fileTree',
            'exclude' => true,
            'eval' => array('files' => true, 'filesOnly' => false, 'fieldType' => 'checkbox'),
        ),
        'purgeData' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncTo']['purgeData'],
            'inputType' => 'checkbox',
            'exclude' => true,
            'eval' => array('multiple' => false),
        ),
    )
);

// Show a other palette for 2.9 and 2.10
if(version_compare("2.10", VERSION, "<") == true)
{
    $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTo']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTo']['palettes']['209'];
}
else
{
    $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTo']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTo']['palettes']['210'];
}

/**
 * Class for syncTo configurations
 */
class tl_syncCto_clients_syncTo extends Backend
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
        $dc->removeButton('save');
        $dc->removeButton('saveNclose');

        $arrData = array
            (
            'id' => 'start_sync',
            'formkey' => 'start_sync',
            'class' => '',
            'accesskey' => 'g',
            'value' => specialchars($GLOBALS['TL_LANG']['MSC']['syncTo']),
            'button_callback' => array('tl_syncCto_clients_syncTo', 'onsubmit_callback')
        );

        $dc->addButton('start_sync', $arrData);
    }

    /**
     * Handle syncTo configurations
     * 
     * @param DataContainer $dc
     * @return array 
     */
    public function onsubmit_callback(DataContainer $dc)
    {

        // Check sync. typ
        if (strlen($this->Input->post('sync_type')) != 0)
        {
            if ($this->Input->post('sync_type') == SYNCCTO_FULL || $this->Input->post('sync_type') == SYNCCTO_SMALL)
            {
                $this->Session->set("syncCto_Typ", $this->Input->post('sync_type'));
            }
            else
            {
                $_SESSION["TL_ERROR"][] = $GLOBALS['TL_LANG']['ERR']['unknown_function'];
                return;
            }
        }
        else
        {
            $this->Session->set("syncCto_Typ", SYNCCTO_SMALL);
        }

        // Load table lists and merge them
        if ($this->Input->post("database_tables_recommended") != "" || $this->Input->post("database_tables_none_recommended") != "")
        {
            if ($this->Input->post("database_tables_recommended") != "" && $this->Input->post("database_tables_none_recommended") != "")
            {
                $arrSyncTables = array_merge($this->Input->post("database_tables_recommended"), $this->Input->post("database_tables_none_recommended"));
            }
            else if ($this->Input->post("database_tables_recommended"))
            {
                $arrSyncTables = $this->Input->post("database_tables_recommended");
            }
            else if ($this->Input->post("database_tables_none_recommended"))
            {
                $arrSyncTables = $this->Input->post("database_tables_none_recommended");
            }

            $this->Session->set("syncCto_SyncTables", $arrSyncTables);
        }
        else
        {
            $this->Session->set("syncCto_SyncTables", FALSE);
        }
        
        // Set purgeDataflag    
        if($this->Input->post("purgeData") == 1)
        {
            $this->Session->set("syncCto_PurgeData", TRUE);
        }
        else
        {
            $this->Session->set("syncCto_PurgeData", FALSE);
        }

        // Files for backup siles       
        if (is_array($this->Input->post('filelist')) && count($this->Input->post('filelist')) != 0)
        {
            $this->Session->set("syncCto_Filelist", $this->Input->post('filelist', true));
        }
        else
        {
            $this->Session->set("syncCto_Filelist", FALSE);
        }

        $this->Session->set("syncCto_Start", microtime(true));

        $this->Session->set("syncCto_StepPool1", FALSE);
        $this->Session->set("syncCto_StepPool2", FALSE);
        $this->Session->set("syncCto_StepPool3", FALSE);
        $this->Session->set("syncCto_StepPool4", FALSE);
        $this->Session->set("syncCto_StepPool5", FALSE);
        $this->Session->set("syncCto_StepPool6", FALSE);

        $arrContenData = array(
            "error" => false,
            "error_msg" => "",
            "refresh" => true,
            "finished" => false,
            "step" => 1,
            "url" => "contao/main.php?do=synccto_clients&amp;table=tl_syncCto_clients_syncTo&amp;act=start&amp;id=" . (int) $this->Input->get("id"),
            "goBack" => "contao/main.php?do=synccto_clients",
            "start" => microtime(true),
            "headline" => $GLOBALS['TL_LANG']['tl_syncCto_clients_syncTo']['edit'],
            "information" => "",
            "data" => array()
        );

        $this->Session->set("syncCto_Content", $arrContenData);

        $this->redirect($this->Environment->base . "contao/main.php?do=synccto_clients&amp;table=tl_syncCto_clients_syncTo&amp;act=start&amp;id=" . $this->Input->get("id"));
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

        $GLOBALS['TL_DCA']['tl_syncCto_clients_syncTo']['list']['sorting']['root'] = $this->BackendUser->filemounts;
    }

}

?>