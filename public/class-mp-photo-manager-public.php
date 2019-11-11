<?php


/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://someone
 * @since      1.0.0
 *
 * @package    Mp_Photo_Manager
 * @subpackage Mp_Photo_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mp_Photo_Manager
 * @subpackage Mp_Photo_Manager/public
 * @author     mp <mp@null.com>
 */
class Mp_Photo_Manager_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mp_Photo_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mp_Photo_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/mp-photo-manager-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mp_Photo_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mp_Photo_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/mp-photo-manager-public.js', array('jquery'), $this->version, false);
	}

	/**
	 * Ajax callback for the Create Album action.
	 *
	 * @since    1.0.0
	 */
	public function mp_create_album()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_create_album_nonce")) {
			exit("Couldn't verify nonce.");
		}
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
		$desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);

		Mp_Photo_Manager_Db::create_album($name, $desc);
	}

	/**
	 * Ajax callback for the Delete Album action.
	 *
	 * @since    1.0.0
	 */
	public function mp_delete_album()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_delete_album_nonce")) {
			exit("Couldn't verify nonce.");
		}
		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

		Mp_Photo_Manager_Db::delete_album($id);
	}
}
