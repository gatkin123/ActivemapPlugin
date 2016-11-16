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
	//this is the deactivater
	delete_option('activemapactivated');
	
}
function acmap_remove_options_page(){
	
	remove_menu_page('activemap');
}
function acmap_options_page()
{
    add_menu_page(
        'Activemap',
        'Activemap Options',
        'manage_options',
        'activemap',
        'acmap_options_page_html'
    );
}

function wporg_settings_init()
{
    // register a new setting for "reading" page
    register_setting('reading', 'wporg_setting_name');
 
    // register a new section in the "reading" page
    add_settings_section(
        'wporg_settings_section',
        'WPOrg Settings Section',
        'wporg_settings_section_cb',
        'reading'
    );
 
    // register a new field in the "wporg_settings_section" section, inside the "reading" page
    add_settings_field(
        'wporg_settings_field',
        'WPOrg Setting',
        'wporg_settings_field_cb',
        'reading',
        'wporg_settings_section'
    );
}

if(!get_option('activemapactivated')){
	add_action('admin_menu','acmap_remove_options_page',99);
}
else{
	add_action('admin_init','wport_settings_init');
	add_action('admin_menu', 'acmap_options_page');
}
function acmap_options_page_html()
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