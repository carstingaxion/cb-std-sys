<?php
/*
Plugin Name: WP Changes Tracker
Plugin URI: http://pixeline.be
Description: Maintain a log of all themes, plugins and wordpress changes.

Version: 1.1.1
Author: pixeline
Author URI: http://pixeline.be
*/

if (!class_exists('wp_changes_tracker')) {
	class wp_changes_tracker {

		private $pluginName = 'WP Changes Tracker';
		private $pluginVersion = '1.0';
		private $dbVersion= '1.0';
		private $dbNameRoot=  'changes_tracker';
		const pluginId = 'wp_changes_tracker';


		public $optionsName = 'wp_changes_tracker_options';
		public $options = array();
		public $localizationDomain = "wp_changes_tracker";
		public $multisite = false;
		private $url = '';
		private $urlpath = '';
		private $settings_link='';
		private $default_type = 'undefined';
		private $types = array();


		function wp_changes_tracker(){$this->__construct();}

		function __construct(){
			//Language Setup
			$locale = get_locale();
			$mo = plugin_dir_path(__FILE__) . 'languages/' . $this->localizationDomain . '-' . $locale . '.mo';
			load_textdomain($this->localizationDomain, $mo);

			//"Constants" setup
			$this->multisite= (function_exists('is_multisite') && is_multisite()) ;
			$this->url = plugins_url(basename(__FILE__), __FILE__);
			$this->urlpath = plugins_url('', __FILE__);
			$this->settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';


			global $wpdb;
			$this->dbName = $wpdb->prefix . $this->dbNameRoot;

			$this->types = array('plugin','theme','option','setting','core','multisite','manual', $this->default_type );
			//Initialize the options
			$this->getOptions();

			//register an activation hook for the plugin
			register_activation_hook( __FILE__, array( &$this, 'install' ) );
			//register_uninstall_hook(__FILE__, array( &$this,'uninstall'));

			//Admin
			if($this->multisite){
				add_action("network_admin_menu", array(&$this,"admin_menu_link"));			
			}else{
				add_action("admin_menu", array(&$this,"admin_menu_link"));

			}
			add_action('admin_enqueue_scripts', array(&$this,'wp_changes_tracker_script'));
			add_action('admin_print_styles', array(&$this,'wp_changes_tracker_styles'));
			if($this->multisite){
			add_action( 'wp_network_dashboard_setup', array(&$this,'dashboard_widget') );
			}else{
			add_action( 'wp_dashboard_setup', array(&$this,'dashboard_widget') );

			}
			
			
			add_action("init", array(&$this,"wp_changes_tracker_init"));


			/* LOGGING ACTIONS - ADD AT WILL */
			// plugin activation changes
			add_action( 'activated_plugin',  array(&$this,'activated_plugin'),10,2 );
			add_action( 'deactivated_plugin',  array(&$this,'activated_plugin'),10,2 );

			// option changed
			add_action( 'updated_option',  array(&$this,'option_changed'),10,3 );
			add_action( 'deleted_option',  array(&$this,'option_deleted'),10,1 );
			add_action( 'added_option',  array(&$this,'option_added'),10,2 );

			// core changes
			add_action( '_core_updated_successfully',  array(&$this,'core_updated'),10,1 );
			add_action( 'after_db_upgrade',  array(&$this,'db_upgraded'),10,0 );
			add_action('after_mu_upgrade', array(&$this,'after_mu_upgrade'),10,1);

			// network changes
			add_action('wpmu_blog_updated',array(&$this,'wpmu_blog_updated'),10,1);
			add_action('wpmu_activate_blog',array(&$this,'wpmu_activate_blog'),10,5);
			add_action('wpmu_new_blog',array(&$this,'wpmu_new_blog'),10,6);
			add_action('deactivate_blog',array(&$this,'deactivate_blog'),10,1);
			add_action('activate_blog',array(&$this,'activate_blog'),10,1);
			add_action('delete_blog',array(&$this,'delete_blog'),10,2);

		}


		/*
		* Log any option change
		* hooks: added_option, deleted_option, updated_option
		*/
		function showDiff($a1,$a2){
			if(is_array($a1) && is_array($a2)){
				return '<br><strong>Changed</strong> <pre>'.print_r(array_diff($a1,$a2),true).'</pre>';
			}
			return;
			
		}

		function option_changed( $option, $oldvalue, $newvalue){
			$mess = 'option "'. $option . '" updated';
			$mess = $mess .' '.$this->showDiff($oldvalue, $newvalue).' old value: <pre>'.print_r($oldvalue,true) . '</pre> new value: <pre>'.print_r($newvalue,true).'</pre>';
			$this->log($mess,'option');
		}

		function option_deleted($option){
			$this->log($option . ' deleted.','option');
		}


		function core_updated($version){
			$this->log('Wordpress updated to version: '.$version,'core');
		}

		function db_upgraded(){
			$this->log('Wordpress database upgraded','core');
		}

		function after_mu_upgrade($response){
			$this->log('Wordpress Network upgraded, result: <pre>'. print_r($response,true).'</pre>','core');
		}
		function wpmu_activate_blog( $blog_id, $user_id, $password, $title, $meta){
			$this->log('Blog (id:'.$blog_id.', title:'.$title.') activated for user: '.$user_id.'. <pre>'.print_r($meta,true).'</pre>','multisite');
		}

		function wpmu_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta){
			$this->log('Blog (domain: '.$domain.', site: '.$site_id.' id:'.$blog_id.', title:'.$title.') created by/for user: '.$user_id.'. <pre>'.print_r($meta,true).'</pre>','multisite');

		}

		function wpmu_blog_updated($blogid){
			$this->log('Wordpress Network Blog (id:'.$blogid.' updated.','multisite');
		}

		function delete_blog($blogid,$drop){
			$drop= ($drop) ? ' deleted too': ' kept';
			$this->log('Wordpress Blog (id:'.$blogid.' deleted. The table was '.$drop.'.','multisite');

		}

		function deactivate_blog($blogid){
			$this->log('Wordpress Network Blog (id:'.$blogid.' deactivated.','multisite');
		}
		function activate_blog($blogid){
			$this->log('Wordpress Network Blog (id:'.$blogid.' activated.','multisite');
		}


		function option_added($option,$value){
			$this->log('option: "'. $option . '" added, value: <pre>'. print_r($value,true).'</pre>','option');
		}

		/**
		 * Log plugin activations and deactivations.
		 *
		 * @param  string $plugin
		 * @param  bool   $network_wide
		 * @author : toscho http://wordpress.stackexchange.com/questions/53413/deactivated-plugin-hook-get-the-name-of-the-plugin
		 * @return void
		 */
		function activated_plugin( $plugin,$network_wide )
		{
			$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );

			$log = array (
				'plugin'  => $plugin_data['Name'],
				'network' => $network_wide ? ' network wide' : '',
				'action'  => 'deactivated_plugin' === current_filter() ? 'deactivated' : 'activated'
			);

			//update_option( 't5_plugin_log', $log );
			$mess = $log['plugin']. ' : '.$log['action'] .' '.$log['network'];
			$this->log($mess,'plugin');
		}

		/**
		 * Sends the log update to the database.
		 *
		 * @param  string $mess
		 * @param  string $type
		 * @return void
		 */
		function log($mess,$type){

			global $wpdb;

			$type = (in_array($type,$this->types)) ? $type: $this->default_type;

			$user = esc_html( wp_get_current_user()->display_name );

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$rows_affected = $wpdb->insert( $this->dbName, array( 'when' => current_time('mysql'),'who'=>$user, 'description' => $mess, 'type' => $type ) );

		}



		function install(){
			$sql_install_query = "CREATE TABLE IF NOT EXISTS `".$this->dbName."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`when` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`who` varchar(255) NOT NULL,
			`description` text NOT NULL,
			`type` varchar(255) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='log of all themes, plugins or wordpress changes.' AUTO_INCREMENT=1 ;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql_install_query);


			$this->log($this->pluginName . ': log database table successfully installed!', 'plugin');

			$theOptions = array('db_version'=> $this->dbVersion );
			update_option($this->optionsName, $theOptions);

		}

		function uninstall(){
			global $wpdb;
			delete_option($this->optionsName);
			$e = $wpdb->query("DROP TABLE IF EXISTS `".$this->dbName."`;");
			die(var_dump($e));
		}


		function wp_changes_tracker_init() {

		}


		function wp_changes_tracker_styles(){

			wp_register_style( $this->pluginName, plugins_url('css/jquery.dataTables.css', __FILE__) );
			wp_enqueue_style( $this->pluginName );

			wp_register_style( $this->pluginName.'-final', plugins_url('css/wp-changes-tracker.css', __FILE__) );
			wp_enqueue_style( $this->pluginName.'-final' );

		}

		function wp_changes_tracker_script() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-validate', 'http://ajax.microsoft.com/ajax/jquery.validate/1.6/jquery.validate.min.js', array('jquery'));
			wp_enqueue_script('datatables', 'http://datatables.net/download/build/jquery.dataTables.min.js', array('jquery'));
			wp_enqueue_script('wp_changes_tracker_script', $this->url.'?wp_changes_tracker_javascript_datatables', array('jquery','datatables'));
			wp_localize_script( 'wp_changes_tracker_script', 'wp_changes_tracker_lang', array(
						'required' => __('Please enter a number.', $this->localizationDomain),
						'number'   => __('Please enter a number.', $this->localizationDomain),
						'min'   => __('Please enter a value greater than or equal to 1.', $this->localizationDomain),
					));
			
			wp_enqueue_script('wp_changes_tracker_script', $this->url.'?wp_changes_tracker_javascript', array('jquery','jquery-validate'));

		}
		/**
		 * @desc Retrieves the plugin options from the database.
		 * @return array
		 */
		function getOptions() {
			if (!$theOptions = get_option($this->optionsName)) {
				$theOptions = array('db_version'=> $this->dbVersion, 'use_datatables_script'=>1, 'max_log_entries_to_display'=>300);
				update_option($this->optionsName, $theOptions);
			}
			$this->options = $theOptions;
		}
		/**
		 * Saves the admin options to the database.
		 */
		function saveAdminOptions(){
			return update_option($this->optionsName, $this->options);
		}

		/**
		 * @desc Adds the options subpanel
		 */
		function admin_menu_link() {
		
		if($this->multisite){
			
					add_submenu_page('settings.php', 'WP Changes Tracker', 'WP Changes Tracker', 'manage_network',  basename(__FILE__),array(&$this,'admin_options_page'));

		}else{
				add_options_page('WP Changes Tracker', 'WP Changes Tracker', 10, basename(__FILE__), array(&$this,'admin_options_page'));		
			
		}
		

			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2 );
		}

		/**
		 * @desc Adds the Settings link to the plugin activate/deactivate page
		 */
		function filter_plugin_actions($links, $file) {
			array_unshift( $links, $this->settings_link );

			return $links;
		}

		function dashboard_widget()
		{
			if ( current_user_can( 'activate_plugins' ) )
			{
				wp_add_dashboard_widget(
					$this->pluginId.'_widget',
					$this->pluginName,
					array(&$this,'widget')
				);
			}
		}

		function widget(){

			$this->display_log();

			print '<p>Configure this table in the '. $this->settings_link.'<p>';

		}


		function display_log()
		{
			global $wpdb;

			$sql = "SELECT * FROM `".$this->dbName. "` ORDER BY `".$this->dbName. "`.`when` DESC";
			if($this->options['max_log_entries_to_display']>0){
				$sql .= ' LIMIT '.$this->options['max_log_entries_to_display'];

			}
			$log = $wpdb->get_results($sql);
			if ( 1 > $wpdb->num_rows)
			{
				print ' The Change log is empty';
				return;
			}
			$interactive = ($this->options['use_datatables_script']) ? 'interactive' : '';
			print '<table id="'.self::pluginId.'_table" class="widefat '.$interactive.'">
	<thead>
		<tr>
			<th style="min-width:8em">Time</th>
			<th >User</th>
			<th>Type</th>
			<th>Message</th>
		</tr>
	</thead>
	<tbody>';

			foreach ( $log as $entry )
			{
				printf(
					'<tr class="%3$s"><td>%1$s</td><td>%2$s</td><td>%3$s</td><td>%4$s</td></tr>',
					$entry->when,
					$entry->who,
					$entry->type,
					$entry->description
				);
			}
			print '</tbody></table>';
		}



		/**
		 * Adds settings/options page
		 */
		function admin_options_page() {
			if($_POST['wp_changes_tracker_save']){
				if (! wp_verify_nonce($_POST['_wpnonce'], 'wp_changes_tracker-update-options') ) die('Whoops! There was a problem with the data you posted. Please go back and try again.');
				$this->options['use_datatables_script'] = $_POST['use_datatables_script'];
				$this->options['max_log_entries_to_display'] = $_POST['max_log_entries_to_display'];

				$this->saveAdminOptions();
				echo '<div class="updated"><p>Success! Your changes were sucessfully saved!</p></div>';
			}
?>
			<div class="wrap">
			<h1>WP Changes Tracker</h1>
			<p><?php _e('by <a href="http://www.pixeline.be" target="_blank" class="external">pixeline</a>', $this->localizationDomain); ?></p>
			<p style="font-weight:bold;"><?php _e('If you like this plugin, please <a href="http://wordpress.org/extend/plugins/wp-changes-tracker/" target="_blank">give it a good rating</a> on the Wordpress Plugins repository, and if you make any money out of it, <a title="Paypal donation page" target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=J9X5B6JUVPBHN&lc=US&item_name=pixeline%20%2d%20Wordpress%20plugin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest">send a few coins over to me</a>!', $this->localizationDomain); ?></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_donations" />
<input type="hidden" name="business" value="J9X5B6JUVPBHN" />
<input type="hidden" name="lc" value="US" />
<input type="hidden" name="item_name" value="pixeline - Wordpress plugin: WP Changes Tracker" />
<input type="hidden" name="currency_code" value="EUR" />
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest" />
<input type="image" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" alt="PayPal - The safer, easier way to pay online!" />
<img src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" alt="" width="1" height="1" border="0" /></form>

			<h2 style="border-top:1px solid #999;padding-top:1em;"><?php _e('Settings',$this->localizationDomain);?></h2>
			<p>
			<?php _e('This plugin maintains a changelog of all themes, plugins and wordpress-core changes, updates, (de)activation, upgrades.', $this->localizationDomain); ?>
			</p>
			<form method="post" id="wp_changes_tracker_options">
			<?php wp_nonce_field('wp_changes_tracker-update-options'); ?>
				<table width="100%" cellspacing="2" cellpadding="5" class="form-table">
					<tr valign="top">
						<th width="50%" scope="row"><?php _e('Add Sorting and styling to the changelog table below (via the <a href="http://datatables.net/" target="_blank">DataTables</a> javascript - could slow down the log if it is heavy) ?', $this->localizationDomain); ?></th>
						<td>
							<label><input name="use_datatables_script" type="radio" id="use_datatables_script_yes" <?php echo ('1'===$this->options['use_datatables_script']) ? 'checked': '' ;?> value="1"/> <?php _e('Yes', $this->localizationDomain); ?></label>

							<label><input name="use_datatables_script" type="radio" id="use_datatables_script_no" <?php echo ('0'===$this->options['use_datatables_script']) ? 'checked': '' ;?> value="0"/> <?php _e('No', $this->localizationDomain); ?></label>

						</td>
					</tr>

					<tr valign="top">
						<th width="50%" scope="row">
						<label for="max_log_entries_to_display"><?php _e('Maximum amount of rows to display?', $this->localizationDomain); ?></label>
						</th>
						<td>
						<input type="text" size="4" name="max_log_entries_to_display" id="max_log_entries_to_display" value="<?php echo $this->options['max_log_entries_to_display']; ?>"/>
						<small>This option controls the number of rows to display, no data will be deleted. "0" will display all values.</small>
						</td>


				</table>
				<p class="submit">
					<input type="submit" name="wp_changes_tracker_save" class="button-primary" value="<?php _e('Save Changes', $this->localizationDomain); ?>" />
				</p>
			</form>

			<h2><?php _e('This Wordpress System Change Log');?></h2>
			<?php
			$this->display_log();
			print '<img src="http://pixeline.be/pixeline-downloads-tracker.php?fn='.self::pluginId.'&v='.$this->pluginVersion.'&uu='.$_SERVER['HTTP_HOST'].'" width="1" height="1"/>';
		}





	} //End Class
} //End if class exists statement

