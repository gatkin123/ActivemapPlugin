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
Text Domain: acmap
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

function enqueue_amapadmin_scripts() {
    wp_register_script('scriptMain', plugin_dir_url(__FILE__) . 'admin/js/main.js', array('jquery'));
	wp_enqueue_script('scriptMain');
	
	wp_register_script('scriptDataManager', plugin_dir_url(__FILE__) . 'admin/js/amap_dmanager.js', array('jquery'));
	wp_enqueue_script('scriptDataManager');
	wp_localize_script( 'scriptDataManager', 'ajax_object', array('ajax_url'=>admin_url('admin-ajax.php')) );
	
	
	wp_enqueue_media();

    // Your custom js file
    wp_register_script( 'media-lib-uploader-js', plugin_dir_url(__FILE__) . 'admin/js/media-lib-uploader.js', array('jquery') );
    wp_enqueue_script( 'media-lib-uploader-js' );

}

function amap_setupajax(){
	
	add_action( 'wp_ajax_amapdata', 'amapdataCallback' );
	add_action( 'wp_ajax_nopriv_amapdata', 'amapdataCallback' );
	
}



function activateAcmap(){
		add_option( 'activemapactivated', 'Y','','yes');
}

function deactivateAcmap(){
		delete_option('activemapactivated');
}



if(!get_option('activemapactivated')){
	add_action('admin_init','acmap_settings_remove');

	//add_action('admin_menu','acmap_remove_options_page',99);
}
else{
	add_action('admin_enqueue_scripts', 'enqueue_amapadmin_scripts');
	add_action('admin_init','acmap_settings_init');

	add_action('admin_menu', 'acmap_options_page');
	amap_setupajax();
	//add_action('admin_menu', 'acmap_options_page');
}

function acmap_settings_remove(){
	unregister_setting('acmap');
	remove_menu_page('acmap_options_page');
}

function acmap_settings_init()
{
    // register a new setting for "acmap" page
    register_setting('acmap', 'acmap_options');
 
    // register a new section in the "acmap" page
    add_settings_section(
        'acmap_section_developers',
        __('The Matrix has you.', 'acmap'),
        'acmap_section_developers_cb',
        'acmap'
    );
 
    // register a new field in the "acmap_section_developers" section, inside the "acmap" page
    add_settings_field(
        'acmap_field_pill', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('Pill', 'acmap'),
        'acmap_field_pill_cb',
        'acmap',
        'acmap_section_developers',
        [
            'label_for'         => 'acmap_field_pill',
            'class'             => 'acmap_row',
            'acmap_custom_data' => 'custom',
        ]
    );

	jal_install();
}
 
/**
 * register our acmap_settings_init to the admin_init action hook
 */
//add_action('admin_init', 'acmap_settings_init');
 
/**
 * custom option and settings:
 * callback functions
 */
 
// developers section cb
 
// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function acmap_section_developers_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Follow the white rabbit.', 'acmap'); ?></p>
    <?php
}
 
// pill field cb
 
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function acmap_field_pill_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('acmap_options');
    // output the field
    ?>
    <select id="<?= esc_attr($args['label_for']); ?>"
            data-custom="<?= esc_attr($args['acmap_custom_data']); ?>"
            name="acmap_options[<?= esc_attr($args['label_for']); ?>]"
    >
        <option value="red" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'red', false)) : (''); ?>>
            <?= esc_html('red pill', 'acmap'); ?>
        </option>
        <option value="blue" <?= isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'blue', false)) : (''); ?>>
            <?= esc_html('blue pill', 'acmap'); ?>
        </option>
    </select>
    <p class="description">
        <?= esc_html('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'acmap'); ?>
    </p>
    <p class="description">
        <?= esc_html('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'acmap'); ?>
    </p>
	<form>
	 <input id="image-url" type="text" name="image" />
	 <input id="upload-button" type="button" class="button" value="Upload Image" />
	 <input type="submit" value="Submit" />
	 </form>
	
	<form>
	 <input id="testdata-button" type="button" class="button" value="Test Data" />
	 <input type="submit" value="Submit" />
	 </form>
	
    <?php
}
 
/**
 * top level menu
 */
function acmap_options_page()
{
    // add top level menu page
    add_menu_page(
        'acmap',
        'acmap Options',
        'manage_options',
        'acmap',
        'acmap_options_page_html'
    );
}
 
/**
 * register our acmap_options_page to the admin_menu action hook
 */
//add_action('admin_menu', 'acmap_options_page');
 
/**
 * top level menu:
 * callback functions
 */
function acmap_options_page_html()
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
        add_settings_error('acmap_messages', 'acmap_message', __('Settings Saved', 'acmap'), 'updated');
    }
 
    // show error/update messages
    settings_errors('acmap_messages');
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "acmap"
            settings_fields('acmap');
            // output setting sections and their fields
            // (sections are registered for "acmap", each field is registered to a specific section)
            do_settings_sections('acmap');
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


