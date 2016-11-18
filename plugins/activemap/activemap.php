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
/**
 * @internal    never define functions inside callbacks.
 *              these functions could be run multiple times; this would result in a fatal error.
 */
 
/**
 * custom option and settings
 */

register_activation_hook(__FILE__, 'activateAcmap');
register_deactivation_hook(__FILE__, 'deactivateAcmap');

function activateAcmap(){
		add_option( 'activemapactivated', 'Y','','yes');
}

function deactivateAcmap(){
		delete_option('activemapactivated');
}

if(!get_option('activemapactivated')){
	add_action('admin_init','wporg_settings_remove');

	//add_action('admin_menu','acmap_remove_options_page',99);
}
else{
	add_action('admin_init','wporg_settings_init');
	add_action('admin_menu', 'wporg_options_page');
	//add_action('admin_menu', 'acmap_options_page');
}

function wporg_settings_remove(){
	unregister_setting('wporg');
	remove_menu_page('wporg_options_page');
}

function wporg_settings_init()
{
    // register a new setting for "wporg" page
    register_setting('wporg', 'wporg_options');
 
    // register a new section in the "wporg" page
    add_settings_section(
        'wporg_section_developers',
        __('The Matrix has you.', 'wporg'),
        'wporg_section_developers_cb',
        'wporg'
    );
 
    // register a new field in the "wporg_section_developers" section, inside the "wporg" page
    add_settings_field(
        'wporg_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('Pill', 'wporg'),
        'wporg_field_pill_cb',
        'wporg',
        'wporg_section_developers',
        [
            'label_for'         => 'wporg_field_pill',
            'class'             => 'wporg_row',
            'wporg_custom_data' => 'custom',
        ]
    );
}
 
/**
 * register our wporg_settings_init to the admin_init action hook
 */
//add_action('admin_init', 'wporg_settings_init');
 
/**
 * custom option and settings:
 * callback functions
 */
 
// developers section cb
 
// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function wporg_section_developers_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Follow the white rabbit.', 'wporg'); ?></p>
    <?php
}
 
// pill field cb
 
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function wporg_field_pill_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('wporg_options');
    // output the field
    ?>
    <select id="<?= esc_attr($args['label_for']); ?>"
            data-custom="<?= esc_attr($args['wporg_custom_data']); ?>"
            name="wporg_options[<?= esc_attr($args['label_for']); ?>]"
    >
        <option value="red" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'red', false)) : (''); ?>>
            <?= esc_html('red pill', 'wporg'); ?>
        </option>
        <option value="blue" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'blue', false)) : (''); ?>>
            <?= esc_html('blue pill', 'wporg'); ?>
        </option>
    </select>
    <p class="description">
        <?= esc_html('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg'); ?>
    </p>
    <p class="description">
        <?= esc_html('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg'); ?>
    </p>
    <?php
}
 
/**
 * top level menu
 */
function wporg_options_page()
{
    // add top level menu page
    add_menu_page(
        'WPOrg',
        'WPOrg Options',
        'manage_options',
        'wporg',
        'wporg_options_page_html'
    );
}
 
/**
 * register our wporg_options_page to the admin_menu action hook
 */
//add_action('admin_menu', 'wporg_options_page');
 
/**
 * top level menu:
 * callback functions
 */
function wporg_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', 'wporg'), 'updated');
    }
 
    // show error/update messages
    settings_errors('wporg_messages');
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields('wporg');
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


global $jal_db_version;
$jal_db_version = '1.0';

/* Table creation routines*/
function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'acmap_locations';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		locationid mediumint(9) NOT NULL AUTO_INCREMENT,
		acmapid mediumint(9) NOT NULL,
		cateogryid mediumint(9) NOT NULL,
		name varchar(100) NOT NULL,
		address varchar(200),
		address2 varchar(200),
		phone varchar(10),
		email varchar(100),
		url varchar(200),
		callout varchar(400),
		offsetx mediumint(6) NOT NULL,
		offsety mediumint(6) NOT NULL,
		locwidth mediumint(6) NOT NULL,
		locheight mediumint(6) NOT NULL
		
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );


	$table_name = $wpdb->prefix . 'acmap_categories';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		categoryid mediumint(9) NOT NULL AUTO_INCREMENT,
		parentid mediumint(9) NOT NULL,
		name varchar(100) NOT NULL
		
	) $charset_collate;";

	dbDelta( $sql );
	
	$table_name = $wpdb->prefix . 'acmap_apps';
		
	$sql = "CREATE TABLE $table_name (
		acmapid mediumint(9) NOT NULL AUTO_INCREMENT,
		parentid mediumint(9) NOT NULL,
		name varchar(100) NOT NULL
		
	) $charset_collate;";

	dbDelta( $sql );
	
	add_option( 'jal_db_version', $jal_db_version );
}
/*
function jal_install_data() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'liveshoutbox';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $welcome_name, 
			'text' => $welcome_text, 
		) 
	);
}
*/
