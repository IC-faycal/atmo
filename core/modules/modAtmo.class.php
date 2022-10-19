<?php
/* Copyright (C) 2004-2018  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2018-2019  Nicolas ZABOURI         <info@inovea-conseil.com>
 * Copyright (C) 2019       Frédéric France         <frederic.france@netlogic.fr>
 * Copyright (C) 2021 Copyright (C) 2021 Nicolas ZABOURI   <info@inovea-conseil.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * 	\defgroup   atmo     Module Atmo
 *  \brief      Atmo module descriptor.
 *
 *  \file       htdocs/atmo/core/modules/modAtmo.class.php
 *  \ingroup    atmo
 *  \brief      Description and activation file for module Atmo
 */
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/**
 *  Description and activation class for module Atmo
 */
class modAtmo extends DolibarrModules
{
    /**
     * Constructor. Define names, constants, directories, boxes, permissions
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        global $langs, $conf;
        $this->db = $db;

        // Id for module (must be unique).
        // Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
        $this->numero = 432514; // TODO Go on page https://wiki.dolibarr.org/index.php/List_of_modules_id to reserve an id number for your module
        // Key text used to identify module (for permissions, menus, etc...)
        $this->rights_class = 'atmo';
        // Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'Inovea Conseil','...'
        // It is used to group modules by family in module setup page
        $this->family = "Inovea Conseil";
        // Module position in the family on 2 digits ('01', '10', '20', ...)
        $this->module_position = '90';
        // Gives the possibility for the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
        //$this->familyinfo = array('myownfamily' => array('position' => '01', 'label' => $langs->trans("MyOwnFamily")));
        // Module label (no space allowed), used if translation string 'ModuleAtmoName' not found (Atmo is name of module).
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        // Module description, used if translation string 'ModuleAtmoDesc' not found (Atmo is name of module).
        $this->description = "AtmoDescription";
        // Used only if file README.md and README-LL.md not found.
        $this->descriptionlong = "Atmo description (Long)";
        $this->editor_name = 'Inovea Conseil';
        $this->editor_url = 'https://www.inovea-conseil.com';
        // Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated' or a version string like 'x.y.z'
        $this->version = '1.0';
        // Url to the file with your last numberversion of this module
        //$this->url_last_version = 'http://www.example.com/versionmodule.txt';

        // Key used in llx_const table to save module status enabled/disabled (where ATMO is value of property name of module in uppercase)
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        // Name of image file used for this module.
        // If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
        // If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
        $this->picto = 'generic';
        // Define some features supported by module (triggers, login, substitutions, menus, css, etc...)
        $this->module_parts = array(
            // Set this to 1 if module has its own trigger directory (core/triggers)
            'triggers' => 0,
            // Set this to 1 if module has its own login method file (core/login)
            'login' => 0,
            // Set this to 1 if module has its own substitution function file (core/substitutions)
            'substitutions' => 0,
            // Set this to 1 if module has its own menus handler directory (core/menus)
            'menus' => 0,
            // Set this to 1 if module overwrite template dir (core/tpl)
            'tpl' => 0,
            // Set this to 1 if module has its own barcode directory (core/modules/barcode)
            'barcode' => 0,
            // Set this to 1 if module has its own models directory (core/modules/xxx)
            'models' => 0,
            // Set this to 1 if module has its own theme directory (theme)
            'theme' => 0,
            // Set this to relative path of css file if module has its own css file
            'css' => array(//    '/atmo/css/atmo.css.php',
            ),
            // Set this to relative path of js file if module must load a js on all pages
            'js' => array(//   '/atmo/js/atmo.js.php',
            ),
            // Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context to 'all'
            'hooks' => array('thirdpartylist'),
            // Set this to 1 if features of module are opened to external users
            'moduleforexternal' => 0,
        );
        // Data directories to create when module is enabled.
        // Example: this->dirs = array("/atmo/temp","/atmo/subdir");
        $this->dirs = array("/atmo/temp");
        // Config pages. Put here list of php page, stored into atmo/admin directory, to use to setup module.
        $this->config_page_url = array("setup.php@atmo");
        // Dependencies
        // A condition to hide module
        $this->hidden = false;
        // List of module class names as string that must be enabled if this module is enabled. Example: array('always1'=>'modModuleToEnable1','always2'=>'modModuleToEnable2', 'FR1'=>'modModuleToEnableFR'...)
        $this->depends = array();
        $this->requiredby = array(); // List of module class names as string to disable if this one is disabled. Example: array('modModuleToDisable1', ...)
        $this->conflictwith = array(); // List of module class names as string this module is in conflict with. Example: array('modModuleToDisable1', ...)
        $this->langfiles = array("atmo@atmo");
        $this->phpmin = array(5, 5); // Minimum version of PHP required by module
        $this->need_dolibarr_version = array(11, -3); // Minimum version of Dolibarr required by module
        $this->warnings_activation = array(); // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        $this->warnings_activation_ext = array(); // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        //$this->automatic_activation = array('FR'=>'AtmoWasAutomaticallyActivatedBecauseOfYourCountryChoice');
        //$this->always_enabled = true;								// If true, can't be disabled

        // Constants
        // List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
        // Example: $this->const=array(1 => array('ATMO_MYNEWCONST1', 'chaine', 'myvalue', 'This is a constant to add', 1),
        //                             2 => array('ATMO_MYNEWCONST2', 'chaine', 'myvalue', 'This is anInovea Conseil constant to add', 0, 'current', 1)
        // );
        $this->const = array(// 1 => array('ATMO_MYCONSTANT', 'chaine', 'avalue', 'This is a constant to add', 1, 'allentities', 1)
        );

        // Some keys to add into the overwriting translation tables
        /*$this->overwrite_translation = array(
            'en_US:ParentCompany'=>'Parent company or reseller',
            'fr_FR:ParentCompany'=>'Maison mère ou revendeur'
        )*/

