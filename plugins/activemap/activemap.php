<?php
/*
Plugin Name: Activemap
Plugin URI:  http://www.artsciencecode.com/activemapplugin/
Description: Plugin for adding Activemap functionality to a website
Version:     1.0
Author:      Geoffrey Atkin, Julian Atkin, Gabriel Atkin
Author URI:  http://www.artsciencecode.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/
function activateAcmap(){
	$test = 0;
	continueAc();
}

function continueAc(){
	$test2 = 0;
	//add_action('admin_menu', 'wporg_options_page');
	add_option( 'activemapactivated', 'Y','','yes');
}

function deactivateAcmap(){

	delete_option('activemapactivated');
	
}
function wporg_remove_options_page(){
	
	remove_menu_page('wporg');
}
function wporg_options_page()
{
    add_submenu_page(
        'tools.php',
        'WPOrg Options',
        'WPOrg Options',
        'manage_options',
        'wporg',
        'wporg_options_page_html'
    );
}
if(!get_option('activemapactivated')){
	add_action('admin_menu','wporg_remove_options_page',99);
}
else{
	add_action('admin_menu', 'wporg_options_page');
}
function wporg_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg_options"
            settings_fields('wporg_options');
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections('wporg');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}


register_activation_hook(__FILE__, 'activateAcmap');
register_deactivation_hook(__FILE__, 'deactivateAcmap');
?>