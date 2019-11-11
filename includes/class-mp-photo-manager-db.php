<?php

class Mp_Photo_Manager_Db
{

  /**
   * Creates plugin database and tables.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function create_db()
  {
    global $wpdb;
    $dbName = "mp_photo_manager";

    // Create database.
    $qry = "CREATE DATABASE IF NOT EXISTS {$dbName}";
    $wpdb->get_results($qry);

    // Create Albums table.
    $qry = "CREATE TABLE IF NOT EXISTS {$dbName}.albums (
					id INT AUTO_INCREMENT,
					user_id INT,
					title VARCHAR(255) NOT NULL,
					description VARCHAR(1023),
					created DATETIME NOT NULL,
					updated DATETIME,
					PRIMARY KEY (id))";
    $wpdb->get_results($qry);

    // Create Photos table.
    $qry = "CREATE TABLE IF NOT EXISTS {$dbName}.photos (
					id INT AUTO_INCREMENT,
					user_id INT,
					title VARCHAR(255) NOT NULL,
					description VARCHAR(1023),
					created DATETIME NOT NULL,
					updated DATETIME,
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

  /**
   * Creates a new album.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function create_album($name, $desc = "")
  {
    // Create album
    global $wpdb;
    $dbName = "mp_photo_manager";

    $userId = get_current_user_id();
    $now = time();

    $qry = "INSERT INTO {$dbName}.albums (user_id, title, description, created, updated)
      VALUES ({$userId}, '{$name}', '{$desc}', FROM_UNIXTIME({$now}), FROM_UNIXTIME({$now}))
    ";
    $wpdb->get_results($qry);
  }

  /**
   * Deletes an album.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function delete_album($album_id)
  {
    // Delete album
  }

  /**
   * Adds a photo to an album.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function add_album_photo($album_id, $photo_path)
  {
    // Add photo to album
  }

  /**
   * Removes a photo from an album.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function remove_album_photo($id)
  {
    // Add photo to album
  }

  /**
   * Deletes a photo.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function delete_photo($id)
  {
    // Deletes photo from album
  }
}
