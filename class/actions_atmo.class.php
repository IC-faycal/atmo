<?php
/* Copyright (C) 2022 SuperAdmin <vincent.crassier@atmo-occitanie.org>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    atmo/class/actions_atmo.class.php
 * \ingroup atmo
 * \brief   Example hook overload.
 *
 * Put detailed description here.
 */

/**
 * Class ActionsAtmo
 */
class ActionsAtmo
{
    /**
     * @var DoliDB Database handler.
     */
    public $db;

    /**
     * @var string Error code (or message)
     */
    public $error = '';

    /**
     * @var array Errors
     */
    public $errors = array();


    /**
     * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
     */
    public $results = array();

    /**
     * @var string String displayed by executeHook() immediately after return
     */
    public $resprints;


    /**
     * Constructor
     *
     *  @param		DoliDB		$db      Database handler
     */
    public function __construct($db)
    {
        $this->db = $db;
    }


    /**
     * Execute action
     *
     * @param	array			$parameters		Array of parameters
     * @param	CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
     * @param	string			$action      	'add', 'update', 'view'
     * @return	int         					<0 if KO,
     *                           				=0 if OK but we want to process standard actions too,
     *                            				>0 if OK and we want to replace standard actions.
     */
    public function getNomUrl($parameters, &$object, &$action)
    {
        global $db,$langs,$conf,$user;
        $this->resprints = '';
        return 0;
    }

    /**
     * Overloading the doActions function : replacing the parent's function with the one below
     *
     * @param   array           $parameters     Hook metadatas (context, etc...)
     * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
     * @param   string          $action         Current action (if set). Generally create or edit or null
     * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
     * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
     */
    public function doActions($parameters, &$object, &$action, $hookmanager)
    {
        global $conf, $user, $langs, $db;

        $error = 0; // Error counter

        if (in_array($parameters['currentcontext'], array('thirdpartylist')))	    // do something only for the context 'somecontext1' or 'somecontext2'
        {
            // Do what you want here...
            // You can for example call global vars like $fieldstosearchall to overwrite them, or update database depending on $action and $_POST values.
            require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
            require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';

            $langs->load("atmo@atmo");

            $extrafields = new ExtraFields($db);
            $extrafields->fetch_name_optionals_label("societe");

            print "<script src='/includes/jquery/js/jquery.min.js'></script>";

            if (!empty($extrafields->attribute_param["trfact"]["options"])) {
                foreach ($extrafields->attribute_param["trfact"]["options"] as $key => $value) {
                    $const = 'atmo_' . $key;
                    $color = $conf->global->$const;

                    print " <script>
                        $(document).ready(function() {
                            const items = document.querySelectorAll('.select2-search-choice-dolibarr');

                            items.forEach(function(item) {
                              let txtItem = item.innerHTML;
                              if (txtItem.normalize() === '" . $value . "') {
                                  item.style.padding = '10px 5px 10px 5px';
                                  item.style.backgroundColor = '" . $color . "';
                              }
                            });
                        });
                    </script>";
                }
            }

            $formcompany = new FormCompany($db);
            $typeCompany = $formcompany->typent_array(0); // Liste des type de Tiers
            $colorThirparty = array();

            foreach ($typeCompany as $key => $value) {
                $color = 'atmo_type_' . $key;
                if (!empty($value)) {
                    $colorThirparty[$color] = array("label" => $value, "color" => $conf->global->$color);
                }
            }

            if (!empty($colorThirparty)) {
                foreach ($colorThirparty as $key => $value) {
                    print " <script>
                    $(document).ready(function() {
                        const items = document.querySelectorAll('table > tbody > tr > td');

                        items.forEach( function(item) {
                            let content = item.innerHTML;
                            if (item.innerHTML == '" . $value['label'] . "'){
                                let style = 'background: #" . $value['color'] . "; border-radius: 5px; padding: 10px 5px 10px 5px';
                                item.innerHTML = '<span style=\'' + style + '\'>' + content + '</span>';
                            }
                        })
                    });
                    </script>";
                }
            }
        }

        if (! $error) {
            $this->results = array('myreturn' => 999);
            $this->resprints = 'A text to show';
            return 0; // or return 1 to replace standard code
        } else {
            $this->errors[] = 'Error message';
            return -1;
        }
    }


