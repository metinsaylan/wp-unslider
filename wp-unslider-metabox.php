<?php 

function wp_unslider_render_image_attachment_box($post) {

    $existing_image_id = get_post_meta($post->ID,'_wpun_image', true);
    if( is_numeric($existing_image_id)) {

        echo '<div>';
            $arr_existing_image = wp_get_attachment_image_src($existing_image_id, 'large');
            $existing_image_url = $arr_existing_image[0];
            echo '<img src="' . $existing_image_url . '" />';
        echo '</div>';

    }

    // If there is an existing image, show it
    if($existing_image) {

        echo '<div>Attached Image ID: ' . $existing_image . '</div>';

    } 

    echo 'Upload an image: <input type="file" name="wpun_image" id="wpun_image" />';

    // See if there's a status message to display (we're using this to show errors during the upload process, though we should probably be using the WP_error class)
    $status_message = get_post_meta($post->ID,'_wpun_image_upload_feedback', true);

    // Show an error message if there is one
    if($status_message) {

        echo '<div class="upload_status_message">';
            echo $status_message;
        echo '</div>';

    }

}



function wpun_setup_meta_boxes() {

    // Add the box to a particular custom content type page
	add_meta_box( 'wpun_details', 'Slide Informations', 'wp_unslider_post_custom_metas', 'slide', 'normal', 'high');
    //add_meta_box( 'wpun_image_box', 'Upload Image', 'wp_unslider_render_image_attachment_box', 'slide', 'normal', 'high');


}
add_action('admin_init','wpun_setup_meta_boxes');




// add_action( 'save_post', 'wp_unslider_save_post_metas', 99 );

/* Prints the box content */
// add_action( 'edit_form_after_title', 'wp_unslider_post_custom_metas' );
function wp_unslider_post_custom_metas( $post ) {
	global $post;
	
	if( get_post_type($post) === "slide" ){

	// Use nonce for verification
	wp_nonce_field( 'wp_unslider_post_custom_metas', 'wp_unslider_noncename' );

	$wpun_image_url = get_post_meta($post->ID, 'wpun_image_url', true); 
	$wpun_text = get_post_meta($post->ID, 'wpun_text', true); 
	
?>

	<div class='tm-row'>
		<div class="tm-label"><label for="wpun_image_url">Image URL</label></div>
		<div class="tm-field"><input type="text" id="wpun_image_url" name="wpun_image_url" value="<?php echo $wpun_image_url; ?>" size="120" class="" /></div>
	</div>
	
	<div class='tm-row'>
		<div class="tm-label"><label for="wpun_text">Sub Text</label></div>
		<div class="tm-field"><textarea type="text" id="wpun_text" name="wpun_text" rows="8" cols="120" class="" /><?php echo $wpun_text; ?></textarea></div>
	</div>
		
<?php

	}
}

function wp_unslider_update_post($post_id, $post) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		  return;
	  if ( !wp_verify_nonce( @$_POST['wp_unslider_noncename'], 'wp_unslider_post_custom_metas' ) )
		  return;
		  
	if ( 'slide' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;
		
		$wpun_image_url = @$_POST['wpun_image_url'];
		update_post_meta( $post_id, 'wpun_image_url', $wpun_image_url );	
		
		$wpun_text = @$_POST['wpun_text'];
		update_post_meta( $post_id, 'wpun_text', $wpun_text );
		
		if(isset($_FILES['wpun_image']) && ($_FILES['wpun_image']['size'] > 0)) {

			// Get the type of the uploaded file. This is returned as "type/extension"
			$arr_file_type = wp_check_filetype(basename($_FILES['wpun_image']['name']));
			$uploaded_file_type = $arr_file_type['type'];
			$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
			if( in_array( $uploaded_file_type, $allowed_file_types ) ) {

				$upload_overrides = array( 'test_form' => false ); 

				// Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
				$uploaded_file = wp_handle_upload( $_FILES['wpun_image'], $upload_overrides );

				// If the wp_handle_upload call returned a local path for the image
				if( isset( $uploaded_file['file'] ) ) {

					$file_name_and_location = $uploaded_file['file'];
					$file_title_for_media_library = 'your title here';

					// Set up options array to add this file as an attachment
					$attachment = array(
						'post_mime_type' => $uploaded_file_type,
						'post_title' => 'Uploaded image ' . addslashes( $file_title_for_media_library ),
						'post_content' => '',
						'post_status' => 'inherit'
					);

					// Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
					$attach_id = wp_insert_attachment( $attachment, $file_name_and_location );
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
					wp_update_attachment_metadata($attach_id,  $attach_data);

					// Before we update the post meta, trash any previously uploaded image for this post.
					// You might not want this behavior, depending on how you're using the uploaded images.
					$existing_uploaded_image = (int) get_post_meta($post_id,'_wpun_image', true);
					if( is_numeric( $existing_uploaded_image ) ) {
						wp_delete_attachment( $existing_uploaded_image );
					}

					// Now, update the post meta to associate the new image with the post
					update_post_meta($post_id,'_wpun_image',$attach_id);

					// Set the feedback flag to false, since the upload was successful
					$upload_feedback = false;


				} else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.

					$upload_feedback = 'There was a problem with your upload.';
					update_post_meta( $post_id, '_wpun_image', $attach_id );

				}

			} else { // wrong file type

				$upload_feedback = 'Please upload only image files (jpg, gif or png).';
				update_post_meta( $post_id, '_wpun_image', $attach_id );

			}

		} else { 

			$upload_feedback = false;

		}

		// Update the post meta with any feedback
		update_post_meta( $post_id, '_wpun_image_upload_feedback', $upload_feedback);
		
	}
}
add_action( 'save_post', 'wp_unslider_update_post', 99, 2);


