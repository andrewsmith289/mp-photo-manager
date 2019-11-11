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
		Mp_Photo_Manager_Db::create_db();
	}
}
