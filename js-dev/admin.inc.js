(function( $ ) {
  "use strict";

  $( document ).ready(function() {
    /* ---------------------------------------------------------------------------
    * Media Uploader
    * --------------------------------------------------------------------------- */
    var mediaUploader,
        metaBox = $('.astrodj-term-group'),
        add_btn = metaBox.find('.tax_media_button'),
        remove_btn = metaBox.find( '.tax_media_remove'),
        img_container = metaBox.find( '#taxonomy-image-wrapper'),
        image = img_container.find('img'),
        placeholder = image.data('src'),
        input = metaBox.find( '#taxonomy-image-id' );

    add_btn.on( 'click', function(e) {
      e.preventDefault();
  
      if( mediaUploader ) {
        mediaUploader.open();
        return;
      }
  
      mediaUploader = wp.media({
        title: 'Choose an Image',
        button: {
          text: 'Choose Image'
        },
        multiple: false
      });
  
      mediaUploader.on( 'select', function() {
        var attachment = mediaUploader.state().get('selection').first().toJSON();

        input.val( attachment.id );
        image.attr('src', attachment.url);
      } );
  
      mediaUploader.open();
    } );

    remove_btn.on('click', function(e) {
      e.preventDefault();

      input.val('');
      image.attr('src', placeholder);
    });

    $('#addtag #submit').on('click', function() {
      input.val('');
      image.attr('src', placeholder);
    });

    /* ---------------------------------------------------------------------------
    * Generate password
    * --------------------------------------------------------------------------- */
    var btnPw = $('.astrodj-generate-pw');

    btnPw.on('click', function(e) {
      wp.ajax.post( 'generate-password' )
      .done( function( data ) {
        $('.astrodj-generate-pw').prev().val(data);
      } );
    });

  });

}(jQuery));