if (isset($_GET['wp_changes_tracker_javascript_datatables'])){

	//embed javascript
	header("content-type: application/x-javascript");
	echo "
/**
* @desc WP Changes Tracker dataTables config
* @author pixeline - http://pixeline.be
*/

jQuery(document).ready(function($){
	var table = jQuery('#".wp_changes_tracker::pluginId."_table.interactive');

	jQuery('pre',table).before('<span class=\'show-details\'>show details</span>').hide();
	jQuery('span.show-details',table).live('click',function(){
        		var target = $(this).next('pre').toggle();
        		if(jQuery(target).is(':visible')){
    				$(this).text('hide details');
    				}else{
    				$(this).text('show details');
    			}
    });
	table.dataTable({
        'sPaginationType': 'full_numbers',
        'bJQueryUI': true,
        'bProcessing': true,
        'bScrollInfinite': true,
        'bScrollCollapse': true,
        'sScrollY': '600px',
        'aaSorting': [[ 1, 'desc' ]],
        'fnDrawCallback': function(){
        	jQuery('pre',table).hide();

        }
    });
});";

} else if (isset($_GET['wp_changes_tracker_javascript'])) {
	//embed javascript
	header("content-type: application/x-javascript");
	echo "
/**
* @desc WP Changes Tracker
* @author pixeline - http://pixeline.be
*/

jQuery(document).ready(function($){

	//validate plugin option form
  	jQuery('#wp_changes_tracker_options').validate({
		rules: {
			use_datatables_script: {
				required: false,
				number: false
			}
		},
		messages: {
			use_datatables_script: {
				// the wp_changes_tracker_lang object is define using wp_localize_script() in function wp_changes_tracker_script()
				required: wp_changes_tracker_lang.required,
				number: wp_changes_tracker_lang.number,
				min: wp_changes_tracker_lang.min
			}
		}
	});
});

";

} else {
	if (class_exists('wp_changes_tracker')) {
		$wp_changes_tracker_var = new wp_changes_tracker();
	}
}
?>