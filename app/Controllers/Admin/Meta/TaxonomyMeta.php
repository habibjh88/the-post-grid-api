<?php
/**
 * Action Hooks class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers\Admin\Meta;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Action Hooks class.
 */
class TaxonomyMeta {
	/**
	 * Class init.
	 *
	 * @return void
	 */

	public function __construct() {
		add_filter( 'manage_edit-layout_category_columns', [ $this, 'edit_term_columns' ], 10, 3 );
		add_filter( 'manage_layout_category_custom_column', [ $this, 'manage_term_custom_column' ], 10, 3 );
		add_action( 'layout_category_add_form_fields', [ $this, 'add_category_image' ], 10, 2 );
		add_action( 'created_layout_category', [ $this, 'save_category_image' ], 10, 2 );
		add_action( 'layout_category_edit_form_fields', [ $this, 'update_category_image' ], 10, 2 );
		add_action( 'edited_layout_category', [ $this, 'updated_category_image' ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_media' ] );
		add_action( 'admin_footer', [ $this, 'add_script' ] );
	}


	/**
	 * Add Category Column
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function edit_term_columns( $columns ) {

		$columns[ rtTPGApi()->rttpg_cat_thumbnail ] = esc_html__( 'Image', 'the-post-grid-api' );
		$columns[ rtTPGApi()->rttpg_cat_status ]    = esc_html__( 'Status', 'the-post-grid-api' );

		return $columns;
	}

	/**
	 * @param $out
	 * @param $column
	 * @param $term_id
	 *
	 * @return mixed|string
	 */
	public function manage_term_custom_column( $out, $column, $term_id ) {
		if ( rtTPGApi()->rttpg_cat_thumbnail === $column ) {
			$value = get_term_meta( $term_id, rtTPGApi()->rttpg_cat_thumbnail, true );
			if ( $value ) {
				$out = '<img style="width:50px;height:50px" src=' . wp_get_attachment_image_src( $value, 'thumbnail' )[0] . ' width="200" />';
			}
		}

		if ( rtTPGApi()->rttpg_cat_status === $column ) {
			$value = get_term_meta( $term_id, rtTPGApi()->rttpg_cat_status, true );
			if ( $value ) {
				$out = "<span>{$value}</span>";
			}
		}

		return $out;
	}

	public function load_media() {
		wp_enqueue_media();
	}

	/*
	 * Add a form field in the new category page
	 * @since 1.0.0
	*/
	public function add_category_image( $taxonomy ) { ?>
        <div class="form-field term-group">
            <label for="<?php echo esc_attr( rtTPGApi()->rttpg_cat_thumbnail ) ?>"><?php _e( 'Image', 'the-post-grid-api' ); ?></label>
            <input type="hidden" id="<?php echo esc_attr( rtTPGApi()->rttpg_cat_thumbnail ) ?>" name="<?php echo esc_attr( rtTPGApi()->rttpg_cat_thumbnail ) ?>" class="rttpg-cat-status" value="">
            <div id="category-image-wrapper"></div>
            <p>
                <input type="button" class="button button-secondary rttpg_media_button" id="rttpg_media_button" name="rttpg_media_button" value="<?php _e( 'Add Image', 'the-post-grid-api' ); ?>"/>
                <input type="button" class="button button-secondary rttpg_media_remove" id="rttpg_media_remove" name="rttpg_media_remove" value="<?php _e( 'Remove Image', 'the-post-grid-api' ); ?>"/>
            </p>
        </div>

        <div class="form-field term-group">
            <label for="<?php echo esc_attr( rtTPGApi()->rttpg_cat_status ) ?>"><?php _e( 'Status', 'the-post-grid-api' ); ?></label>
            <select style="min-width: 100px" name="<?php echo esc_attr( rtTPGApi()->rttpg_cat_status ) ?>" id="<?php echo esc_attr( rtTPGApi()->rttpg_cat_status ) ?>">
                <option value="pro"><?php echo esc_html('Pro') ?></option>
                <option value="free"><?php echo esc_html('Free') ?></option>
            </select>
        </div>
		<?php
	}

	/*
	 * Save the form field
	 * @since 1.0.0
	*/
	public function save_category_image( $term_id, $tt_id ) {
		if ( isset( $_POST[ rtTPGApi()->rttpg_cat_thumbnail ] ) && '' !== $_POST[ rtTPGApi()->rttpg_cat_thumbnail ] ) {
			$image = $_POST[ rtTPGApi()->rttpg_cat_thumbnail ];
			add_term_meta( $term_id, rtTPGApi()->rttpg_cat_thumbnail, $image, true );
		}

		if ( isset( $_POST[ rtTPGApi()->rttpg_cat_status ] ) && '' !== $_POST[ rtTPGApi()->rttpg_cat_status ] ) {
			$status = $_POST[ rtTPGApi()->rttpg_cat_status ];
			add_term_meta( $term_id, rtTPGApi()->rttpg_cat_status, $status, true );
		}
	}

	/*
	 * Edit the form field
	 * @since 1.0.0
	*/
	public function update_category_image( $term, $taxonomy ) { ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="<?php echo esc_attr( rtTPGApi()->rttpg_cat_thumbnail ) ?>"><?php _e( 'Image', 'the-post-grid-api' ); ?></label>
            </th>
            <td>
				<?php $image_id = get_term_meta( $term->term_id, rtTPGApi()->rttpg_cat_thumbnail, true ); ?>
                <input type="hidden" id="<?php echo rtTPGApi()->rttpg_cat_thumbnail; ?>" name="<?php echo esc_attr( rtTPGApi()->rttpg_cat_thumbnail ) ?>" value="<?php echo $image_id; ?>">
                <div id="category-image-wrapper">
					<?php if ( $image_id ) { ?>
						<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
					<?php } ?>
                </div>
                <p>
                    <input type="button" class="button button-secondary rttpg_media_button" id="rttpg_media_button" name="rttpg_media_button" value="<?php _e( 'Add Image', 'the-post-grid-api' ); ?>"/>
                    <input type="button" class="button button-secondary rttpg_media_remove" id="rttpg_media_remove" name="rttpg_media_remove" value="<?php _e( 'Remove Image', 'the-post-grid-api' ); ?>"/>
                </p>
            </td>
        </tr>

        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="<?php echo esc_attr( rtTPGApi()->rttpg_cat_status ) ?>"><?php _e( 'Image', 'the-post-grid-api' ); ?></label>
            </th>
            <td>
				<?php $status = get_term_meta( $term->term_id, rtTPGApi()->rttpg_cat_status, true ); ?>
                <select style="min-width: 100px" name="<?php echo esc_attr( rtTPGApi()->rttpg_cat_status ) ?>" id="<?php echo esc_attr( rtTPGApi()->rttpg_cat_status ) ?>">
                    <option <?php echo esc_attr($status=='pro' ? 'selected' : '') ?> value="pro"><?php echo esc_html('Pro') ?></option>
                    <option <?php echo esc_attr($status=='free' ? 'selected' : '') ?> value="free"><?php echo esc_html('Free') ?></option>
                </select>
            </td>
        </tr>
		<?php
	}

	/*
	 * Update the form field value
	 * @since 1.0.0
	 */
	public function updated_category_image( $term_id, $tt_id ) {
		if ( isset( $_POST[ rtTPGApi()->rttpg_cat_thumbnail ] ) && '' !== $_POST[ rtTPGApi()->rttpg_cat_thumbnail ] ) {
			$image = $_POST[ rtTPGApi()->rttpg_cat_thumbnail ];
			update_term_meta( $term_id, rtTPGApi()->rttpg_cat_thumbnail, $image );
		} else {
			update_term_meta( $term_id, rtTPGApi()->rttpg_cat_thumbnail, '' );
		}

		if ( isset( $_POST[ rtTPGApi()->rttpg_cat_status ] ) && '' !== $_POST[ rtTPGApi()->rttpg_cat_status ] ) {
			$image = $_POST[ rtTPGApi()->rttpg_cat_status ];
			update_term_meta( $term_id, rtTPGApi()->rttpg_cat_status, $image );
		} else {
			update_term_meta( $term_id, rtTPGApi()->rttpg_cat_status, '' );
		}
	}

	/*
	 * Add script
	 * @since 1.0.0
	 */
	public function add_script() { ?>
        <script>
            jQuery(document).ready(function ($) {
                function ct_media_upload(button_class) {
                    var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $('body').on('click', button_class, function (e) {
                        var button_id = '#' + $(this).attr('id');
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(button_id);
                        _custom_media = true;
                        wp.media.editor.send.attachment = function (props, attachment) {
                            if (_custom_media) {
                                $('#<?php echo esc_attr( rtTPGApi()->rttpg_cat_thumbnail ) ?>').val(attachment.id);
                                $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                                $('#category-image-wrapper .custom_media_image').attr('src', attachment.url).css('display', 'block');
                            } else {
                                return _orig_send_attachment.apply(button_id, [props, attachment]);
                            }
                        }
                        wp.media.editor.open(button);
                        return false;
                    });
                }

                ct_media_upload('.rttpg_media_button.button');
                $('body').on('click', '.rttpg_media_remove', function () {
                    $('#<?php echo esc_attr( rtTPGApi()->rttpg_cat_thumbnail ) ?>').val('');
                    $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                });
                // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
                $(document).ajaxComplete(function (event, xhr, settings) {
                    var queryStringArr = settings.data.split('&');
                    if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                        var xml = xhr.responseXML;
                        $response = $(xml).find('term_id').text();
                        if ($response != "") {
                            // Clear the thumb image
                            $('#category-image-wrapper').html('');
                        }
                    }
                });
            });
        </script>
	<?php }
}