        if (!isset($conf->atmo) || !isset($conf->atmo->enabled)) {
            $conf->atmo = new stdClass();
            $conf->atmo->enabled = 0;
        }

        // Array to add new pages in new tabs
        $this->tabs = array();
        // Example:
        // $this->tabs[] = array('data'=>'objecttype:+tabname1:Title1:mylangfile@atmo:$user->rights->atmo->read:/atmo/mynewtab1.php?id=__ID__');  					// To add a new tab identified by code tabname1
        // $this->tabs[] = array('data'=>'objecttype:+tabname2:SUBSTITUTION_Title2:mylangfile@atmo:$user->rights->Inovea Conseilmodule->read:/atmo/mynewtab2.php?id=__ID__',  	// To add anInovea Conseil new tab identified by code tabname2. Label will be result of calling all substitution functions on 'Title2' key.
        // $this->tabs[] = array('data'=>'objecttype:-tabname:NU:conditiontoremove');                                                     										// To remove an existing tab identified by code tabname
        //
        // Where objecttype can be
        // 'categories_x'	  to add a tab in category view (replace 'x' by type of category (0=product, 1=supplier, 2=customer, 3=member)
        // 'contact'          to add a tab in contact view
        // 'contract'         to add a tab in contract view
        // 'group'            to add a tab in group view
        // 'intervention'     to add a tab in intervention view
        // 'invoice'          to add a tab in customer invoice view
        // 'invoice_supplier' to add a tab in supplier invoice view
        // 'member'           to add a tab in fundation member view
        // 'opensurveypoll'	  to add a tab in opensurvey poll view
        // 'order'            to add a tab in customer order view
        // 'order_supplier'   to add a tab in supplier order view
        // 'payment'		  to add a tab in payment view
        // 'payment_supplier' to add a tab in supplier payment view
        // 'product'          to add a tab in product view
        // 'propal'           to add a tab in propal view
        // 'project'          to add a tab in project view
        // 'stock'            to add a tab in stock view
        // 'thirdparty'       to add a tab in third party view
        // 'user'             to add a tab in user view

        // Dictionaries
        $this->dictionaries = array();
        /* Example:
        $this->dictionaries=array(
            'langs'=>'atmo@atmo',
            // List of tables we want to see into dictonnary editor
            'tabname'=>array(MAIN_DB_PREFIX."table1", MAIN_DB_PREFIX."table2", MAIN_DB_PREFIX."table3"),
            // Label of tables
            'tablib'=>array("Table1", "Table2", "Table3"),
            // Request to select fields
            'tabsql'=>array('SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table1 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table2 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table3 as f'),
            // Sort order
            'tabsqlsort'=>array("label ASC", "label ASC", "label ASC"),
            // List of fields (result of select to show dictionary)
            'tabfield'=>array("code,label", "code,label", "code,label"),
            // List of fields (list of fields to edit a record)
            'tabfieldvalue'=>array("code,label", "code,label", "code,label"),
            // List of fields (list of fields for insert)
            'tabfieldinsert'=>array("code,label", "code,label", "code,label"),
            // Name of columns with primary key (try to always name it 'rowid')
            'tabrowid'=>array("rowid", "rowid", "rowid"),
            // Condition to show each dictionary
            'tabcond'=>array($conf->atmo->enabled, $conf->atmo->enabled, $conf->atmo->enabled)
        );
        */

