(function($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  jQuery(function() {
    // Load Albums on load
    //loadAlbums();

    function loadAlbums() {
      $.ajax({
        url: mp_ajax_object.ajax_url + "?action=mp_get_albums",
        type: "post",
        dataType: "json",
        data: {
          nonce: mp_ajax_object.get_albums_nonce
        },
        success: function(data) {
          $("#mp_photo_manager .album-list").empty();

          // Build Album list
          for (let album in data) {
            const albumTitle = data[album].title;
            $("#mp_photo_manager .album-list").append(
              `<div class='album'>
									<h3 class='album-name'>${albumTitle}</h3>
									<span class='delete-album'>X</span>
								</div>`
            );
          }

          // Add the New Album button to the end
          $(".album-list").append(`
					<div class='create-album'>
						<a class='btn' href='#modal_new_album' rel='modal:open'>+ New Album</a>
					</div>
          `);
        }
      });
    }

    function loadAlbumDetails() {}

    function loadPhotos() {}

    function loadPhotoDetails() {}

    $("button#save_new_album").click(function(event) {
      event.preventDefault();

      // const name = $('#mp_create_album input#mp_name').val();
      // const desc = $('#mp_create_album input#mp_desc').val();
      // const data = {
      //   action: 'mp_create_album',
      //   name: name,
      //   desc: desc,
      //   create_album_nonce: mp_ajax_object.create_album_nonce
      // };
      $.ajax({
        url: mp_ajax_object.ajax_url + "?action=mp_create_album",
        type: "post",
        dataType: "json",
        data: $("form#mp_create_album").serialize(),
        success: function(data) {
          $("#modal_new_album a.mp-close").click();
        }
      });

      setTimeout(function() {
        loadAlbums();
      }, 750);
    });

    const el = "#mp_photo_manager .album";
    const className = "mp-selected";
    $(document).on("click", el, function(event) {
      $(el).removeClass(className);
      $(event.target).addClass(className);
    });
  });
})(jQuery);
