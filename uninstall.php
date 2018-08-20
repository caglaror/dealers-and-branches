<?php
/**
 * uninstall.php
 *
 */


// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}


// drop a custom database table
global $wpdb;
$table = $wpdb->prefix . Dealers_And_Branches::TABLE_NAME;
$table2 = $wpdb->prefix . Dealers_And_Branches::TABLE_NAME . '_vars';

$wpdb->query("DROP IF EXISTS $table");
$wpdb->query("DROP IF EXISTS $table2");

