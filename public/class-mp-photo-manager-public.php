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
		wp_enqueue_style('jquery-modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css', array(), $this->version, 'all');
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
		// wp_enqueue_script('jquery-modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', array('jquery'), $this->version, false);

		// wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/mp-photo-manager-public.js', array('jquery'), $this->version, false);

		// Localize script with nonces
		// wp_localize_script(
		// 	$this->plugin_name,
		// 	'mp_ajax_object',
		// 	[
		// 		'ajax_url' => admin_url('admin-ajax.php'),

		// 		'create_album_nonce' => wp_create_nonce("mp_create_album_nonce"),
		// 		'get_album_nonce' => wp_create_nonce("mp_get_album_nonce"),
		// 		'get_albums_nonce' => wp_create_nonce("mp_get_albums_nonce"),
		// 		'update_album_nonce' => wp_create_nonce("mp_update_album_nonce"),
		// 		'delete_album_nonce' => wp_create_nonce("mp_delete_album_nonce"),

		// 		'create_photo_nonce' => wp_create_nonce("mp_create_photo_nonce"),
		// 		'get_photo_nonce' => wp_create_nonce("mp_get_photo_nonce"),
		// 		'get_photos_nonce' => wp_create_nonce("mp_get_photos_nonce"),
		// 		'update_photo_nonce' => wp_create_nonce("mp_update_photo_nonce"),
		// 		'delete_photo_nonce' => wp_create_nonce("mp_delete_photo_nonce"),

		// 		'add_album_photo_nonce' => wp_create_nonce("mp_add_album_photo_nonce"),
		// 		'get_album_photo_nonce' => wp_create_nonce("mp_get_album_photo_nonce"),
		// 		'delete_album_photo_nonce' => wp_create_nonce("mp_delete_album_photo_nonce")
		// 	]
		// );
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

		$id = Mp_Photo_Manager_Db::create_album($name, $desc);
		wp_die(json_encode(Mp_Photo_Manager_Db::get_album($id)[0]));
	}

	/**
	 * Ajax callback for the Get Album action.
	 *
	 * @since    1.0.0
	 */
	public function mp_get_album()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_get_album_nonce")) {
			exit("Couldn't verify nonce.");
		}
		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

		wp_die(json_encode(Mp_Photo_Manager_Db::get_album($id)));
	}

	/**
	 * Ajax callback for the Get Albums action.
	 *
	 * @since    1.0.0
	 */
	public function mp_get_albums()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_get_albums_nonce")) {
			exit("Couldn't verify nonce.");
		}

		wp_die(json_encode(Mp_Photo_Manager_Db::get_albums()));
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

	/**
	 * Ajax callback for the Update Album action.
	 *
	 * @since    1.0.0
	 */
	public function mp_update_album()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_update_album_nonce")) {
			exit("Couldn't verify nonce.");
		}
		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
		$desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);

		Mp_Photo_Manager_Db::update_album($id, $name, $desc);
	}

	/**
	 * Ajax callback for the Create Photo action.
	 *
	 * @since    1.0.0
	 */
	public function mp_create_photo()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_create_photo_nonce")) {
			exit("Couldn't verify nonce.");
		}

		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
		$desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
		$path = filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);

		Mp_Photo_Manager_Db::create_photo($name, $desc, $path);
	}

	/**
	 * Ajax callback for the Get Photo action.
	 *
	 * @since    1.0.0
	 */
	public function mp_get_photo()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_get_photo_nonce")) {
			exit("Couldn't verify nonce.");
		}
		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

		wp_die(json_encode(Mp_Photo_Manager_Db::get_photo($id)));
	}

	/**
	 * Ajax callback for the Get Photos action.
	 *
	 * @since    1.0.0
	 */
	public function mp_get_photos()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_get_photos_nonce")) {
			exit("Couldn't verify nonce.");
		}

		wp_die(json_encode(Mp_Photo_Manager_Db::get_photos()));
	}

	/**
	 * Ajax callback for the Update Photo action.
	 *
	 * @since    1.0.0
	 */
	public function mp_update_photo()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_update_photo_nonce")) {
			exit("Couldn't verify nonce.");
		}

		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
		$desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
		$path = filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);

		Mp_Photo_Manager_Db::update_photo($id, $name, $desc, $path);
	}

	/**
	 * Ajax callback for the Update Photo action.
	 *
	 * @since    1.0.0
	 */
	public function mp_delete_photo()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_delete_photo_nonce")) {
			exit("Couldn't verify nonce.");
		}

		$photo_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);


		Mp_Photo_Manager_Db::delete_photo($photo_id);
	}

	/**
	 * Ajax callback for the Add Photo to Album action.
	 *
	 * @since    1.0.0
	 */
	public function mp_add_album_photo()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_add_album_photo_nonce")) {
			exit("Couldn't verify nonce.");
		}

		$photo_id = filter_input(INPUT_POST, 'photo_id', FILTER_SANITIZE_STRING);
		$album_id = filter_input(INPUT_POST, 'album_id', FILTER_SANITIZE_STRING);


		Mp_Photo_Manager_Db::add_album_photo($album_id, $photo_id);
	}

	/**
	 * Ajax callback for the Get Album Photos action.
	 *
	 * @since    1.0.0
	 */
	public function mp_get_album_photos()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_get_album_photos_nonce")) {
			exit("Couldn't verify nonce.");
		}

		$album_id = filter_input(INPUT_POST, 'album_id', FILTER_SANITIZE_STRING);


		wp_die(json_encode(Mp_Photo_Manager_Db::get_album_photos($album_id)));
	}

	/**
	 * Ajax callback for the Delete Photo from Album action.
	 *
	 * @since    1.0.0
	 */
	public function mp_delete_album_photo()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], "mp_delete_album_photo_nonce")) {
			exit("Couldn't verify nonce.");
		}

		$photo_id = filter_input(INPUT_POST, 'photo_id', FILTER_SANITIZE_STRING);
		$album_id = filter_input(INPUT_POST, 'album_id', FILTER_SANITIZE_STRING);


		Mp_Photo_Manager_Db::delete_album_photo($album_id, $photo_id);
	}
}
