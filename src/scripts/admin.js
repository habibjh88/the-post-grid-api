"use strict";

(function ($) {


    $(document).ready(function () {

        //category image upload
        let meta_image_frame;
        $('#upload_image_btn').click(function (e) {
            e.preventDefault();
            if (meta_image_frame) {
                meta_image_frame.open();
                return;
            }

            // Sets up the media library frame
            meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
                title: 'Upload Category Image',
                button: {text: 'Upload Image'},
                library: {type: 'image'}
            });

            meta_image_frame.on('select', function () {
                var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
                $('.category-image').html(`<div class='category-image-wrap'><img src='${media_attachment.url}' width='200' /><input type="hidden" name="rt_category_image" value='${media_attachment.id}' class="category-image-id"/><button>x</button></div>`);
            });

            meta_image_frame.open();
        });

        $(document).on("click", ".category-image-wrap button", function () {
            $(this).parent().remove();
        });
    })


// jquery passing
})(jQuery);