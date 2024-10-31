(function( $ ) {

    var ndfw_cp = {
        popup_reveal: '',
        $popup: $( '#ndfw-popup' ),
        $popup_success: $( '#ndfw-popup-success' ),
        init: function() {
			wp.customize.bind( 'preview-ready', this.init_popup );
        },
        init_popup: function() {
			$( '.panel-placeholder' ).hide();
			wp.customize.preview.bind( 'show_popup_preview', function( data ) {
				if ( true === data.expanded ) {
					$( document.body ).trigger( 'show_popup_preview' );
				} else {
					$( document.body ).trigger( 'close_popup_preview' );
				}
			});
		}
    };
    
	ndfw_cp.init();

	wp.customize( 'ndfw_popup_background_color', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-wrapper' ).css( 'background-color', value );
		});
	} );
	wp.customize( 'ndfw_popup_background_overlay', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup' ).css( 'background-color', value );
		});
	} );
	wp.customize( 'ndfw_popup_border_radius', function(value) {
		value.bind( function(to) {
			var value = to + 'px';
			$( '.ndfw-popup-wrapper' ).css( 'border-radius', value );
		});
	} );


	wp.customize( 'ndfw_popup_image_main', function(value) {
		value.bind( function(to) {
			var value = to;
			if ( value != '' ) {
				$( '#ndfw-popup .ndfw-popup-media-image' ).css( 'background-image', 'url(' + value + ')' );
			} else {
				$( '#ndfw-popup .ndfw-popup-media-image' ).css( 'background-image', '' );
			}
		});
	} );
	wp.customize( 'ndfw_popup_image_success', function(value) {
		value.bind( function(to) {
			var value = to;
			if ( value != '' ) {
				$( '#ndfw-popup-success .ndfw-popup-media-image' ).css( 'background-image', 'url(' + value + ')' );
			}
		});
	} );
	wp.customize( 'ndfw_popup_image_hide', function(value) {
		value.bind( function(to) {
			if ( to === true ) {
				$( '.ndfw-popup' ).addClass( 'ndfw-popup-no-image' );
			} else {
				$( '.ndfw-popup' ).removeClass( 'ndfw-popup-no-image' );
			}
		});
	});
	wp.customize( 'ndfw_popup_image_layout', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup' ).removeClass( 'ndfw-popup-layout-left' );
			$( '.ndfw-popup' ).removeClass( 'ndfw-popup-layout-right' );
			$( '.ndfw-popup' ).removeClass( 'ndfw-popup-layout-top' );
			$( '.ndfw-popup' ).addClass( 'ndfw-popup-layout-' + value );
		});
	});


	wp.customize( 'ndfw_popup_headline_color', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-headline h2' ).css( 'color', value );
		});
	});
	wp.customize( 'ndfw_popup_headline_family', function(value) {
		value.bind( function(to) {
			var value = to;
			ndfw_put_font( value );
			$( '.ndfw-popup-headline h2' ).css( 'font-family', value );
		});
	});
	wp.customize( 'ndfw_popup_headline_size', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-headline h2' ).css( 'font-size', value + 'px' );
		});
	});
	wp.customize( 'ndfw_popup_headline_weight', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-headline h2' ).css( 'font-weight', value );
		});
	});
	wp.customize( 'ndfw_popup_headline_align', function(value) {
		value.bind( function(to) {
			var value = to;
			console.log(value);
			$( '.ndfw-popup-headline h2' ).css( 'text-align', value );
		});
	});
	wp.customize( 'ndfw_popup_headline_italic', function(value) {
		value.bind( function(to) {
			var value = ( to === true ) ? 'italic' : 'normal';
			$( '.ndfw-popup-headline h2' ).css( 'font-style', value );
		});
	});



	wp.customize( 'ndfw_popup_body_color', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-body p' ).css( 'color', value );
		});
	});
	wp.customize( 'ndfw_popup_body_family', function(value) {
		value.bind( function(to) {
			var value = to;
			ndfw_put_font( value );
			$( '.ndfw-popup-body p' ).css( 'font-family', value );
		});
	});
	wp.customize( 'ndfw_popup_body_size', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-body p' ).css( 'font-size', value + 'px' );
		});
	});
	wp.customize( 'ndfw_popup_body_weight', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-body p' ).css( 'font-weight', value );
		});
	});
	wp.customize( 'ndfw_popup_body_align', function(value) {
		value.bind( function(to) {
			var value = to;
			console.log(value);
			$( '.ndfw-popup-body p' ).css( 'text-align', value );
		});
	});
	wp.customize( 'ndfw_popup_body_italic', function(value) {
		value.bind( function(to) {
			var value = ( to === true ) ? 'italic' : 'normal';
			$( '.ndfw-popup-body p' ).css( 'font-style', value );
		});
	});



	wp.customize( 'ndfw_popup_note_color', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-note p' ).css( 'color', value );
		});
	});
	wp.customize( 'ndfw_popup_note_family', function(value) {
		value.bind( function(to) {
			var value = to;
			ndfw_put_font( value );
			$( '.ndfw-popup-note p' ).css( 'font-family', value );
		});
	});
	wp.customize( 'ndfw_popup_note_size', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-note p' ).css( 'font-size', value + 'px' );
		});
	});
	wp.customize( 'ndfw_popup_note_weight', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-note p' ).css( 'font-weight', value );
		});
	});
	wp.customize( 'ndfw_popup_note_align', function(value) {
		value.bind( function(to) {
			var value = to;
			console.log(value);
			$( '.ndfw-popup-note p' ).css( 'text-align', value );
		});
	});
	wp.customize( 'ndfw_popup_note_italic', function(value) {
		value.bind( function(to) {
			var value = ( to === true ) ? 'italic' : 'normal';
			$( '.ndfw-popup-note p' ).css( 'font-style', value );
		});
	});




	wp.customize( 'ndfw_popup_inputs_background', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"]' ).css( 'background-color', value );
		});
	});
	wp.customize( 'ndfw_popup_inputs_color', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"]' ).css( 'color', value );
		});
	});
	wp.customize( 'ndfw_popup_inputs_placeholder', function(value) {
		value.bind( function(to) {
			var value = to;
			var css = '<style id="ndfw-preview-css">';
			css += '.ndfw-popup-form input[type="text"]::placeholder { color: ' + value + '}';
			css += '.ndfw-popup-form input[type="text"]:-moz-placeholder { color: ' + value + '}';
			css += '.ndfw-popup-form input[type="text"]::-moz-placeholder { color: ' + value + '}';
			css += '.ndfw-popup-form input[type="text"]:-ms-input-placeholder { color: ' + value + '}';
			css += '.ndfw-popup-form input[type="text"]::-webkit-input-placeholder { color: ' + value + '}';
			css += '.ndfw-popup-form input[type="email"]::placeholder { color: ' + value + '}';
			css += '.ndfw-popup-form input[type="email"]:-moz-placeholder { color: ' + value + '}';
			css += '.ndfw-popup-form input[type="email"]::-moz-placeholder { color: ' + value + '}';
			css += '.ndfw-popup-form input[type="email"]:-ms-input-placeholder { color: ' + value + '}';
			css += '.ndfw-popup-form input[type="email"]::-webkit-input-placeholder { color: ' + value + '}';
			css += '</style>';
			$( 'head' ).find( '#ndfw-preview-css' ).remove();
			$( 'head' ).append( css );
		});
	});
	wp.customize( 'ndfw_popup_inputs_family', function(value) {
		value.bind( function(to) {
			var value = to;
			ndfw_put_font( value );
			$( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"]' ).css( 'font-family', value );
		});
	});
	wp.customize( 'ndfw_popup_inputs_size', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"]' ).css( 'font-size', value + 'px' );
		});
	});
	wp.customize( 'ndfw_popup_inputs_weight', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"]' ).css( 'font-weight', value );
		});
	});
	wp.customize( 'ndfw_popup_inputs_italic', function(value) {
		value.bind( function(to) {
			var value = ( to === true ) ? 'italic' : 'normal';
			$( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"]' ).css( 'font-style', value );
		});
	});
	wp.customize( 'ndfw_popup_inputs_radius', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-form input[type="text"], .ndfw-popup-form input[type="email"]' ).css( 'border-radius', value + 'px' );
		});
	});




	wp.customize( 'ndfw_popup_buttons_background', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup button' ).css( 'background-color', value );
		});
	});
	wp.customize( 'ndfw_popup_buttons_color', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup button' ).css( 'color', value );
		});
	});
	wp.customize( 'ndfw_popup_buttons_family', function(value) {
		value.bind( function(to) {
			var value = to;
			ndfw_put_font( value );
			$( '.ndfw-popup button' ).css( 'font-family', value );
		});
	});
	wp.customize( 'ndfw_popup_buttons_size', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup button' ).css( 'font-size', value + 'px' );
		});
	});
	wp.customize( 'ndfw_popup_buttons_weight', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup button' ).css( 'font-weight', value );
		});
	});
	wp.customize( 'ndfw_popup_buttons_italic', function(value) {
		value.bind( function(to) {
			var value = ( to === true ) ? 'italic' : 'normal';
			$( '.ndfw-popup button' ).css( 'font-style', value );
		});
	});
	wp.customize( 'ndfw_popup_buttons_radius', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup button' ).css( 'border-radius', value + 'px' );
		});
	});




	wp.customize( 'ndfw_popup_links_color', function(value) {
		value.bind( function(to) {
			var value = to;
			$('.ndfw-popup a').css( 'color', value );
		});
	});
	wp.customize( 'ndfw_popup_links_family', function(value) {
		value.bind( function(to) {
			var value = to;
			ndfw_put_font( value );
			$('.ndfw-popup a').css( 'font-family', value );
		});
	});
	wp.customize( 'ndfw_popup_links_size', function(value) {
		value.bind( function(to) {
			var value = to;
			$('.ndfw-popup a').css( 'font-size', value + 'px' );
		});
	});
	wp.customize( 'ndfw_popup_links_weight', function(value) {
		value.bind( function(to) {
			var value = to;
			$('.ndfw-popup a').css( 'font-weight', value );
		});
	});
	wp.customize( 'ndfw_popup_links_italic', function(value) {
		value.bind( function(to) {
			var value = ( to === true ) ? 'italic' : 'normal';
			$('.ndfw-popup a').css( 'font-style', value );
		});
	});



	wp.customize( 'ndfw_popup_timer_background', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-timer .unit' ).css( 'background-color', value );
		});
	});
	wp.customize( 'ndfw_popup_timer_color', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-timer .unit' ).css( 'color', value );
		});
	});
	wp.customize( 'ndfw_popup_timer_family', function(value) {
		value.bind( function(to) {
			var value = to;
			ndfw_put_font( value );
			$( '.ndfw-popup-timer .unit' ).css( 'font-family', value );
		});
	});
	wp.customize( 'ndfw_popup_timer_size', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-timer .unit-value' ).css( 'font-size', value + 'px' );
			$( '.ndfw-popup-timer .unit-name' ).css( 'font-size', value * 0.375 + 'px' );
		});
	});
	wp.customize( 'ndfw_popup_timer_weight', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-timer .unit' ).css( 'font-weight', value );
		});
	});
	wp.customize( 'ndfw_popup_timer_italic', function(value) {
		value.bind( function(to) {
			var value = ( to === true ) ? 'italic' : 'normal';
			$( '.ndfw-popup-timer .unit' ).css( 'font-style', value );
		});
	});
	wp.customize( 'ndfw_popup_timer_radius', function(value) {
		value.bind( function(to) {
			var value = to;
			$( '.ndfw-popup-timer .unit' ).css( 'border-radius', value + 'px' );
		});
	});

	function ndfw_put_font( value ) {
		var font = value.replace( ' ', '+' );
		var css = 'https://fonts.googleapis.com/css?family=' + font + ':100,200,300,400,500,600,700,800,900&subset=latin%2Clatin-ext';
		$( 'head' ).append( '<link rel="stylesheet" id="ndfw-style-fonts-css" href="' + css + '" type="text/css" media="all">' );
	}

} )( jQuery );