        // Boxes/Widgets
        // Add here list of php file(s) stored in atmo/core/boxes that contains a class to show a widget.
        $this->boxes = array(
            //  0 => array(
            //      'file' => 'atmowidget1.php@atmo',
            //      'note' => 'Widget provided by Atmo',
            //      'enabledbydefaulton' => 'Home',
            //  ),
            //  ...
        );

        // Cronjobs (List of cron jobs entries to add when module is enabled)
        // unit_frequency must be 60 for minute, 3600 for hour, 86400 for day, 604800 for week
        $this->cronjobs = array(
            //  0 => array(
            //      'label' => 'MyJob label',
            //      'jobtype' => 'method',
            //      'class' => '/atmo/class/myobject.class.php',
            //      'objectname' => 'MyObject',
            //      'method' => 'doScheduledJob',
            //      'parameters' => '',
            //      'comment' => 'Comment',
            //      'frequency' => 2,
            //      'unitfrequency' => 3600,
            //      'status' => 0,
            //      'test' => '$conf->atmo->enabled',
            //      'priority' => 50,
            //  ),
        );
        // Example: $this->cronjobs=array(
        //    0=>array('label'=>'My label', 'jobtype'=>'method', 'class'=>'/dir/class/file.class.php', 'objectname'=>'MyClass', 'method'=>'myMethod', 'parameters'=>'param1, param2', 'comment'=>'Comment', 'frequency'=>2, 'unitfrequency'=>3600, 'status'=>0, 'test'=>'$conf->atmo->enabled', 'priority'=>50),
        //    1=>array('label'=>'My label', 'jobtype'=>'command', 'command'=>'', 'parameters'=>'param1, param2', 'comment'=>'Comment', 'frequency'=>1, 'unitfrequency'=>3600*24, 'status'=>0, 'test'=>'$conf->atmo->enabled', 'priority'=>50)
        // );

        // Permissions provided by this module
        $this->rights = array();
        $r = 0;
        // Add here entries to declare new permissions
        /* BEGIN MODULEBUILDER PERMISSIONS */
        $this->rights[$r][0] = $this->numero . $r; // Permission id (must not be already used)
        $this->rights[$r][1] = 'Read objects of Atmo'; // Permission label
        $this->rights[$r][4] = 'myobject'; // In php code, permission will be checked by test if ($user->rights->atmo->level1->level2)
        $this->rights[$r][5] = 'read'; // In php code, permission will be checked by test if ($user->rights->atmo->level1->level2)
        $r++;
        $this->rights[$r][0] = $this->numero . $r; // Permission id (must not be already used)
        $this->rights[$r][1] = 'Create/Update objects of Atmo'; // Permission label
        $this->rights[$r][4] = 'myobject'; // In php code, permission will be checked by test if ($user->rights->atmo->level1->level2)
        $this->rights[$r][5] = 'write'; // In php code, permission will be checked by test if ($user->rights->atmo->level1->level2)
        $r++;
        $this->rights[$r][0] = $this->numero . $r; // Permission id (must not be already used)
        $this->rights[$r][1] = 'Delete objects of Atmo'; // Permission label
        $this->rights[$r][4] = 'myobject'; // In php code, permission will be checked by test if ($user->rights->atmo->level1->level2)
        $this->rights[$r][5] = 'delete'; // In php code, permission will be checked by test if ($user->rights->atmo->level1->level2)
        $r++;
        /* END MODULEBUILDER PERMISSIONS */

