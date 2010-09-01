jQuery(document).ready(function($) {
	$('#comment-link-suggest-o-ajax').after('<input type="submit" value="Add to list" name="comment_link_add" id="comment_link_add" /><input type="submit" value="Delete from list" name="comment_link_delete" id="comment_link_delete" /><p id="comment-link-message" style="color: #ff0000; font-weight: bold;"></p>')
	
	$('#comment-link-message').hide();
	var commentLinkMessage = function( message ) {
		$('#comment-link-message').html( message ).show();
		$('#comment-link-message').delay(2000).hide('slow');
	}
	
	var commentLinkSuggestOTronLoadAjax = function( operation, newText, currentId ) {
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: { 
				action: 'comment_link_suggest_ajax',
				text: newText,
				id: currentId,
				operation: operation
			},
			success: function(stuff){ 
				$('#comment-link-suggest-o-ajax').html(stuff); 
				if ( operation == 'DELETE' ) {
					commentLinkMessage('Item deleted: ' + newText);	
				} else if ( operation == 'ADD' ) {
					commentLinkMessage('Item added: ' + newText);	
					
				}
			}
		});
	}
	
	commentLinkSuggestOTronLoadAjax( 'LOAD', '', '' );
	
	$('#comment_link_plugin_preset').live('change', function() {
		var preset =  $("#comment_link_plugin_preset :selected").attr('title');
		var commentId =   $("#comment_link_plugin_preset :selected").val();
		var commentText = $("#comment_link_suggest_o_text").val();
		var newComment = commentText + " " + preset;
		if ( commentId != 0 ) {
			$('#comment_link_suggest_o_text').val(newComment);							 
		}
	});
	
	$('#comment_link_add').bind('click', function() {
		var newText = $("#comment_link_suggest_o_text").val();
		var currentId = $("#comment_link_plugin_preset :selected").val();
		
		if ( newText != "") {
			commentLinkSuggestOTronLoadAjax( 'ADD', newText, currentId );
		}
		return false;
	});
	$('#comment_link_delete').bind('click', function() {
		var newText = $("#comment_link_plugin_preset :selected").attr('title');
		var currentId = $("#comment_link_plugin_preset :selected").val();
		if ( currentId != 0 ) {
		
		var areYouSure = confirm( "Are you sure you want to delete the following suggestion: \n\n" + newText );
		if ( areYouSure ) {
			commentLinkSuggestOTronLoadAjax( 'DELETE', newText, currentId );
		} else {
		}
		}
		return false;
	});

	
});