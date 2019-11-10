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
		ob_start();
		MP_Photo_Manager_Activator::create_db();
		//var_dump(ob_get_contents());

	}

	public static function create_db()
	{
		global $wpdb;

		$dbName = "mp_photo_manager";

		// Create database.
		$qry = "CREATE DATABASE IF NOT EXISTS {$dbName}";
		$wpdb->get_results($qry);

		// $qry = "USE mp_photo_manager";
		// $wpdb->get_results($qry);
		// var_dump($wpdb->print_error());
		// exit;

		// Create Albums table.
		$qry = "CREATE TABLE IF NOT EXISTS {$dbName}.albums (
					id INT AUTO_INCREMENT,
					user_id INT,
					title VARCHAR(255) NOT NULL,
					desciption VARCHAR(1023),
					created DATE NOT NULL,
					updated DATE,
					PRIMARY KEY (id))";
		$wpdb->get_results($qry);

		// Create Photos table.
		$qry = "CREATE TABLE IF NOT EXISTS {$dbName}.photos (
					id INT AUTO_INCREMENT,
					user_id INT,
					title VARCHAR(255) NOT NULL,
					desciption VARCHAR(1023),
					created DATE NOT NULL,
					updated DATE,
					PRIMARY KEY (id))";
		$wpdb->get_results($qry);

		// Create Photos/Albums intersection table.
		$qry = "CREATE TABLE IF NOT EXISTS $dbName.photos_albums (
					id INT NOT NULL AUTO_INCREMENT,
					photo_id INT NOT NULL,
					album_id INT NOT NULL,
					added DATE NOT NULL,
					FOREIGN KEY (photo_id) REFERENCES photos(id),
					FOREIGN KEY (album_id) REFERENCES albums(id),
					PRIMARY KEY (id),
					UNIQUE KEY pkey (photo_id, album_id))";
		$wpdb->get_results($qry);
		// var_dump($wpdb->print_error());
		//exit;
	}
}