        // Main menu entries to add
        $this->menu = array();
        $r = 0;
        // Add here entries to declare new menus
        /* BEGIN MODULEBUILDER TOPMENU */
        /*$this->menu[$r++] = array(
            'fk_menu'=>'', // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
            'type'=>'top', // This is a Top menu entry
            'titre'=>'Atmo',
            'mainmenu'=>'atmo',
            'leftmenu'=>'',
            'url'=>'/atmo/atmoindex.php',
            'langs'=>'atmo@atmo', // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position'=>1000 + $r,
            'enabled'=>'$conf->atmo->enabled', // Define condition to show or hide menu entry. Use '$conf->atmo->enabled' if entry must be visible if module is enabled.
            'perms'=>'$user->rights->atmo->myobject->read', // Use 'perms'=>'$user->rights->atmo->level1->level2' if you want your menu with a permission rules
            'target'=>'',
            'user'=>2, // 0=Menu for internal users, 1=external users, 2=both
        );*/
        /* END MODULEBUILDER TOPMENU */
        /* BEGIN MODULEBUILDER LEFTMENU MYOBJECT
        $this->menu[$r++]=array(
            'fk_menu'=>'fk_mainmenu=atmo',      // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
            'type'=>'left',                          // This is a Top menu entry
            'titre'=>'MyObject',
            'mainmenu'=>'atmo',
            'leftmenu'=>'myobject',
            'url'=>'/atmo/atmoindex.php',
            'langs'=>'atmo@atmo',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position'=>1000+$r,
            'enabled'=>'$conf->atmo->enabled',  // Define condition to show or hide menu entry. Use '$conf->atmo->enabled' if entry must be visible if module is enabled.
            'perms'=>'$user->rights->atmo->myobject->read',			                // Use 'perms'=>'$user->rights->atmo->level1->level2' if you want your menu with a permission rules
            'target'=>'',
            'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
        );
        $this->menu[$r++]=array(
            'fk_menu'=>'fk_mainmenu=atmo,fk_leftmenu=myobject',	    // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
            'type'=>'left',			                // This is a Left menu entry
            'titre'=>'List MyObject',
            'mainmenu'=>'atmo',
            'leftmenu'=>'atmo_myobject_list',
            'url'=>'/atmo/myobject_list.php',
            'langs'=>'atmo@atmo',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position'=>1000+$r,
            'enabled'=>'$conf->atmo->enabled',  // Define condition to show or hide menu entry. Use '$conf->atmo->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
            'perms'=>'$user->rights->atmo->myobject->read',			                // Use 'perms'=>'$user->rights->atmo->level1->level2' if you want your menu with a permission rules
            'target'=>'',
            'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
        );
        $this->menu[$r++]=array(
            'fk_menu'=>'fk_mainmenu=atmo,fk_leftmenu=myobject',	    // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
            'type'=>'left',			                // This is a Left menu entry
            'titre'=>'New MyObject',
            'mainmenu'=>'atmo',
            'leftmenu'=>'atmo_myobject_new',
            'url'=>'/atmo/myobject_page.php?action=create',
            'langs'=>'atmo@atmo',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position'=>1000+$r,
            'enabled'=>'$conf->atmo->enabled',  // Define condition to show or hide menu entry. Use '$conf->atmo->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
            'perms'=>'$user->rights->atmo->myobject->write',			                // Use 'perms'=>'$user->rights->atmo->level1->level2' if you want your menu with a permission rules
            'target'=>'',
            'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
        );
        END MODULEBUILDER LEFTMENU MYOBJECT */

        // Exports profiles provided by this module
        $r = 1;
        /* BEGIN MODULEBUILDER EXPORT MYOBJECT */
        /*
        $langs->load("atmo@atmo");
        $this->export_code[$r]=$this->rights_class.'_'.$r;
        $this->export_label[$r]='MyObjectLines';	// Translation key (used only if key ExportDataset_xxx_z not found)
        $this->export_icon[$r]='myobject@atmo';
        // Define $this->export_fields_array, $this->export_TypeFields_array and $this->export_entities_array
        $keyforclass = 'MyObject'; $keyforclassfile='/mymobule/class/myobject.class.php'; $keyforelement='myobject@atmo';
        include DOL_DOCUMENT_ROOT.'/core/commonfieldsinexport.inc.php';
        //$this->export_fields_array[$r]['t.fieldtoadd']='FieldToAdd'; $this->export_TypeFields_array[$r]['t.fieldtoadd']='Text';
        //unset($this->export_fields_array[$r]['t.fieldtoremove']);
   		//$keyforclass = 'MyObjectLine'; $keyforclassfile='/atmo/class/myobject.class.php'; $keyforelement='myobjectline@atmo'; $keyforalias='tl';
		//include DOL_DOCUMENT_ROOT.'/core/commonfieldsinexport.inc.php';
        $keyforselect='myobject'; $keyforaliasextra='extra'; $keyforelement='myobject@atmo';
        include DOL_DOCUMENT_ROOT.'/core/extrafieldsinexport.inc.php';
        //$keyforselect='myobjectline'; $keyforaliasextra='extraline'; $keyforelement='myobjectline@atmo';
        //include DOL_DOCUMENT_ROOT.'/core/extrafieldsinexport.inc.php';
        //$this->export_dependencies_array[$r] = array('myobjectline'=>array('tl.rowid','tl.ref')); // To force to activate one or several fields if we select some fields that need same (like to select a unique key if we ask a field of a child to avoid the DISTINCT to discard them, or for computed field than need several Inovea Conseil fields)
        //$this->export_special_array[$r] = array('t.field'=>'...');
        //$this->export_examplevalues_array[$r] = array('t.field'=>'Example');
        //$this->export_help_array[$r] = array('t.field'=>'FieldDescHelp');
        $this->export_sql_start[$r]='SELECT DISTINCT ';
        $this->export_sql_end[$r]  =' FROM '.MAIN_DB_PREFIX.'myobject as t';
        //$this->export_sql_end[$r]  =' LEFT JOIN '.MAIN_DB_PREFIX.'myobject_line as tl ON tl.fk_myobject = t.rowid';
        $this->export_sql_end[$r] .=' WHERE 1 = 1';
        $this->export_sql_end[$r] .=' AND t.entity IN ('.getEntity('myobject').')';
        $r++; */
        /* END MODULEBUILDER EXPORT MYOBJECT */

