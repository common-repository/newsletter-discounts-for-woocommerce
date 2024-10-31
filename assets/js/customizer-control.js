jQuery( document ).ready( function( $ ) {

    var ndfw_cc = {
        popup_reveal: '',
        $popup: $( '#ndfw-popup' ),
        $popup_success: $( '#ndfw-popup-success' ),
        init: function() {
			wp.customize.panel( 'ndfw_popup', this.init_popup );
        },
        init_popup: function( panel ) {
			panel.expanded.bind( function( isExpanding ) {
				wp.customize.previewer.send( 'show_popup_preview', { expanded: isExpanding } );
			} );
        }
    };

	ndfw_cc.init();

} );