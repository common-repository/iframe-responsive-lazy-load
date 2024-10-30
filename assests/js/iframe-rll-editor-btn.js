(function($){

    // Close modal
    var close_win = function() {
        $('#iframeRLL_model_bg, #iframeRLL_model_div').css('display','none');
        $( document.body ).removeClass( 'modal-open' );
    };

    // Open modal when media button is clicked
    $(document).on('click', '.iframeRLL_insert_btn', function(e) {
        e.preventDefault();
        $('#iframeRLL_model_bg, #iframeRLL_model_div').css('display','block');
        $( document.body ).addClass( 'modal-open' );
    });

    // Close modal on close or cancel links
    $(document).on('click', '#iframeRLL_model_close, #iframeRLL_model_cancel a', function(e) {
        e.preventDefault();
        close_win();
    });

    // Insert shortcode into TinyMCE
    $(document).on('click', '#iframeRLL_model_submit', function(e) {
        e.preventDefault();
        var shortcode;

		var iframeRLL_source_single = ($('#iframeRLL_source_single').val()!="") ? 'src="' + $('#iframeRLL_source_single').val() + '"' : '';
		var iframeRLL_width = ($('#iframeRLL_width').val()!="") ? ' width="' + $('#iframeRLL_width').val() + '"' : '';
		var iframeRLL_height = ($('#iframeRLL_height').val()!="") ? ' height="' + $('#iframeRLL_height').val() + '"' : '';
		var iframeRLL_class = ($('#iframeRLL_class').val()!="") ? ' class="' + $('#iframeRLL_class').val() + '"' : '';
		var iframeRLL_loggeded = ($('#logged-users').prop("checked") == true) ? ' login="true"' : '';

        shortcode = '[iframe_rll ' + iframeRLL_source_single + iframeRLL_width + iframeRLL_height + iframeRLL_class +  iframeRLL_loggeded + ']';
        wp.media.editor.insert(shortcode);
        close_win();
    });

    $('#iframeRLL_model_tab span').click(function() {
		var fetchId = $(this).attr('id');
		$('#iframeRLL_model_tab span').removeClass('active');
		$(this).addClass('active');
		$('#iframeRLL_model_container div').removeClass('active');
		$('.' + fetchId).addClass('active');
	});

    $('input[type="checkbox"]#enable_multiple_iframe').click(function(){
        if($(this).prop("checked") == true){
            $('.enable_multiple_iframe_div').css('display','block');
            $('.enable_single_iframe_div').css('display','none');
        }
        else if($(this).prop("checked") == false){
            $('.enable_multiple_iframe_div').css('display','none');
            $('.enable_single_iframe_div').css('display','block');
        }
    });

}(jQuery));