        // Imports profiles provided by this module
        $r = 1;
        /* BEGIN MODULEBUILDER IMPORT MYOBJECT */
        /*
         $langs->load("atmo@atmo");
         $this->export_code[$r]=$this->rights_class.'_'.$r;
         $this->export_label[$r]='MyObjectLines';	// Translation key (used only if key ExportDataset_xxx_z not found)
         $this->export_icon[$r]='myobject@atmo';
         $keyforclass = 'MyObject'; $keyforclassfile='/mymobule/class/myobject.class.php'; $keyforelement='myobject@atmo';
         include DOL_DOCUMENT_ROOT.'/core/commonfieldsinexport.inc.php';
         $keyforselect='myobject'; $keyforaliasextra='extra'; $keyforelement='myobject@atmo';
         include DOL_DOCUMENT_ROOT.'/core/extrafieldsinexport.inc.php';
         //$this->export_dependencies_array[$r]=array('mysubobject'=>'ts.rowid', 't.myfield'=>array('t.myfield2','t.myfield3')); // To force to activate one or several fields if we select some fields that need same (like to select a unique key if we ask a field of a child to avoid the DISTINCT to discard them, or for computed field than need several Inovea Conseil fields)
         $this->export_sql_start[$r]='SELECT DISTINCT ';
         $this->export_sql_end[$r]  =' FROM '.MAIN_DB_PREFIX.'myobject as t';
         $this->export_sql_end[$r] .=' WHERE 1 = 1';
         $this->export_sql_end[$r] .=' AND t.entity IN ('.getEntity('myobject').')';
         $r++; */
        /* END MODULEBUILDER IMPORT MYOBJECT */


        // Exports
        //--------
        $r = 1;
        global $conf, $user;

        $alias_product_perentity = empty($conf->global->MAIN_PRODUCT_PERENTITY_SHARED) ? "p" : "ppe";
        $this->export_code[$r] = $this->rights_class . '_' . $r;
        $this->export_label[$r] = 'exportTitle'; // Translation key (used only if key ExportDataset_xxx_z not found)
        $this->export_icon[$r] = 'invoice';
        $this->export_permission[$r] = array(array("facture", "facture", "export", "other"));

        $selectName = "CASE WHEN UPPER(ctc.code) = UPPER('BILLING') AND UPPER(ctc.element) = UPPER('facture') AND ctc.source = 'external' THEN CONCAT(t.lastname, ' ', t.firstname) ELSE s.nom END as s_nom";
        $selectAdress = "CASE WHEN UPPER(ctc.code) = UPPER('BILLING') AND UPPER(ctc.element) = UPPER('facture') AND ctc.source = 'external' THEN t.address ELSE s.address END as s_address";
        $selectZip = "CASE WHEN UPPER(ctc.code) = UPPER('BILLING') AND UPPER(ctc.element) = UPPER('facture') AND ctc.source = 'external' THEN t.zip ELSE s.zip END as s_zip";
        $selectTown = "CASE WHEN UPPER(ctc.code) = UPPER('BILLING') AND UPPER(ctc.element) = UPPER('facture') AND ctc.source = 'external' THEN t.town ELSE s.town END as s_town";

