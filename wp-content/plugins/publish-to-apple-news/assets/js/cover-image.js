(function ( $, window, undefined ) {
  'use strict';

  // Set up add and remove image functionality.
  $( '.apple-news-coverimage-image-container' ).each( function () {
    var $this = $( this ),
      $addImgButton = $this.find( '.apple-news-coverimage-add' ),
      $delImgButton = $this.find( '.apple-news-coverimage-remove' ),
      $imgContainer = $this.find( '.apple-news-coverimage-image' ),
      $imgIdInput = $this.find( '.apple-news-coverimage-id' ),
      frame;

    // Set up handler for remove image functionality.
    $delImgButton.on( 'click', function() {
      $imgContainer.empty();
      $addImgButton.removeClass( 'hidden' );
      $delImgButton.addClass( 'hidden' );
      $imgIdInput.val( '' );
    } );

    // Set up handler for add image functionality.
    $addImgButton.on( 'click', function () {

      // Open frame, if it already exists.
      if ( frame ) {
        frame.open();
        return;
      }

      // Set configuration for media frame.
      frame = wp.media( { multiple: false } );

      // Set up handler for image selection.
      frame.on( 'select', function () {

        // Get information about the attachment.
        var attachment = frame.state().get( 'selection' ).first().toJSON(),
          imgUrl = attachment.url;

        // Set image URL to medium size, if available.
        if ( attachment.sizes.medium && attachment.sizes.medium.url ) {
          imgUrl = attachment.sizes.medium.url;
        }

        // Clear current values.
        $imgContainer.empty();
        $imgIdInput.val( '' );

        // Add the image and ID, swap visibility of add and remove buttons.
        $imgContainer.append( '<img src="' + imgUrl + '" alt="" />' ); // phpcs:ignore WordPressVIPMinimum.JS.StringConcat.Found, WordPressVIPMinimum.JS.HTMLExecutingFunctions.append
        $imgIdInput.val( attachment.id );
        $addImgButton.addClass( 'hidden' );
        $delImgButton.removeClass( 'hidden' );
      } );

      // Open the media frame.
      frame.open();
    } );
  } );
})( jQuery, window );
