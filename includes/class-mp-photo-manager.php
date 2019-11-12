<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://someone
 * @since      1.0.0
 *
 * @package    Mp_Photo_Manager
 * @subpackage Mp_Photo_Manager/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mp_Photo_Manager
 * @subpackage Mp_Photo_Manager/includes
 * @author     mp <mp@null.com>
 */
class Mp_Photo_Manager
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mp_Photo_Manager_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('MP_PHOTO_MANAGER_VERSION')) {
			$this->version = MP_PHOTO_MANAGER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mp-photo-manager';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcodes();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mp_Photo_Manager_Loader. Orchestrates the hooks of the plugin.
	 * - Mp_Photo_Manager_i18n. Defines internationalization functionality.
	 * - Mp_Photo_Manager_Admin. Defines all hooks for the admin area.
	 * - Mp_Photo_Manager_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mp-photo-manager-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mp-photo-manager-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-mp-photo-manager-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-mp-photo-manager-public.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mp-photo-manager-db.php';

		$this->loader = new Mp_Photo_Manager_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mp_Photo_Manager_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Mp_Photo_Manager_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Mp_Photo_Manager_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		$plugin_public = new Mp_Photo_Manager_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		// Register AJAX actions for Photo Manager.
		$this->loader->add_action('wp_ajax_mp_create_album', $plugin_public, 'mp_create_album');
		$this->loader->add_action('wp_ajax_mp_get_album', $plugin_public, 'mp_get_album');
		$this->loader->add_action('wp_ajax_mp_get_albums', $plugin_public, 'mp_get_albums');
		$this->loader->add_action('wp_ajax_mp_delete_album', $plugin_public, 'mp_delete_album');
		$this->loader->add_action('wp_ajax_mp_update_album', $plugin_public, 'mp_update_album');

		$this->loader->add_action('wp_ajax_mp_create_photo', $plugin_public, 'mp_create_photo');
		$this->loader->add_action('wp_ajax_mp_get_photo', $plugin_public, 'mp_get_photo');
		$this->loader->add_action('wp_ajax_mp_get_photos', $plugin_public, 'mp_get_photos');
		$this->loader->add_action('wp_ajax_mp_update_photo', $plugin_public, 'mp_update_photo');
		$this->loader->add_action('wp_ajax_mp_delete_photo', $plugin_public, 'mp_delete_photo');

		$this->loader->add_action('wp_ajax_mp_add_album_photo', $plugin_public, 'mp_add_album_photo');
		$this->loader->add_action('wp_ajax_mp_get_album_photos', $plugin_public, 'mp_get_album_photos');
		$this->loader->add_action('wp_ajax_mp_delete_album_photo', $plugin_public, 'mp_delete_album_photo');
	}

	/**
	 * Register the plugin shortcodes.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcodes()
	{
		add_shortcode('mp-photo-manager', array($this, 'mp_album_manager_shortcode'));
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mp_Photo_Manager_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * Renders the Album Manager Shortcode.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function mp_album_manager_shortcode()
	{
		$create_album_nonce = wp_create_nonce("mp_create_album_nonce");
		$get_album_nonce = wp_create_nonce("mp_get_album_nonce");
		$get_albums_nonce = wp_create_nonce("mp_get_albums_nonce");
		$delete_album_nonce = wp_create_nonce("mp_delete_album_nonce");
		$update_album_nonce = wp_create_nonce("mp_update_album_nonce");

		$create_photo_nonce = wp_create_nonce("mp_create_photo_nonce");
		$get_photo_nonce = wp_create_nonce("mp_get_photo_nonce");
		$get_photos_nonce = wp_create_nonce("mp_get_photos_nonce");
		$update_photo_nonce = wp_create_nonce("mp_update_photo_nonce");
		$delete_photo_nonce = wp_create_nonce("mp_delete_photo_nonce");

		$add_album_photo_nonce = wp_create_nonce("mp_add_album_photo_nonce");
		$get_album_photos_nonce = wp_create_nonce("mp_get_album_photos_nonce");
		$delete_album_photo_nonce = wp_create_nonce("mp_delete_album_photo_nonce");

		$link = admin_url("admin-ajax.php");
		return "
			<div id='mp_photo_manager'>
  <div class='photo-large'>
    <img href='#' alt='Photo name' />
  </div>
  <div class='album-strip'>
    <div class='album-details'>
      <h1>Album Details</h1>
      <h2 class='album-name'>Album Name</h2>
      <p class='album-description'>Album Description</p>
    </div>
    <div class='album-list'>
      <div class='album'>
        <span class='album-name'>Album Name</span>
        <span class='delete-album'>X</span>
      </div>
      <div class='create-album'>
        <a href='#modal_new_album' rel='modal:open'>+ New Album</a>
			</div>
			<div id='modal_new_album' class='modal'>
				<h1> Create Album </h1>
			<form id='mp_create_album'>
				Name: <input id='mp_name' type='text' name='name'><br>
				Description: <input id='mp_desc' type='textarea' name='desc'><br>
				<input type='hidden' name='nonce' value='{$create_album_nonce}' />
				<input type='hidden' name='action' value='mp_create_album' />

				<button id='save_new_album'>Save</button>
			</form>
				<a class='mp-close' href='#' rel='modal:close'>Close</a>
			</div>
    </div>
  </div>
  <div class='photo-strip'>
    <div class='photo-details'>
      <h1>Photo Details</h1>
      <h2 class='photo-name'>Photo Name</h2>
      <p class='photo-description'>Photo Description</p>
    </div>
    <div class='photo-list'>
			<div class='photo'>
				<img href='#' alt='thumbnail' />
        <span class='photo-name'>Photo Name</span>
        <span class='delete-photo'>X</span>
      </div>
      <div class='upload-photo'>
        + Upload Photo
      </div>
    </div>
  </div>
</div>


			<h1> Create Album </h1>
			<form action='{$link}' method='post'>
			Name: <input type='text' name='name'><br>
			Description: <input type='textarea' name='desc'><br>
			<input type='hidden' name='nonce' value='{$create_album_nonce}' />
			<input type='hidden' name='action' value='mp_create_album' />

			<input type='submit'>
			</form>

			<h1> Get Album </h1>
			<form action='{$link}' method='post'>
			id: <input type='text' name='id'><br>
			<input type='hidden' name='nonce' value='{$get_album_nonce}' />
			<input type='hidden' name='action' value='mp_get_album' />

			<input type='submit'>
			</form>

			<h1> Get Albums </h1>
			<form action='{$link}' method='post'>
			<input type='hidden' name='nonce' value='{$get_albums_nonce}' />
			<input type='hidden' name='action' value='mp_get_albums' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Delete Album </h1>
			<form action='{$link}' method='post'>
			ID: <input type='text' name='id'><br>
			<input type='hidden' name='nonce' value='{$delete_album_nonce}' />
			<input type='hidden' name='action' value='mp_delete_album' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Update Album </h1>
			<form action='{$link}' method='post'>
			id: <input type='text' name='id'><br>
			Name: <input type='text' name='name'><br>
			Description: <input type='textarea' name='desc'><br>
			<input type='hidden' name='nonce' value='{$update_album_nonce}' />
			<input type='hidden' name='action' value='mp_update_album' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Add New Photo </h1>
			<form action='{$link}' method='post'>			
			Name: <input type='text' name='name'><br>
			Description: <input type='textarea' name='desc'><br>
			Path: <input type='text' name='path'><br>
			<input type='hidden' name='nonce' value='{$create_photo_nonce}' />
			<input type='hidden' name='action' value='mp_create_photo' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Get Photo </h1>
			<form action='{$link}' method='post'>
			id: <input type='text' name='id'><br>
			<input type='hidden' name='nonce' value='{$get_photo_nonce}' />
			<input type='hidden' name='action' value='mp_get_photo' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Get Photos </h1>
			<form action='{$link}' method='post'>			
			<input type='hidden' name='nonce' value='{$get_photos_nonce}' />
			<input type='hidden' name='action' value='mp_get_photos' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Update Photo </h1>
			<form action='{$link}' method='post'>
			ID: <input type='text' name='id'><br>
			Name: <input type='text' name='name'><br>
			Description: <input type='textarea' name='desc'><br>
			Path: <input type='text' name='path'><br>
			<input type='hidden' name='nonce' value='{$update_photo_nonce}' />
			<input type='hidden' name='action' value='mp_update_photo' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Delete Photo </h1>
			<form action='{$link}' method='post'>
			ID: <input type='text' name='id'><br>
			<input type='hidden' name='nonce' value='{$delete_photo_nonce}' />
			<input type='hidden' name='action' value='mp_delete_photo' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Add Photo to Album </h1>
			<form action='{$link}' method='post'>
			Album ID: <input type='text' name='album_id'><br>
			Photo ID: <input type='text' name='photo_id'><br>
			<input type='hidden' name='nonce' value='{$add_album_photo_nonce}' />
			<input type='hidden' name='action' value='mp_add_album_photo' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Get Album Photos </h1>
			<form action='{$link}' method='post'>
			Album ID: <input type='text' name='album_id'><br>
			<input type='hidden' name='nonce' value='{$get_album_photos_nonce}' />
			<input type='hidden' name='action' value='mp_get_album_photos' />

			<input type='submit'>
			</form>

			<hr />
			<h1> Delete Photo from Album </h1>
			<form action='{$link}' method='post'>
			Album ID: <input type='text' name='album_id'><br>
			Photo ID: <input type='text' name='photo_id'><br>
			<input type='hidden' name='nonce' value='{$delete_album_photo_nonce}' />
			<input type='hidden' name='action' value='mp_delete_album_photo' />

			<input type='submit'>
			</form>


		";
	}
}
