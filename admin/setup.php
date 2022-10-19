<?php
/* Copyright (C) 2004-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2021 Copyright (C) 2021 Nicolas ZABOURI   <info@inovea-conseil.com>
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
 * \file    atmo/admin/setup.php
 * \ingroup atmo
 * \brief   Atmo setup page.
 */

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) { $i--; $j--; }
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) $res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) $res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
// Try main.inc.php using relative path
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

global $langs, $user, $conf;

// Libraries
require_once DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php";
require_once '../lib/atmo.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
//require_once "../class/myclass.class.php";

// Translations
$langs->loadLangs(array("admin", "atmo@atmo"));

// Access control
if (!$user->admin) accessforbidden();

// Parameters
$action = GETPOST('action', 'alpha');
$backtopage = GETPOST('backtopage', 'alpha');
$arrayofparametersType = array();
$arrayofparameters = array();

global $db;

$formcompany = new FormCompany($db);
$formother = new FormOther($db);
$extrafields = new ExtraFields($db);

$extrafields->fetch_name_optionals_label("societe");

if (!empty($extrafields->attribute_param["trfact"]["options"])) {
    foreach ($extrafields->attribute_param["trfact"]["options"] as $key => $value) {
        if (!empty($value))
            $arrayofparameters['atmo_' . $key] = array('css'=>'minwidth200', 'enabled'=>1, "name" => $value);
    }
}

$typeCompany = $formcompany->typent_array(0); // Liste des type de Tiers

foreach ($typeCompany as $key => $value) {
    if (!empty($value)) {
        $arrayofparametersType['atmo_type_' . $key] = array('css'=>'minwidth200', 'enabled'=>1, "name" => $value);
    }
}

if ($action == "update") {
    foreach ($arrayofparametersType as $key => $value) {
        $color = GETPOST($key, 'alpha');
        if (!empty($color))
            dolibarr_set_const($db, $key, $color, "chaine", 1);
    }
}

/*
 * Actions
 */

if ((float) DOL_VERSION >= 6)
	include DOL_DOCUMENT_ROOT.'/core/actions_setmoduleoptions.inc.php';

/*
 * View
 */

$page_name = "AtmoSetup";
llxHeader('', $langs->trans($page_name));

// Subheader
$linkback = '<a href="'.($backtopage ? $backtopage : DOL_URL_ROOT.'/admin/modules.php?restore_lastsearch_values=1').'">'.$langs->trans("BackToModuleList").'</a>';

print load_fiche_titre($langs->trans($page_name), $linkback, 'object_atmo@atmo');

// Configuration header
$head = atmoAdminPrepareHead();
dol_fiche_head($head, 'settings', '', -1, "atmo@atmo");

// Setup page goes here
echo '<span class="opacitymedium">'.$langs->trans("AtmoSetupPage").'</span><br><br>';

if ($action == 'edit')
{
	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'?action=update">';
	print '<input type="hidden" name="token" value="'.newToken().'">';
	print '<input type="hidden" name="action" value="update">';

    print "<h4>" . $langs->trans('TitleAdminColorInvoice') . "</h4>";
	print '<table class="noborder centpercent">';
	print '<tr class="liste_titre"><td class="titlefield">'.$langs->trans("Parameter").'</td><td>'.$langs->trans("Value").'</td></tr>';

	foreach ($arrayofparameters as $key => $val)
	{
        $color = GETPOST('color');
		print '<tr class="oddeven"><td>';
		$tooltiphelp = (($langs->trans($key.'Tooltip') != $key.'Tooltip') ? $langs->trans($key.'Tooltip') : '');
		print $val["name"];
		print '</td><td>' . $formother->selectColor($conf->global->$key, $key). '</td></tr>';
	}
	print '</table>';

    print "<h4>" . $langs->trans('TitleAdminColorThirdparty') . "</h4>";
    print '<table class="noborder centpercent">';
    print '<tr class="liste_titre"><td class="titlefield">'.$langs->trans("Parameter").'</td><td>'.$langs->trans("Value").'</td></tr>';

    foreach ($arrayofparametersType as $key => $val)
    {
        $color = GETPOST('color');
        print '<tr class="oddeven"><td>';
        $tooltiphelp = (($langs->trans($key.'Tooltip') != $key.'Tooltip') ? $langs->trans($key.'Tooltip') : '');
        print $val["name"];
        print '</td><td>' . $formother->selectColor($conf->global->$key, $key). '</td></tr>';
    }
    print '</table>';

	print '<br><div class="center">';
	print '<input class="button" type="submit" value="'.$langs->trans("Save").'">';
	print '</div>';

	print '</form>';
	print '<br>';
}
else
{
	if (!empty($arrayofparameters))
	{
        $style = "-webkit-appearance: none; width: 35px; height: 35px; border: 0; border-radius: 50%; padding: 0; overflow: hidden; box-shadow: 2px 2px 5px rgba(0,0,0,.1)";

        print "<h4>" . $langs->trans('TitleAdminColorInvoice') . "</h4>";
		print '<table class="noborder centpercent">';
		print '<tr class="liste_titre"><td class="titlefield">'.$langs->trans("Parameter").'</td><td>'.$langs->trans("Value").'</td></tr>';
		foreach ($arrayofparameters as $key => $val)
		{
			print '<tr class="oddeven"><td>';
			$tooltiphelp = (($langs->trans($key.'Tooltip') != $key.'Tooltip') ? $langs->trans($key.'Tooltip') : '');
			print $val["name"];
			print '</td><td>'. "<input type='color' value=#" . $conf->global->$key . " style='" . $style . "'></td></tr>";
			//print '</td><td>'.$conf->global->$key.'</td></tr>';
		}

		print '</table>';

        print "<h4>" . $langs->trans('TitleAdminColorThirdparty') . "</h4>";
        print '<table class="noborder centpercent">';
        print '<tr class="liste_titre"><td class="titlefield">'.$langs->trans("Parameter").'</td><td>'.$langs->trans("Value").'</td></tr>';
        foreach ($arrayofparametersType as $key => $val)
        {
            print '<tr class="oddeven"><td>';
            $tooltiphelp = (($langs->trans($key.'Tooltip') != $key.'Tooltip') ? $langs->trans($key.'Tooltip') : '');
            print $val["name"];
            print '</td><td>'. "<input type='color' value=#" . $conf->global->$key . " style='" . $style . "'></td></tr>";
            //print '</td><td>'.$conf->global->$key.'</td></tr>';
        }

        print '</table>';

		print '<div class="tabsAction">';
		print '<a class="butAction" href="'.$_SERVER["PHP_SELF"].'?action=edit">'.$langs->trans("Modify").'</a>';
		print '</div>';
	}
	else
	{
		print '<br>'.$langs->trans("NothingToSetup");
	}
}


// Page end
dol_fiche_end();

llxFooter();
$db->close();
