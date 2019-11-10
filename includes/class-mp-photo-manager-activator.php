<?php

/**
 * Fired during plugin activation
 *
 * @link       https://someone
 * @since      1.0.0
 *
 * @package    Mp_Photo_Manager
 * @subpackage Mp_Photo_Manager/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mp_Photo_Manager
 * @subpackage Mp_Photo_Manager/includes
 * @author     mp <mp@null.com>
 */
class Mp_Photo_Manager_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		MP_Photo_Manager_Activator::create_db();
	}

	public static function create_db()
	{
		global $wpdb;

		// Create database.
		$qry = "CREATE DATABASE IF NOT EXISTS mp_photo_manager";

		$wpdb->get_results($qry);
		// var_dump();
		// exit;

		$qry = "USE mp_photo_manager";
		$wpdb->get_results($qry);

		// Create Albums table.
		$qry = "CREATE TABLE IF NOT EXISTS albums";
		var_dump($wpdb->get_results($qry));
		exit;

		// // Create Photos table.
		$qry = "CREATE TABLE IF NOT EXISTS photos";
		$wpdb->get_results($qry);
	}
}
