/**
 * @file
 * Javascript file for UCONN Theme more and lessing.
 */

(function ($) {

    // Adds more/less toggle functionality.
    Drupal.behaviors.uconnMoreToggle = {
        attach: function(context, settings) {
            // show more
            if (!$(".uconn-show-more").hasClass('processed')) {
                $(".uconn-show-more").click(function(e) {
                    // toggle class .hidden
                    $(".uconn-short-description, .uconn-full-description").toggleClass('uconn-hidden');
                    if ($(this).text() == Drupal.t('[more]')) {
                        $(this).text(Drupal.t('[less]'));
                    }
                    else {
                        $(this).text(Drupal.t('[more]'));
                    }
                    e.preventDefault();
                });
                $(".uconn-show-more").addClass('processed');
            }
        }
    }
 })(jQuery);
