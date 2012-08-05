<?php
/*
Plugin Name: Comment Link Suggest-O-Tron
Plugin URI: http://line-in.co.uk/plugins/comment-link-suggest-o-tron
Description: Want more comments? You gots to ask the right questions...
Version: 1.2.3
Author: Simon Fairbairn
Author URI: http://line-in.co.uk
*/

/*  
	Copyright 2009  Simon Fairbairn  (email : linein@simonfairbairn.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Set up the default array of questions
register_activation_hook(__FILE__, 'comment_link_suggest_o_tron_install');
if (!function_exists('comment_link_suggest_o_tron_install')) {
	function comment_link_suggest_o_tron_install() {
		$commentLinkSuggestions = array(
			"Select a preset from the list below",
			"Agree? Disagree? %--Leave a comment--% and share your thoughts!",
			"Don't forget to %--Leave a comment--%!",
			"So what to you think? %--Leave a comment--% and let me know!",
			"Have you found this useful? Do you have any questions? Thoughts? Opinions? I'd love to hear them! %--Leave a comment--% and share your views!",
			"%--Get the debate started here--%!",
			"We'd love to know what you think, so why not %--Leave a reply--%?",
			"Don't be shy, %--leave a reply--%!",
			"%--Leave a comment--% and let me know!"
		);
		update_option("commentLinkSuggestions", $commentLinkSuggestions);
	}
}




// Get the Link Suggestion and display it in the content and the RSS feed
// But only if there isn't a 'Click here for more...' thingy
add_filter('the_content', 'comment_link_suggest_o_tron_add_content'); 
add_filter('the_content_rss', 'comment_link_suggest_o_tron_add_content');
if (!function_exists('comment_link_suggest_o_tron_add_content') ) { 
	function comment_link_suggest_o_tron_add_content($content) {
		global $more;
		$id = get_the_ID(); 
		$permalink = get_permalink($id);
		$text = get_post_meta( $id, '_comment_link_suggest_o_options', true );
		
		$text['text'] = str_replace("%--", "<a href='$permalink#respond'>", $text['text']);
		$text['text'] = str_replace("--%", "</a>", $text['text']); 
		$stuff = '';
		
		if ($more || strstr($content, 'more-link' ) === false) {
			if ( $text['text'] != '' ) {

				if (1 == $text['bold']) {
					$text['text'] = "<strong>".$text['text']."</strong>";
				}
				if (1 == $text['italic']) {
					$text['text'] = "<em>".$text['text']."</em>";
				}
				$stuff = "<p class='commentLinkPlugin'> ".$text['text']."</p>";
			}
		}	

		if ( isset( $text['top'] ) && $text['top'] == 1 ) {
			$content = stripslashes($stuff) . $content;
		} else {
			$content = $content . stripslashes($stuff) ;
		}

		
		return $content;
	}
}

// Save out the information
// But check first to make sure that the user isn't using the Quick Edit
// functionality
add_action('save_post', 'comment_link_suggest_o_save');
if ( !function_exists( 'comment_link_suggest_o_save' ) ) {
	function comment_link_suggest_o_save( $post_id ) {
		if ( !isset( $_POST['commment_text_noncename'] ) || !wp_verify_nonce( $_POST['commment_text_noncename'], plugin_basename(__FILE__) )) {
			
			return $post_id;
		}
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
		if ( 'rooms' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
				return $post_id;
			} else {
			if ( !current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}		
		
		$comment_link = '';
		if ( isset( $_POST['comment_link_suggest_o_text'] ) )
			$comment_link['text'] = $_POST['comment_link_suggest_o_text'];
		if ( isset( $_POST['comment_link_suggest_o_bold'] ) ) 
			$comment_link['bold'] = 1;
		else
			$comment_link['bold'] = 0;
		if ( isset( $_POST['comment_link_suggest_o_italic'] ) ) 
			$comment_link['italic'] = 1;
		else
			$comment_link['italic'] = 0;

		if ( isset( $_POST['comment_link_suggest_o_top'] ) ) 
			$comment_link['top'] = 1;
		else
			$comment_link['top'] = 0;


		update_post_meta($post_id, '_comment_link_suggest_o_options', $comment_link);
	}
}


// Set up the form
if (!function_exists('comment_link_form') ) { 
	function comment_link_form() {
		global $post;
		$commentLink = get_post_meta( $post->ID, '_comment_link_suggest_o_options', true );
		if ( !isset($commentLink['text'] ) )
			$commentLink['text'] = '';
		if ( !isset($commentLink['bold'] ) )
			$commentLink['bold'] = 1;
		if ( !isset($commentLink['italic'] ) )
			$commentLink['italic'] = 1;
		if ( !isset($commentLink['top'] ) )
			$commentLink['top'] = 0;


		// Use nonce for verification
		echo '<input type="hidden" name="commment_text_noncename" id="comment_text_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		// The actual fields for data entry
		echo '<textarea rows="3" cols="80" name="comment_link_suggest_o_text" id="comment_link_suggest_o_text" class="attachmentlinks">'.stripslashes($commentLink['text']).'</textarea>';
		echo '<p><label for="comment_link_plugin_text">Add text to entice people to leave a comment (use %-- and --% to define what text you want to appear as a link)</label></p> ';
		echo '<p><label for="comment_link_suggest_o_bold"><strong>Bold </strong></label><input type="checkbox" value="1" name="comment_link_suggest_o_bold" id="comment_link_suggest_o_bold"';
		if ($commentLink['bold'] == 1) {
			echo " checked='checked' ";
		}
		echo ' /> ';
		echo '<label for="comment_link_suggest_o_italic"><em>Italic </em></label><input type="checkbox" value="1" name="comment_link_suggest_o_italic" id="comment_link_suggest_o_italic" ';
		if ($commentLink['italic'] == 1) {
			echo " checked='checked' ";
		}
		echo '/> ';
		echo ' <label for="comment_link_suggest_o_top">Show at top? </label><input type="checkbox" value="1" name="comment_link_suggest_o_top" id="comment_link_suggest_o_top" ';
		if ($commentLink['top'] == 1) {
			echo " checked='checked' ";
		}

		echo '/><br />';
		echo '<label for="comment_link_plugin_preset">Comment Text Presets: </label>';
		echo "<noscript>Awww, no JavaScript! You're missing out on the best part!</noscript>";
		echo '<span id="comment-link-suggest-o-ajax"></span></p>';
		

	
	}
}


add_action('admin_menu', 'comment_link_suggest_o_editor');
if (!function_exists('comment_link_suggest_o_editor') ) {
	function comment_link_suggest_o_editor() { 
		add_meta_box( 'comment_link_sectionid', 'Comment Link Suggest-O-Tron', 
					'comment_link_form', 'post', 'normal' );
	}
}



// Add the scripts needed
add_action('admin_init', 'comment_link_suggest_o_tron_scripts');
if (!function_exists('comment_link_suggest_o_tron_scripts') ) {
	function comment_link_suggest_o_tron_scripts() { 
		wp_enqueue_script('jquery');
		wp_enqueue_script('comment-link-suggest-o-tron-js', WP_PLUGIN_URL . '/comment-link-suggest-o-tron/comment-link-suggest-o-tron.js', array('jquery'), '1.2.0', true);
	}
}
add_action('wp_ajax_comment_link_suggest_ajax', 'comment_link_suggest_o_tron_ajax');
if (!function_exists('comment_link_suggest_o_tron_ajax') ) {
	function comment_link_suggest_o_tron_ajax() {
		$commentLinkSuggestions = get_option("commentLinkSuggestions");
		$text = $_POST['text'];
		$id = $_POST['id'];
		$operation = $_POST['operation'];
		
		if ( 'DELETE' == $operation ) {
			if ( '0' != $id ) {
				$i = 0;
				foreach( $commentLinkSuggestions as $key => $value ) {
					if ( $id != $key ) {
						$newSuggestions[$i] = $value;
						$i++;
					}
					
				}
				update_option("commentLinkSuggestions", $newSuggestions );
		
			}

		} elseif ( 'ADD' == $operation ) {
			array_push( $commentLinkSuggestions, $text );
			update_option("commentLinkSuggestions", $commentLinkSuggestions );
			
		}

		$commentLinkSuggestions = get_option('commentLinkSuggestions');
		$maxSuggestions = sizeof($commentLinkSuggestions);

		echo '<select id="comment_link_plugin_preset" name="comment_link_plugin_preset">';
		for ($i = 0; $i < $maxSuggestions; $i++) {
			echo '<option value="'.$i.'" title="'.stripslashes($commentLinkSuggestions[$i]).'">'.stripslashes(substr($commentLinkSuggestions[$i], 0, 35));
			if ($i != 0) {
				echo '... ';
			}
			echo '</option>"';
		}
		echo '</select>';
		die();
	}
}

?>