    /**
     * Overloading the doActions function : replacing the parent's function with the one below
     *
     * @param   array           $parameters     Hook metadatas (context, etc...)
     * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
     * @param   string          $action         Current action (if set). Generally create or edit or null
     * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
     * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
     */
    public function doMassActions($parameters, &$object, &$action, $hookmanager)
    {
        global $conf, $user, $langs;

        $error = 0; // Error counter

        /* print_r($parameters); print_r($object); echo "action: " . $action; */
        if (in_array($parameters['currentcontext'], array('somecontext1','somecontext2')))		// do something only for the context 'somecontext1' or 'somecontext2'
        {
            foreach($parameters['toselect'] as $objectid)
            {
                // Do action on each object id
            }
        }

        if (! $error) {
            $this->results = array('myreturn' => 999);
            $this->resprints = 'A text to show';
            return 0; // or return 1 to replace standard code
        } else {
            $this->errors[] = 'Error message';
            return -1;
        }
    }


    /**
     * Overloading the addMoreMassActions function : replacing the parent's function with the one below
     *
     * @param   array           $parameters     Hook metadatas (context, etc...)
     * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
     * @param   string          $action         Current action (if set). Generally create or edit or null
     * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
     * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
     */
    public function addMoreMassActions($parameters, &$object, &$action, $hookmanager)
    {
        global $conf, $user, $langs;

        $error = 0; // Error counter

        /* print_r($parameters); print_r($object); echo "action: " . $action; */
        if (in_array($parameters['currentcontext'], array('somecontext1','somecontext2')))		// do something only for the context 'somecontext1' or 'somecontext2'
        {
            $this->resprints = '<option value="0"'.($disabled?' disabled="disabled"':'').'>'.$langs->trans("AtmoMassAction").'</option>';
        }

        if (! $error) {
            return 0; // or return 1 to replace standard code
        } else {
            $this->errors[] = 'Error message';
            return -1;
        }
    }



    /**
     * Execute action
     *
     * @param	array	$parameters     Array of parameters
     * @param   Object	$object		   	Object output on PDF
     * @param   string	$action     	'add', 'update', 'view'
     * @return  int 		        	<0 if KO,
     *                          		=0 if OK but we want to process standard actions too,
     *  	                            >0 if OK and we want to replace standard actions.
     */
    public function beforePDFCreation($parameters, &$object, &$action)
    {
        global $conf, $user, $langs;
        global $hookmanager;

        $outputlangs=$langs;

        $ret=0; $deltemp=array();
        dol_syslog(get_class($this).'::executeHooks action='.$action);

        /* print_r($parameters); print_r($object); echo "action: " . $action; */
        if (in_array($parameters['currentcontext'], array('somecontext1','somecontext2')))		// do something only for the context 'somecontext1' or 'somecontext2'
        {
        }

        return $ret;
    }

    /**
     * Execute action
     *
     * @param	array	$parameters     Array of parameters
     * @param   Object	$pdfhandler     PDF builder handler
     * @param   string	$action         'add', 'update', 'view'
     * @return  int 		            <0 if KO,
     *                                  =0 if OK but we want to process standard actions too,
     *                                  >0 if OK and we want to replace standard actions.
     */
    public function afterPDFCreation($parameters, &$pdfhandler, &$action)
    {
        global $conf, $user, $langs;
        global $hookmanager;

        $outputlangs=$langs;

        $ret=0; $deltemp=array();
        dol_syslog(get_class($this).'::executeHooks action='.$action);

        /* print_r($parameters); print_r($object); echo "action: " . $action; */
        if (in_array($parameters['currentcontext'], array('somecontext1','somecontext2')))		// do something only for the context 'somecontext1' or 'somecontext2'
        {
        }

        return $ret;
    }

    /* Add here any other hooked methods... */
}
