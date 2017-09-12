(function($){


	function initialize_field( $el ) {

		//$el.doStuff();

	}


	if( typeof acf.add_action !== 'undefined' ) {

		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/

		acf.add_action('ready append', function( $el ){

			acf.get_fields({ type : 'google_font_selector'}, $el).each(function(){

                // ADDED FOR THEME
				initialize_field( $(this) );
                var container = $(this).parents('.acf-input:first');
                container.find('.acfgfs-font-subsets').hide();//.acfgfs-font-variants,
                var load_preview = function(new_font){
                    var font = new_font.replace( ' ', '+' );
                    var prevcontainer = container.find('.acfgfs-preview');
                    prevcontainer.html('<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' + font + '"><div style="font-family:' + new_font + '">' + prevcontainer.text() + '</div>')
                }
                load_preview(container.find('.acfgfs-font-family select').val());
                // / ADDED FOR THEME

                var add_action_to_field = function(container, new_font){
                    // REMOVED THINGS FOR THEME
                    var data = container.find('.acfgfs-font-data').val();

                    // ADDED FOR THEME
                    var variants = container.find( '.acfgfs-font-variants .acfgfs-list' );
                    var subsets = container.find( '.acfgfs-font-subsets .acfgfs-list' );
                    load_preview(new_font);
                    // / ADDED FOR THEME

                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'post',
                        dataType: 'json',
                        beforeSend: function() {
                            // COMMENTED FOR THEME
                            //container.find( '.acfgfs-loader').show();
                        },
                        data: {
                            action: 'acfgfs_get_font_details',
                            font_family: new_font,
                            data: data
                        },
                        success: function( response ) {
                            container.find( '.acfgfs-loader').hide();
                            variants.html( response.variants );
                            subsets.html( response.subsets );

                            // MODIFIED FOR THEME
							//preview_text = jQuery('#acfgfs-preview div').html();
							//font = new_font.replace( ' ', '+' );
                            //var prevcontainer = container.find('.acfgfs-preview');
                            //prevcontainer.text();
							//prevcontainer.html('<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' + font + '"><div style="font-family:' + new_font + '">' + prevcontainer.text() + '</div>')
							//jQuery('#acfgfs-preview div').html(preview_text)
                            // / MODIFIED FOR THEME

                        }
                    });
                };



                (function(container){
                    container.on('change', '.acfgfs-font-family select', function(){
                        add_action_to_field(container, $(this).val());
                    });
                })(container);
                

			});

		});


	} else {


		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM.
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/

		$(document).live('acf/setup_fields', function(e, postbox){

			$(postbox).find('.field[data-field_type="google_font_selector"]').each(function(){

				initialize_field( $(this) );
                jQuery(document).on( 'change', '.acfgfs-font-family select', function(){
                    var new_font = $(this).val()
                    var container = $(this).parents('.acfgfs-font-selector:first');
                    var variants = container.find( '.acfgfs-font-variants .acfgfs-list' );
                    var subsets = container.find( '.acfgfs-font-subsets .acfgfs-list' );
                    var data = container.find( '.acfgfs-font-data').val();
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'post',
                        dataType: 'json',
                        beforeSend: function() {
                            container.find( '.acfgfs-loader').show();
                        },
                        data: {
                            action: 'acfgfs_get_font_details',
                            font_family: new_font,
                            data: data
                        },
                        success: function( response ) {
                            container.find( '.acfgfs-loader').hide();
                            variants.html( response.variants );
                            subsets.html( response.subsets );

							font = new_font.replace( ' ', '+' );
							container.find('.acfgfs-preview').html('<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' + font + '"><div style="font-family:' + font + '">This is new a preview of the selected font</div>')

                        }
                    });
                });

			});

		});


	}


})(jQuery);
