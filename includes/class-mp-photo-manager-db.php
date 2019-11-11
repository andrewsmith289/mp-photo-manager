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
          path VARCHAR(512),
					created DATETIME NOT NULL,
					updated DATETIME,
					PRIMARY KEY (id))";
    $wpdb->get_results($qry);

    // Create Photos/Albums intersection table.
    // $qry = "CREATE TABLE IF NOT EXISTS $dbName.photos_albums (
    // 			id INT NOT NULL AUTO_INCREMENT,
    // 			photo_id INT NOT NULL,
    // 			album_id INT NOT NULL,
    // 			added DATE NOT NULL,
    // 			FOREIGN KEY (photo_id) REFERENCES photos(id),
    // 			FOREIGN KEY (album_id) REFERENCES albums(id),
    // 			PRIMARY KEY (id),
    //       UNIQUE KEY pkey (photo_id, album_id))";

    // Create Photos/Albums intersection table.
    $qry = "CREATE TABLE IF NOT EXISTS $dbName.photos_albums (
					id INT NOT NULL AUTO_INCREMENT,
					photo_id INT NOT NULL,
					album_id INT NOT NULL,
					added DATE NOT NULL,
					PRIMARY KEY (id))";
    $wpdb->get_results($qry);
  }

  /**
   * Creates a new album.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function create_album($name, $desc = "")
  {

    global $wpdb;
    $dbName = "mp_photo_manager";

    $userId = get_current_user_id();
    $now = time();

    $name = esc_sql($name);
    $desc = esc_sql($desc);

    // Create album
    $qry = "INSERT INTO {$dbName}.albums (user_id, title, description, created, updated)
      VALUES ({$userId}, '{$name}', '{$desc}', FROM_UNIXTIME({$now}), FROM_UNIXTIME({$now}))
    ";
    $wpdb->get_results($qry);
  }

  /**
   * Gets an album.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function get_album($id)
  {

    global $wpdb;
    $dbName = "mp_photo_manager";

    $userId = get_current_user_id();
    $id = esc_sql($id);

    // Check Album exists and belongs to user.
    $qry = "SELECT * FROM {$dbName}.albums 
      WHERE user_id={$userId} AND id={$id}";
    $result = $wpdb->get_results($qry);

    if (count($result) == 0) {
      echo "Album not found in database, or doesn't belong to you.";
      return;
    }

    // Return the album
    return $result;
  }

  /**
   * Gets user's Albums.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function get_albums()
  {
    global $wpdb;
    $dbName = "mp_photo_manager";
    $userId = get_current_user_id();

    // Get Photos.
    $qry = "SELECT * FROM {$dbName}.albums 
      WHERE user_id={$userId}";
    $result = $wpdb->get_results($qry);

    return $result;
  }


  /**
   * Deletes an album.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function delete_album($album_id)
  {
    global $wpdb;
    $dbName = "mp_photo_manager";

    $userId = get_current_user_id();
    $album_id = esc_sql($album_id);

    // Check Album exists and belongs to user.
    if (!Mp_Photo_Manager_Db::album_exists($album_id)) {
      echo "Album not found in database, or doesn't belong to you.";
      return;
    }

    // Delete Album
    $qry = "DELETE FROM {$dbName}.albums 
      WHERE user_id={$userId} AND id={$album_id}";
    $wpdb->get_results($qry);
  }

  /**
   * Updates an album's details.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function update_album($id, $name, $desc)
  {
    // Update album details
    global $wpdb;
    $dbName = "mp_photo_manager";

    $userId = get_current_user_id();
    $now = time();

    $id = esc_sql($id);
    $name = esc_sql($name);
    $desc = esc_sql($desc);

    // Check Album exists and belongs to user.
    $qry = "SELECT * FROM {$dbName}.albums WHERE user_id={$userId} AND id={$id}";
    $result = $wpdb->get_results($qry);

    if (count($result) == 0) {
      echo "Album not found in database, or doesn't belong to you.";
      return;
    }

    $qry = "UPDATE {$dbName}.albums 
      SET title='{$name}', description='{$desc}', updated=FROM_UNIXTIME({$now}) 
      WHERE id={$id} 
    ";
    $result = $wpdb->get_results($qry);
  }

  /**
   * Determies if a given Album exists for this user.
   *
   * @since     1.0.0
   */
  private static function album_exists($album_id)
  {
    // Check Photo exists and belongs to user.
    global $wpdb;
    $dbName = "mp_photo_manager";

    $userId = get_current_user_id();

    // Check Photo exists and belongs to user.
    $qry = "SELECT * FROM {$dbName}.albums WHERE user_id={$userId} AND id={$album_id}";
    $result = $wpdb->get_results($qry);

    if (count($result) == 0) {
      echo "Album not found in database, or doesn't belong to you.";
      return false;
    }

    return true;
  }

  /**
   * Adds a photo to an album.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public static function add_album_photo($album_id, $photo_id)
  {
    global $wpdb;
    $dbName = "mp_photo_manager";

    $now = time();
    $album_id = esc_sql($album_id);
    $photo_id = esc_sql($photo_id);

    // Check Album exists and belongs to user.
    if (!Mp_Photo_Manager_Db::album_exists($album_id)) {
      echo "Album not found in database, or doesn't belong to you.";
      return;
    }

    // Check Photo exists and belongs to user.
    if (!Mp_Photo_Manager_Db::photo_exists($photo_id)) {
      echo "Photo not found in database, or doesn't belong to you.";
      return;
    }

    // Add photo to album
    $qry = "INSERT INTO {$dbName}.photos_albums (album_id, photo_id, added) 
      VALUES ({$album_id}, {$photo_id}, FROM_UNIXTIME({$now}))";
    $wpdb->get_results($qry);
  }

  /**
   * Gets Album Photos.
   *
   * @since     1.0.0
   */
  public static function get_album_photos($album_id)
  {
    global $wpdb;
    $dbName = "mp_photo_manager";
    $userId = get_current_user_id();

    // Get Photos.
    $qry = "SELECT photo_id, album_id, photos.description, path, added, photos.created, photos.updated FROM {$dbName}.photos_albums
      INNER JOIN {$dbName}.albums on photos_albums.album_id = albums.id
      INNER JOIN {$dbName}.photos on photos_albums.photo_id = photos.id    
      WHERE albums.id={$album_id} 
        AND albums.user_id={$userId}";
    $result = $wpdb->get_results($qry);

    return $result;
  }

  /**
   * Deletes a photo from an album.
   *
   * @since     1.0.0
   */
  public static function delete_album_photo($album_id, $photo_id)
  {
    global $wpdb;
    $dbName = "mp_photo_manager";

    $album_id = esc_sql($album_id);
    $photo_id = esc_sql($photo_id);

    // Check Album exists and belongs to user.
    if (!Mp_Photo_Manager_Db::album_exists($album_id)) {
      echo "Album not found in database, or doesn't belong to you.";
      return;
    }

    // Check Photo exists and belongs to user.
    if (!Mp_Photo_Manager_Db::photo_exists($photo_id)) {
      echo "Photo not found in database, or doesn't belong to you.";
      return;
    }

    // Remove photo from album
    $qry = "DELETE FROM {$dbName}.photos_albums WHERE album_id={$album_id} AND photo_id={$photo_id}";
    $result = $wpdb->get_results($qry);
  }

  /**
   * Adds a photo to user's photos.
   *
   * @since     1.0.0
   */
  public static function create_photo($name, $desc, $path)
  {
    global $wpdb;
    $dbName = "mp_photo_manager";

    $userId = get_current_user_id();
    $now = time();

    $name = esc_sql($name);
    $desc = esc_sql($desc);
    $path = esc_sql($path);

    // Add new photo
    $qry = "INSERT INTO {$dbName}.photos (user_id, title, description, path, created, updated)
      VALUES ({$userId}, '{$name}', '{$desc}', '{$path}', FROM_UNIXTIME({$now}), FROM_UNIXTIME({$now}))";
    $wpdb->get_results($qry);
  }

  /**
   * Gets a photo.
   *
   * @since     1.0.0
   */
  public static function get_photo($id)
  {
    global $wpdb;
    $dbName = "mp_photo_manager";

    $userId = get_current_user_id();
    $id = esc_sql($id);

    // Check Photo exists and belongs to User.
    $qry = "SELECT * FROM {$dbName}.photos 
      WHERE user_id={$userId} AND id={$id}";
    $result = $wpdb->get_results($qry);

    if (count($result) == 0) {
      echo "Photo not found in database, or doesn't belong to you.";
      return;
    }

    // Return the Photo
    return $result;
  }

  /**
   * Gets user's Photos.
   *
   * @since     1.0.0
   */
  public static function get_photos()
  {
    global $wpdb;
    $dbName = "mp_photo_manager";
    $userId = get_current_user_id();

    // Get Photos.
    $qry = "SELECT * FROM {$dbName}.photos 
      WHERE user_id={$userId}";
    $result = $wpdb->get_results($qry);

    return $result;
  }

  /**
   * Updates a user's photo.
   *
   * @since     1.0.0
   */
  public static function update_photo($id, $name, $desc, $path)
  {
    global $wpdb;
    $dbName = "mp_photo_manager";
    $now = time();

    $id = esc_sql($id);
    $name = esc_sql($name);
    $desc = esc_sql($desc);
    $path = esc_sql($path);

    // Check Photo exists and belongs to user.
    if (!Mp_Photo_Manager_Db::photo_exists($id)) {
      echo "Photo not found in database, or doesn't belong to you.";
      return;
    }

    // Update photo details
    $qry = "UPDATE {$dbName}.photos 
      SET title='{$name}', description='{$desc}', path='{$path}', updated=FROM_UNIXTIME({$now}) 
      WHERE id={$id}";
    $wpdb->get_results($qry);
  }

  /**
   * Deletes a user's photo.
   *
   * @since     1.0.0
   */
  public static function delete_photo($photo_id)
  {
    global $wpdb;
    $dbName = "mp_photo_manager";
    $userId = get_current_user_id();

    $photo_id = esc_sql($photo_id);

    // Check Photo exists and belongs to user.
    if (!Mp_Photo_Manager_Db::photo_exists($photo_id)) {
      echo "Photo not found in database, or doesn't belong to you.";
      return;
    }

    // Delete Photo
    $qry = "DELETE FROM {$dbName}.photos WHERE user_id={$userId} AND id={$photo_id}";
    $wpdb->get_results($qry);
  }

  /**
   * Determies if a given Photo exists for this user.
   *
   * @since     1.0.0
   */
  private static function photo_exists($photo_id)
  {
    // Check Photo exists and belongs to user.
    global $wpdb;
    $dbName = "mp_photo_manager";
    $userId = get_current_user_id();

    // Check Photo exists and belongs to user.
    $qry = "SELECT * FROM {$dbName}.photos WHERE user_id={$userId} AND id={$photo_id}";
    $result = $wpdb->get_results($qry);

    if (count($result) == 0) {
      echo "Photo not found in database, or doesn't belong to you.";
      return false;
    }

    return true;
  }
}
