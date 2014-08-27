<?php
/**
 * news_admin - hooks
 *
 * @link http://www.egroupware.org
 * @author Ralf Becker <RalfBecker-AT-outdoor-training.de>
 * @package news_admin
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 * @version $Id$
 */

/**
 * Static hooks for news admin
 */
class news_admin_hooks
{
	/**
	 * Settings hook
	 *
	 * @param array|string $hook_data
	 */
	static public function settings($hook_data)
	{
		$show_entries = array(
			0 => lang('No'),
			1 => lang('Yes'),
			2 => lang('Yes').' - '.lang('small view'),
		);
		$_show_entries = array(
			0 => lang('No'),
			1 => lang('Yes'),
		);

		$prefs = array(
			/* disabled until we have a home app again
			'homeShowLatest' => array(
				'type'   => 'select',
				'label'  => 'Show news articles on main page?',
				'name'   => 'homeShowLatest',
				'values' => $show_entries,
				'help'   => 'Should News_Admin display the latest article headlines on the main screen.',
				'xmlrpc' => True,
				'admin'  => False,
				'default'=> '2',
			),
			'homeShowLatestCount' => array(
				'type'    => 'input',
				'label'   => 'Number of articles to display on the main screen',
				'name'    => 'homeShowLatestCount',
				'size'    => 3,
				'maxsize' => 10,
				'help'    => 'Number of articles to display on the main screen',
				'xmlrpc'  => True,
				'admin'   => False,
				'default' => 5,
			),
			'homeShowCats' => array(
				'type'   => 'multiselect',
				'label'  => 'Categories to displayed on main page?',
				'name'   => 'homeShowCats',
				'values' => ExecMethod('news_admin.bonews.rights2cats',EGW_ACL_READ),
				'help'   => 'Which news categories should be displayed on the main screen.',
				'xmlrpc' => True,
				'admin'  => False,
			),*/
			'limit_des_lines' => array(
				'type'   => 'input',
				'size'   => 5,
				'label'  => 'Limit number of description lines (default 5, 0 for no limit)',
				'name'   => 'limit_des_lines',
				'help'   => 'How many describtion lines should be directly visible. Further lines are available via a scrollbar.',
				'xmlrpc' => True,
				'admin'  => False,
				'default'=> 5,
			),
		);
		if ($GLOBALS['egw_info']['user']['apps']['filemanager'])
		{
			$prefs['upload_dir'] = array(
				'type'  => 'vfs_dir',
				'label' => 'VFS upload directory',
				'name'  => 'upload_dir',
				'size'  => 50,
				'help'  => 'Start directory for image browser of rich text editor in EGroupware VFS (filemanager).',
				'xmlrpc' => True,
				'admin'  => False,
			);
		}
		// Import / Export for nextmatch
		if ($GLOBALS['egw_info']['user']['apps']['importexport'])
		{
			$definitions = new importexport_definitions_bo(array(
				'type' => 'export',
				'application' => 'news_admin'
			));
			$options = array(
				'~nextmatch~'	=>	lang('Old fixed definition')
			);
			foreach ((array)$definitions->get_definitions() as $identifier) {
				try {
					$definition = new importexport_definition($identifier);
				} catch (Exception $e) {
					// permission error
					continue;
				}
				if ($title = $definition->get_title()) {
					$options[$title] = $title;
				}
				unset($definition);
			}
			$settings['nextmatch-export-definition'] = array(
				'type'   => 'select',
				'values' => $options,
				'label'  => 'Export definitition to use for nextmatch export',
				'name'   => 'nextmatch-export-definition',
				'help'   => lang('If you specify an export definition, it will be used when you export'),
				'run_lang' => false,
				'xmlrpc' => True,
				'admin'  => False,
			);
		}
		return $prefs;
	}

	/**
	 * Hook for sidebox, admin or preferences menu
	 *
	 * @param array|string $hook_data
	 */
	public static function all_hooks($hook_data)
	{
		$location = is_array($hook_data) ? $hook_data['location'] : $hook_data;
		$appname = 'news_admin';

		if ($location == 'sidebox_menu')
		{
			$categories = new categories('',$appname);
			$enableadd = false;
			foreach((array)$categories->return_sorted_array(0,False,'','','',false) as $cat)
			{
				if ($categories->check_perms(EGW_ACL_EDIT,$cat))
				{
					$enableadd = true;
					break;
				}
			}
			$menu_title = $GLOBALS['egw_info']['apps'][$appname]['title'] . ' '. lang('Menu');
			$file = array();
			if ($enableadd)
			{
				list($w,$h) = explode('x',egw_link::get_registry('news_admin', 'edit_popup'));
				$file['Add'] = "javascript:egw_openWindowCentered2('".egw::link('/index.php',array(
						'menuaction' => 'news_admin.uinews.edit',
					),false)."','_blank',".$w.",".$h.",'yes');";
			}
			$file['Read news'] = egw::link('/index.php',array('menuaction' => 'news_admin.uinews.index'));

			display_sidebox($appname,$menu_title,$file);
		}

		if ($GLOBALS['egw_info']['user']['apps']['admin'])
		{
			$title = lang('Administration');
			$file = Array(
				//'Site Configuration' => egw::link('/index.php','menuaction=admin.uiconfig.index&appname=' . $appname),
				'Configure RSS exports' => egw::link('/index.php','menuaction=news_admin.uiexport.exportlist')
			);

			if ($location == 'sidebox_menu')
			{
				display_sidebox($appname,$title,$file);
			}
			else
			{
				display_section($appname,$title,$file);
			}
		}
	}


	/**
	 * Hook to tell framework we use standard categories method
	 *
	 * @param string|array $data hook-data or location
	 * @return boolean|array
	 */
	public static function categories($data)
	{
		return array('menuaction' => 'news_admin.news_admin_ui.cats');
	}

	/**
	 * Return link registration
	 *
	 * @return array
	 */
	public static function links() {
		return array(
			'query' => 'news_admin.bonews.link_query',
			'title' => 'news_admin.bonews.link_title',
			'view' => array(
				'menuaction' => 'news_admin.uinews.view'
			),
			'view_id' => 'news_id',
			'view_popup'  => '700x390',
			'view_list'	=>	'news_admin.uinews.index',
			'edit' => array(
				'menuaction' => 'news_admin.uinews.edit'
			),
			'edit_id' => 'news_id',
			'edit_popup'  => '700x750',
		);
	}
}