/*	SELECT count(*)
	FROM information_schema.TABLES
	WHERE (TABLE_SCHEMA = 'your_db_name') AND (TABLE_NAME = 'name_of_table')
*/

	$dbnam = $wpdb->dbname;
		$table_name = $wpdb->prefix . 'acmap_apps';
	$sql = "SELECT count(*) FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '".$dbnam."') AND (TABLE_NAME = '".$table_name."')";
	
	$cnt = $wpdb->get_var( $sql );
	//console_log("Count".$cnt);
	
	if($cnt < 1){
	
	$table_name = $wpdb->prefix . 'acmap_locations';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		locationid mediumint(9) NOT NULL,
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
		locheight mediumint(6) NOT NULL,
		PRIMARY KEY (locationid)
		
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	

	$table_name = $wpdb->prefix . 'acmap_categories';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		categoryid mediumint(9) NOT NULL,
		parentid mediumint(9) NOT NULL,
		templateid mediumint(9) NOT NULL,
		name varchar(100) NOT NULL,
		PRIMARY KEY (categoryid)
		
	) $charset_collate;";

	dbDelta( $sql );
	
	
	$table_name = $wpdb->prefix . 'acmap_templates';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		templateid mediumint(9) NOT NULL,
		name varchar(100) NOT NULL,
		PRIMARY KEY (templateid)
		
	) $charset_collate;";

	dbDelta( $sql );
	
	
	$table_name = $wpdb->prefix . 'acmap_apps';
		
	$sql = "CREATE TABLE $table_name (
		acmapid mediumint(9) NOT NULL,
		mapurl varchar(100) NOT NULL,
		name varchar(100) NOT NULL,
		PRIMARY KEY (acmapid)
		
	) $charset_collate;";

	dbDelta( $sql );
	
	

	
	add_option( 'jal_db_version', $jal_db_version );
	console_log("init data");
	jal_install_init_data();
	}
		//console_log($sql);
	
	
	
}

function jal_install_init_data() {
	
	require_once('admin/activemap-temploader.php');
	
	initTemplateData();
}



/*Data callbacks*/

function amapdataCallback(){
	global $wpdb;
	$dataop = $_POST['operation'];
		//echo("amap callback");
	if($dataop=="getcurrentmaps"){
		//amap_getcurrentmaps();
	}
	else if($dataop == "gettemplates"){
		echo amap_getcurrenttemplates();
	}
	
	
	//echo($dataop);
	die();
	
	
}

function amap_getcurrenttemplates(){
	global $wpdb;
	
		$table_name = $wpdb->prefix . 'acmap_categories';
	$rows = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_N);
	
	return getXMLFromWDB($rows);
	
		
}
function getXMLFromWDB($datarows){
	$xmlstr = "<?xml version='1.0' encoding='UTF-8'?><data>";
	$xmlstrtest = "<?xml version='1.0' encoding='UTF-8'?><row><col>1</col></row>";
	$cnt = 0;
	foreach($datarows as $datarow){
		$xmlstr = $xmlstr."<row>";
		$cnt++;
		foreach($datarow as $datacol){
			$xmlstr = $xmlstr. "<col>".$datacol."</col>";
		}
		$xmlstr = $xmlstr."</row>";
		if($cnt==5){
			//break;
		}
	}
	$xmlstr = $xmlstr."</data>";
	//console_log($xmlstr);
	return 	$xmlstr;
}

//generic object generator
function object(){

       return new stdClass();

}






