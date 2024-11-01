jQuery(document).ready(function($) {
  "use strict";

  jQuery('.shortcode-previev a').on('click', function(e) {
    e.preventDefault();
    jQuery('#shortcode-preview-modal').show();
  });

  jQuery(document).on('click', '#shortcode-preview-close', function(e) {
    e.preventDefault();
    jQuery('#shortcode-preview-modal').hide();
  });

  jQuery(document).on('keyup',function(e) {
    if(e.keyCode == 27) {
      jQuery('#shortcode-preview-modal').hide();
    }
  });

  jQuery('#shortcode-preview-submit').submit(function(e) {
    e.preventDefault()
    var shortcode = jQuery('#shortcode-preview-input').val();
    var type = jQuery('#shortcode-preview-type option:selected').val();
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        dataType: "json",
        data: {
            action    : 'shortcode_preview_generate_preview',
            shortcode : shortcode,
            type      : type
        },
        beforeSend: function() {
           jQuery('.loader').show();
        },
        success: function(res) {
          jQuery('.loader').hide();
          if(res.status == false) {
            jQuery('.shortcode-preview-result').html(res.message);
          } else {
            var post_id = res.data.post_id;
            var url = res.data.url;
            var height = jQuery(window).height() - 147; // 75px #shortcode-preview-header, 40px padding #shortcode-preview-modal, 32px #wpadminbar
            jQuery('.shortcode-preview-btn-wrap').show();
            jQuery('#shortcode-preview-delete').data('id', post_id);
            jQuery('#shortcode-preview-preview').attr('href', url);
            var frame = '<iframe src="' + url + '" frameBorder="0" style="width:100%;height:' + height + 'px"></iframe>';
            jQuery('.shortcode-preview-result').html(frame);
            /* if hide admin bar */
            jQuery('iframe').load( function() {
              jQuery('iframe').contents().find("head").append(jQuery("<style type='text/css'>  #wpadminbar{display:none!important;}  </style>"));
            });
          }
        }
    });
  });

  jQuery(document).on('click', '#shortcode-preview-delete', function(e) {
    e.preventDefault()
    var post_id = jQuery(this).data('id');
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        dataType: "json",
        data: {
            action    : 'shortcode_preview_delete_preview',
            post_id   : post_id,
        },
        beforeSend: function() {
           jQuery('.loader').show();
        },
        success: function(res) {
          jQuery('.loader').hide();
          jQuery('.shortcode-preview-result').html(res.message);
          if(res.status == true) {
            jQuery('#shortcode-preview-input').val('');
            jQuery('.shortcode-preview-btn-wrap').hide();
          }
        }
    });
  });

  jQuery(document).on('click', '#shortcode-preview-delete-all', function(e) {
    e.preventDefault()
    jQuery.ajax({
        context: this,
        url: ajaxurl,
        type: 'post',
        dataType: "json",
        data: {
            action    : 'shortcode_preview_delete_preview_all',
        },
        beforeSend: function() {
           jQuery(this).css('opacity', 0.2);
        },
        success: function(res) {
          if(res.status == true) {
            jQuery(this).remove();
          }
          jQuery('.shortcode-preview-delete-all-message').html(res.message);
        }
    });
  });
});
