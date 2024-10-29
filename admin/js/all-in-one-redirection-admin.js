(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 jQuery('.vsz-redirection #delete-redirection').click(function(e) {
		var check_btn_val = jQuery('.vsz-redirection .redirection-list-form input[name="check_delete_btns[]').is(':checked');
		if(check_btn_val){
			var confirm_delete = confirm('Do you want to delete selected redirection records?');
			if (!confirm_delete) {
				e.preventDefault();
				return false;
			}
		}
		else{
			alert('Please select at least one record to delete.');
			e.preventDefault();
			return false;
		}
    });
	
	jQuery('.vsz-redirection #redirection-setting-form').submit(function(e) {
		var confirm_setting_save = confirm('If your setting is not proper, you might not be access your admin screen. Do you want to save these changes?');
		if (!confirm_setting_save) {
			e.preventDefault();
			return false;
		}
	});
	
	jQuery('.vsz-redirection #redirection-delete-all-form').submit(function(e) {
		var confirm_delete_all = confirm('Do you want to delete all the redirection records?');
		if (!confirm_delete_all) {
			e.preventDefault();
			return false;
		}
	});
	
	jQuery('.vsz-redirection #redirection-hits-reset').submit(function(e) {
		var confirm_reset_hits = confirm('Do you want to reset all the redirection hits records?');
		if (!confirm_reset_hits) {
			e.preventDefault();
			return false;
		}
	});
	
	jQuery('.vsz-redirection .additional_setting_radio').change(function(e) {		
		if(jQuery(this).val()=='DEFAULT-SETTING'){
			jQuery('.vsz-redirection #redirection-setting-form input[name="host"]').attr('disabled',true);
			jQuery('.vsz-redirection #redirection-setting-form input[name="www"]').attr('disabled',true);
		}
		else{
			jQuery('.vsz-redirection #redirection-setting-form input[name="host"]').attr('disabled',false);
			jQuery('.vsz-redirection #redirection-setting-form input[name="www"]').attr('disabled',false);
		}
    });
	jQuery('.vsz-redirection #all_check_delete').change(function(e) {
		if(jQuery(this).is(':checked')){
			jQuery('.vsz-redirection .redirection-list-form input[name="check_delete_btns[]"]').attr('checked',true);
		}
		else{
			jQuery('.vsz-redirection .redirection-list-form input[name="check_delete_btns[]"]').attr('checked',false);
		}
	});
	
	jQuery('.vsz-redirection .redirection-list-form input[name="check_delete_btns[]"]').change(function(e) {
		jQuery('.vsz-redirection #all_check_delete').attr('checked',false);		
	});
	
	jQuery('.vsz-redirection .redirection-list-form .list-setting-btn').click(function(e) {
		jQuery(this).parents('tr').next('tr').toggleClass('active-more-info');
    });
	
	jQuery('.vsz-redirection #all_check_404_delete').change(function(e) {
		if(jQuery(this).is(':checked')){
			jQuery('.vsz-redirection .list-404-pages-form input[name="check_404_page_delete_btns[]"]').attr('checked',true);
		}
		else{
			jQuery('.vsz-redirection .list-404-pages-form input[name="check_404_page_delete_btns[]"]').attr('checked',false);
		}
	});
	
	jQuery('.vsz-redirection .list-404-pages-form input[name="check_404_page_delete_btns[]"]').change(function(e) {
		jQuery('.vsz-redirection #all_check_404_delete').attr('checked',false);		
	});
	
	jQuery('.vsz-redirection #delete-404-pages').click(function(e) {
		var check_btn_val = jQuery('.vsz-redirection .list-404-pages-form input[name="check_404_page_delete_btns[]').is(':checked');
		if(check_btn_val){		
			var confirm_delete = confirm('Do you want to delete selected 404 page records?');
			if (!confirm_delete) {
				e.preventDefault();
				return false;
			}
		}
		else{
			alert('Please select at least one record to delete.');
			e.preventDefault();
			return false;
		}
    });
	
	jQuery('.vsz-redirection #delete-all-404-pages').click(function(e) {
		var confirm_delete_all = confirm('Do you want to delete all the 404 page records?');
		if (!confirm_delete_all) {
			e.preventDefault();
			return false;
		}
	});
	
	
	jQuery('.vsz-redirection .list-add-404-rule-btn').click(function(e) {
		var page_404_id = jQuery(this).attr('page_id');
		jQuery('.vsz-redirection .redirection_insert_404_page_form input[name="source_url_insert"]').val(jQuery('.vsz-redirection .list-404-pages .surlid-'+page_404_id).text());
		jQuery('.vsz-redirection .redirection_insert_404_page_form input[name="page_404_id"]').val(page_404_id);
		jQuery('.vsz-redirection #vsz-insert-form-404-page-sec').slideDown();
		jQuery('html, body').animate({
			scrollTop: jQuery('.vsz-redirection #vsz-insert-form-404-page-sec').offset().top - 20
		},1000);
	});
	

})( jQuery );
