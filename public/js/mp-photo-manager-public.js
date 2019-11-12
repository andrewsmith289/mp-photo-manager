(function($) {
  'use strict';

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
    jQuery('button#save_new_album').click(function(event) {
      event.preventDefault();
      console.log('hi');

      const name = $('#mp_create_album input#mp_name').val();
      const desc = $('#mp_create_album input#mp_desc').val();
      const data = {
        action: 'mp_create_album',
        name: name,
        desc: desc,
        create_album_nonce: mp_ajax_object.create_album_nonce
      };
      console.log(mp_ajax_object.ajax_url + '&action=mp_create_album');
      $.ajax({
        url: mp_ajax_object.ajax_url + '?action=mp_create_album',
        type: 'post',
        dataType: 'json',
        data: $('form#mp_create_album').serialize(),
        success: function(data) {
          console.log('success!');
          $('#modal_new_album a.mp-close').click();
        }
      });
    });

    function loadAlbums() {}

    function loadAlbumDetails() {}

    function loadPhotos() {}

    function loadPhotoDetails() {}
  });

  // $.get(
  //   mp_ajax_object.ajax_url,
  //   {
  //     action: 'mp_get_albums',
  //     nonce: mp_ajax_object.get_albums_nonce
  //   },
  //   function(response) {
  //     if (undefined !== response.success && false === response.success) {
  //       return;
  //     }

  //     // Parse your response here.
  //     console.log(response);
  //   }
  // );
})(jQuery);
