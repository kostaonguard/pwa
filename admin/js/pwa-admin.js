(function($) {
  'use strict';

  /**
   * Shows spinner on form submit
   * @return {undefined}
   */
  var showSpinner = function() {
    $('form').submit(function() {
      $(this).find('.spinner').addClass('is-active');
    });
  };

  $(document).ready(function() {
    showSpinner();
  });

  /**
   * Settings screen JS
   */

  // The "Remove" button (remove the value from input type='hidden')
  $('.remove_image_button').click(function() {
  	var answer = confirm('Are you sure?');
  	if (answer == true) {
  		var src = $(this).parent().prev().attr('data-src');
  		$(this).parent().prev().attr('src', src);
  		$(this).prev().prev().val('');
  	}
  	return false;
  });

  $('input[type=color]').wpColorPicker();

  var PWA_WP_Settings = {

    init: function() {
      this.general();
    },

    general: function() {

      var file_frame;
      window.formfield = '';

      $(document.body).on('click', 'input[class^=upload_]', function(e) {

        e.preventDefault();
        var id_field = '';
        var btn = $(this);

        window.formfield = $(this).parent().prev();

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
          frame: 'post',
          title: 'Choose Image',
          multiple: false,
          library: {
            type: 'image'
          },
          button: {
            text: 'Use Image'
          }
        });

        file_frame.on('menu:render:default', function(view) {
          // Store our views in an object.
          var views = {};

          // Unset default menu items
          view.unset('library-separator');
          view.unset('gallery');
          view.unset('featured-image');
          view.unset('embed');

          // Initialize the views in our view object.
          view.set(views);
        });

        // When an image is selected, run a callback.
        file_frame.on('insert', function() {

          var id_field = 'preview_image_' + btn.attr('id');

          console.log(id_field);

          var selection = file_frame.state().get('selection');

          selection.each(function(attachment, index) {

            attachment = attachment.toJSON();
            window.formfield.val(attachment.url);

            $('#manifest_icons_0_sizes').val(attachment.width +'x'+ attachment.height);
            $('#manifest_icons_0_type').val(attachment.mime);

            /* Image preview */
            var img = $('<img />');
            img.attr('src', attachment.url);
            // replace previous image with new one if selected
            $('#' + id_field).empty().append(img);

            // show preview div when image exists
            if ($('#' + id_field + ' img')) {
              $('#' + id_field).show();
            }

          });
        });

        // Finally, open the modal
        file_frame.open();
      });


      // WP 3.5+ uploader
      var file_frame;
      window.formfield = '';
    }

  }
  PWA_WP_Settings.init();

})(jQuery);
