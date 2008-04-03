<?php
/**
 * eGroupWare - News admin
 * 
 * @link http://www.egroupware.org 
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 * @package news_admin
 * @subpackage setup
 * @version $Id$
 */

/* Basic information about this app */
$setup_info['news_admin']['name']      = 'news_admin';
$setup_info['news_admin']['title']     = 'News Admin';
$setup_info['news_admin']['version']   = '1.5.001';
$setup_info['news_admin']['app_order'] = 16;
$setup_info['news_admin']['enable']    = 1;

$setup_info['news_admin']['author']    =
$setup_info['news_admin']['maintainer'] = 'Ralf Becker';
$setup_info['news_admin']['maintainer_email'] = 'RalfBecker@outdoor-training.de';

/* The tables this app creates */
$setup_info['news_admin']['tables']    = array('egw_news','egw_news_export');

/* The hooks this app includes, needed for hooks registration */
$setup_info['news_admin']['hooks'][] = 'admin';
$setup_info['news_admin']['hooks'][] = 'home';
$setup_info['news_admin']['hooks'][] = 'sidebox_menu';
$setup_info['news_admin']['hooks'][] = 'settings';
$setup_info['news_admin']['hooks'][] = 'preferences';
$setup_info['news_admin']['hooks'][] = 'config_validate';

/* Dependencies for this app to work */
$setup_info['news_admin']['depends'][] = array(
	 'appname' => 'phpgwapi',
	 'versions' => Array('1.3','1.4','1.5')
);

// installation checks for news_admin (PEAR)
$setup_info['news_admin']['check_install'] = array(
	'' => array(
		'func' => 'pear_check',
	),
	'XML_Feed_Parser' => array(
		'func' => 'pear_check',
	),
);