        $this->export_fields_array[$r] = array(
            's.rowid' => "IdCompany", $selectName => "CompanyName", 'ps.nom' => 'ParentCompany', 's.code_client' => 'CustomerCode', $selectAdress => 'Address', $selectZip => 'Zip', $selectTown => 'Town', 'c.code' => 'CountryCode', 'cd.nom' => 'State',
            's.phone' => 'Phone',
            's.siren' => 'ProfId1', 's.siret' => 'ProfId2', 's.ape' => 'ProfId3', 's.idprof4' => 'ProfId4',
            's.code_compta' => 'CustomerAccountancyCode',
            's.code_compta_fournisseur' => 'SupplierAccountancyCode',
            's.tva_intra' => 'VATIntra',
            'f.rowid' => "InvoiceId", 'f.ref' => "InvoiceRef", 'f.ref_client' => 'RefCustomer',
            'f.type' => "Type", 'f.datec' => "InvoiceDateCreation", 'f.datef' => "DateInvoice", 'f.date_lim_reglement' => "DateDue", 'f.total' => "TotalHT",
            'f.total_ttc' => "TotalTTC", 'f.tva' => "TotalVAT", 'f.localtax1' => 'LT1', 'f.localtax2' => 'LT2', 'f.paye' => "InvoicePaidCompletely", 'f.fk_statut' => 'InvoiceStatus', 'f.close_code' => 'EarlyClosingReason', 'f.close_note' => 'EarlyClosingComment',
            'none.rest' => 'Rest',
            'f.note_private' => "NotePrivate", 'f.note_public' => "NotePublic", 'f.fk_user_author' => 'CreatedById', 'uc.login' => 'CreatedByLogin',
            'f.fk_user_valid' => 'ValidatedById', 'uv.login' => 'ValidatedByLogin', 'pj.ref' => 'ProjectRef', 'pj.title' => 'ProjectLabel', 'fd.rowid' => 'LineId', 'fd.description' => "LineDescription",
            'fd.subprice' => "LineUnitPrice", 'fd.tva_tx' => "LineVATRate", 'fd.qty' => "LineQty", 'fd.total_ht' => "LineTotalHT", 'fd.total_tva' => "LineTotalVAT",
            'fd.total_ttc' => "LineTotalTTC", 'fd.date_start' => "DateStart", 'fd.date_end' => "DateEnd", 'fd.special_code' => 'SpecialCode',
            'fd.product_type' => "TypeOfLineServiceOrProduct", 'fd.fk_product' => 'ProductId', 'p.ref' => 'ProductRef', 'p.label' => 'ProductLabel',
            $alias_product_perentity . '.accountancy_code_sell' => 'ProductAccountancySellCode'
        );
        if (!empty($conf->multicurrency->enabled)) {
            $this->export_fields_array[$r]['f.multicurrency_code'] = 'Currency';
            $this->export_fields_array[$r]['f.multicurrency_tx'] = 'CurrencyRate';
            $this->export_fields_array[$r]['f.multicurrency_total_ht'] = 'MulticurrencyAmountHT';
            $this->export_fields_array[$r]['f.multicurrency_total_tva'] = 'MulticurrencyAmountVAT';
            $this->export_fields_array[$r]['f.multicurrency_total_ttc'] = 'MulticurrencyAmountTTC';
        }
        if (!empty($conf->cashdesk->enabled) || !empty($conf->takepos->enabled) || !empty($conf->global->INVOICE_SHOW_POS)) {
            $this->export_fields_array[$r]['f.module_source'] = 'Module';
            $this->export_fields_array[$r]['f.pos_source'] = 'POSTerminal';
        }
        $this->export_TypeFields_array[$r] = array(
            's.rowid' => 'Numeric', 's.nom' => 'Text', 'ps.nom' => 'Text', 's.code_client' => 'Text', 's.address' => 'Text', 's.zip' => 'Text', 's.town' => 'Text', 'c.code' => 'Text', 'cd.nom' => 'Text', 's.phone' => 'Text', 's.siren' => 'Text',
            's.siret' => 'Text', 's.ape' => 'Text', 's.idprof4' => 'Text', 's.code_compta' => 'Text', 's.code_compta_fournisseur' => 'Text', 's.tva_intra' => 'Text',
            'f.rowid' => 'Numeric', 'f.ref' => "Text", 'f.ref_client' => 'Text', 'f.type' => "Numeric", 'f.datec' => "Date", 'f.datef' => "Date", 'f.date_lim_reglement' => "Date",
            'f.total_ht' => "Numeric", 'f.total_ttc' => "Numeric", 'f.total_tva' => "Numeric", 'f.localtax1' => 'Numeric', 'f.localtax2' => 'Numeric', 'f.paye' => "Boolean", 'f.fk_statut' => 'Numeric', 'f.close_code' => 'Text', 'f.close_note' => 'Text',
            'none.rest' => "NumericCompute",
            'f.note_private' => "Text", 'f.note_public' => "Text", 'f.fk_user_author' => 'Numeric', 'uc.login' => 'Text', 'f.fk_user_valid' => 'Numeric', 'uv.login' => 'Text',
            'pj.ref' => 'Text', 'pj.title' => 'Text', 'fd.rowid' => 'Numeric', 'fd.label' => 'Text', 'fd.description' => "Text", 'fd.subprice' => "Numeric", 'fd.tva_tx' => "Numeric",
            'fd.qty' => "Numeric", 'fd.total_ht' => "Numeric", 'fd.total_tva' => "Numeric", 'fd.total_ttc' => "Numeric", 'fd.date_start' => "Date", 'fd.date_end' => "Date",
            'fd.special_code' => 'Numeric', 'fd.product_type' => "Numeric", 'fd.fk_product' => 'List:product:label', 'p.ref' => 'Text', 'p.label' => 'Text',
            $alias_product_perentity . '.accountancy_code_sell' => 'Text'
        );
        if (!empty($conf->cashdesk->enabled) || !empty($conf->takepos->enabled) || !empty($conf->global->INVOICE_SHOW_POS)) {
            $this->export_TypeFields_array[$r]['f.module_source'] = 'Text';
            $this->export_TypeFields_array[$r]['f.pos_source'] = 'Text';
        }
        $this->export_entities_array[$r] = array(
            's.rowid' => "company", 's.nom' => 'company', 'ps.nom' => 'company', 's.code_client' => 'company', 's.address' => 'company', 's.zip' => 'company', 's.town' => 'company', 'c.code' => 'company', 'cd.nom' => 'company', 's.phone' => 'company',
            's.siren' => 'company', 's.siret' => 'company', 's.ape' => 'company', 's.idprof4' => 'company', 's.code_compta' => 'company', 's.code_compta_fournisseur' => 'company',
            's.tva_intra' => 'company', 'pj.ref' => 'project', 'pj.title' => 'project', 'fd.rowid' => 'invoice_line', 'fd.label' => "invoice_line", 'fd.description' => "invoice_line",
            'fd.subprice' => "invoice_line", 'fd.total_ht' => "invoice_line", 'fd.total_tva' => "invoice_line", 'fd.total_ttc' => "invoice_line", 'fd.tva_tx' => "invoice_line",
            'fd.qty' => "invoice_line", 'fd.date_start' => "invoice_line", 'fd.date_end' => "invoice_line", 'fd.special_code' => 'invoice_line',
            'fd.product_type' => 'invoice_line', 'fd.fk_product' => 'product', 'p.ref' => 'product', 'p.label' => 'product', $alias_product_perentity . '.accountancy_code_sell' => 'product',
            'f.fk_user_author' => 'user', 'uc.login' => 'user', 'f.fk_user_valid' => 'user', 'uv.login' => 'user'
        );
        $this->export_special_array[$r] = array('none.rest' => 'getRemainToPay');
        $this->export_dependencies_array[$r] = array('invoice_line' => 'fd.rowid', 'product' => 'fd.rowid', 'none.rest' => array('f.rowid', 'f.total_ttc', 'f.close_code')); // To add unique key if we ask a field of a child to avoid the DISTINCT to discard them
        $keyforselect = 'facture';
        $keyforelement = 'invoice';
        $keyforaliasextra = 'extra';
        include DOL_DOCUMENT_ROOT . '/core/extrafieldsinexport.inc.php';
        $keyforselect = 'facturedet';
        $keyforelement = 'invoice_line';
        $keyforaliasextra = 'extra2';
        include DOL_DOCUMENT_ROOT . '/core/extrafieldsinexport.inc.php';
        $keyforselect = 'product';
        $keyforelement = 'product';
        $keyforaliasextra = 'extra3';
        include DOL_DOCUMENT_ROOT . '/core/extrafieldsinexport.inc.php';
        $keyforselect = 'societe';
        $keyforelement = 'company';
        $keyforaliasextra = 'extra4';
        include DOL_DOCUMENT_ROOT . '/core/extrafieldsinexport.inc.php';
        $this->export_sql_start[$r] = 'SELECT DISTINCT ';
        $this->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'societe as s';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'societe_extrafields as extra4 ON s.rowid = extra4.fk_object';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'societe as ps ON ps.rowid = s.parent';
        if (empty($user->rights->societe->client->voir)) {
            $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'societe_commerciaux as sc ON sc.fk_soc = s.rowid';
        }
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_country as c on s.fk_pays = c.rowid';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_departements as cd on s.fk_departement = cd.rowid,';
        $this->export_sql_end[$r] .= ' ' . MAIN_DB_PREFIX . 'facture as f';

        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'element_contact AS ec on ec.element_id = f.rowid';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_type_contact AS ctc on ctc.rowid = ec.fk_c_type_contact';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'socpeople AS t on ec.fk_socpeople = t.rowid';


        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'projet as pj ON f.fk_projet = pj.rowid';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'user as uc ON f.fk_user_author = uc.rowid';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'user as uv ON f.fk_user_valid = uv.rowid';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'facture_extrafields as extra ON f.rowid = extra.fk_object';
        $this->export_sql_end[$r] .= ' , ' . MAIN_DB_PREFIX . 'facturedet as fd';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'facturedet_extrafields as extra2 on fd.rowid = extra2.fk_object';
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'product as p on (fd.fk_product = p.rowid)';
        if (!empty($conf->global->MAIN_PRODUCT_PERENTITY_SHARED)) {
            $this->export_sql_end[$r] .= " LEFT JOIN " . MAIN_DB_PREFIX . "product_perentity as ppe ON ppe.fk_product = p.rowid AND ppe.entity = " . ((int)$conf->entity);
        }
        $this->export_sql_end[$r] .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'product_extrafields as extra3 on p.rowid = extra3.fk_object';
        $this->export_sql_end[$r] .= ' WHERE f.fk_soc = s.rowid AND f.rowid = fd.fk_facture';
        $this->export_sql_end[$r] .= ' AND f.entity IN (' . getEntity('invoice') . ')';

