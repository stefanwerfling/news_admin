<?php
/**
 * EGroupware - News admin
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
$setup_info['news_admin']['version']   = '1.8';
$setup_info['news_admin']['app_order'] = 16;
$setup_info['news_admin']['enable']    = 1;
$setup_info['news_admin']['index']     = 'news_admin.uinews.index';

$setup_info['news_admin']['license']   = 'GPL';
$setup_info['news_admin']['author']    =
$setup_info['news_admin']['maintainer'] = 'Ralf Becker';
$setup_info['news_admin']['maintainer_email'] = 'RalfBecker@outdoor-training.de';

/* The tables this app creates */
$setup_info['news_admin']['tables']    = array('egw_news','egw_news_export');

/* The hooks this app includes, needed for hooks registration */
$setup_info['news_admin']['hooks']['admin'] = 'news_admin.news_admin_hooks.all_hooks';
$setup_info['news_admin']['hooks']['sidebox_menu'] = 'news_admin.news_admin_hooks.all_hooks';
$setup_info['news_admin']['hooks']['settings'] = 'news_admin.news_admin_hooks.settings';
$setup_info['news_admin']['hooks']['preferences'] = 'news_admin.news_admin_hooks.all_hooks';
$setup_info['news_admin']['hooks']['search_link'] = 'news_admin.news_admin_hooks.links';
$setup_info['news_admin']['hooks'][] = 'home';

/* Dependencies for this app to work */
$setup_info['news_admin']['depends'][] = array(
	 'appname' => 'phpgwapi',
	 'versions' => Array('1.7','1.8','1.9')
);

// installation checks for news_admin (PEAR)
$setup_info['news_admin']['check_install'] = array(
	'' => array(
		'func' => 'pear_check',
	),
	'XML_Feed_Parser' => array(
		'func' => 'pear_check',
		'from' => 'NewsAdmin',
	),
);