        if (empty($user->rights->societe->client->voir)) {
            $this->export_sql_end[$r] .= ' AND sc.fk_user = ' . (empty($user) ? 0 : $user->id);
        }
        $this->export_sql_end[$r] .= ' GROUP BY f.rowid';
    }
    /**
     *  Function called when module is enabled.
     *  The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     *  It also creates data directories
     *
     *  @param      string  $options    Options when enabling module ('', 'noboxes')
     *  @return     int             	1 if OK, 0 if KO
     */
    public function init($options = '')
    {
        global $conf, $langs;

        $result = $this->_load_tables('/atmo/sql/');
        if ($result < 0) return -1; // Do not activate module if error 'not allowed' returned when loading module SQL queries (the _load_table run sql with run_sql with the error allowed parameter set to 'default')

        // Create extrafields during init
        //include_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
        //$extrafields = new ExtraFields($this->db);
        //$result1=$extrafields->addExtraField('myattr1', "New Attr 1 label", 'boolean', 1,  3, 'thirdparty',   0, 0, '', '', 1, '', 0, 0, '', '', 'atmo@atmo', '$conf->atmo->enabled');
        //$result2=$extrafields->addExtraField('myattr2', "New Attr 2 label", 'varchar', 1, 10, 'project',      0, 0, '', '', 1, '', 0, 0, '', '', 'atmo@atmo', '$conf->atmo->enabled');
        //$result3=$extrafields->addExtraField('myattr3', "New Attr 3 label", 'varchar', 1, 10, 'bank_account', 0, 0, '', '', 1, '', 0, 0, '', '', 'atmo@atmo', '$conf->atmo->enabled');
        //$result4=$extrafields->addExtraField('myattr4', "New Attr 4 label", 'select',  1,  3, 'thirdparty',   0, 1, '', array('options'=>array('code1'=>'Val1','code2'=>'Val2','code3'=>'Val3')), 1,'', 0, 0, '', '', 'atmo@atmo', '$conf->atmo->enabled');
        //$result5=$extrafields->addExtraField('myattr5', "New Attr 5 label", 'text',    1, 10, 'user',         0, 0, '', '', 1, '', 0, 0, '', '', 'atmo@atmo', '$conf->atmo->enabled');

        // Permissions
        $this->remove($options);

        $sql = array();

        // ODT template
        /*
        $src=DOL_DOCUMENT_ROOT.'/install/doctemplates/atmo/template_myobjects.odt';
        $dirodt=DOL_DATA_ROOT.'/doctemplates/atmo';
        $dest=$dirodt.'/template_myobjects.odt';

        if (file_exists($src) && ! file_exists($dest))
        {
            require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
            dol_mkdir($dirodt);
            $result=dol_copy($src, $dest, 0, 0);
            if ($result < 0)
            {
                $langs->load("errors");
                $this->error=$langs->trans('ErrorFailToCopyFile', $src, $dest);
                return 0;
            }
        }

        $sql = array(
            "DELETE FROM ".MAIN_DB_PREFIX."document_model WHERE nom = '".$this->db->escape($this->const[0][2])."' AND type = 'atmo' AND entity = ".$conf->entity,
            "INSERT INTO ".MAIN_DB_PREFIX."document_model (nom, type, entity) VALUES('".$this->db->escape($this->const[0][2])."','atmo',".$conf->entity.")"
        );
        */

        return $this->_init($sql, $options);
    }

    /**
     *  Function called when module is disabled.
     *  Remove from database constants, boxes and permissions from Dolibarr database.
     *  Data directories are not deleted
     *
     *  @param      string	$options    Options when enabling module ('', 'noboxes')
     *  @return     int                 1 if OK, 0 if KO
     */
    public function remove($options = '')
    {
        $sql = array();
        return $this->_remove($sql, $options);
    }
}
