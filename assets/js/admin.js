jQuery( function( $ ) {

    var ndfw_admin = {
        popup_reveal: '',
        $options: $('#ndfw_options'),
        init: function() {

            this.$options.find('#ndfw_newsletter_service').on( 'change', this.change_newsletter_service );
            this.$options.find('#ndfw_drip_connect').on( 'click', this.connect_newsletter );
            this.$options.find('#ndfw_klaviyo_connect').on( 'click', this.connect_newsletter );
            this.$options.find('#ndfw_mailchimp_connect').on( 'click', this.connect_newsletter );

            $( document.body ).bind( 'init_select2', this.init_select2 );
            jQuery(window).load( function() {
                $( document.body ).trigger( 'init_select2' );
            });

        },
        init_select2: function() {
            $('.ndfw_select2').each( function() {
                var placeholder = $( this ).attr( 'placeholder' );
                $( this ).select2( { placeholder: placeholder } );
            } );
        },
        connect_newsletter: function(event) {
            event.preventDefault();

            var service = '';
            var api = '';

            if ( $(this).hasClass( 'ndfw_drip_connect' ) ) {
                service = 'drip';
                api = ndfw_admin.$options.find( '#ndfw_drip_api' ).val();
            } else if ( $(this).hasClass( 'ndfw_klaviyo_connect' ) ) {
                service = 'klaviyo';
                api = ndfw_admin.$options.find( '#ndfw_klaviyo_api' ).val();
            } else if ( $(this).hasClass( 'ndfw_mailchimp_connect' ) ) {
                service = 'mailchimp';
                api = ndfw_admin.$options.find( '#ndfw_mailchimp_api' ).val();
            }

            if ( service == '' ) {
                return false;
            }

            var newsletter_data  = {
                action: 'ndfw_newsletter_lists',
                service: service,
                api: api,
                security: ndfw_newsletter_lists.newsletter_lists_nonce
            }

            var $form = ndfw_admin.$options.find( '#ndfw_newsletter_form' );

            $.post( ndfw_newsletter_lists.ajax_url, newsletter_data, function( response ) {

                ndfw_admin.$options.find( '#ndfw_' + service + '_list option[value!=""]' ).remove();

                var result = JSON.parse(response);

                if ( result['status'] == 'success' ) {
                    var lists = result[ 'data' ];
                    for (var i = 0; i < lists.length; i++) {
                        var list = lists[i];
                        ndfw_admin.$options.find( '#ndfw_' + service + '_list' ).parents( '.ndfw-options-row' ).show();
                        ndfw_admin.$options.find( '#ndfw_' + service + '_list' ).append( '<option value="' + list[ 'id' ] + '">' + list[ 'name' ] + '</option>' );
                    };
                } else {
                    alert( result[ 'error_message' ] );
                    ndfw_admin.$options.find( '#ndfw_' + service + '_list' ).parents( '.ndfw-options-row' ).hide();
                }

            });

            return false;

        },
        change_newsletter_service: function(event) {

            var service = $(this).val();

            if ( service == 'drip' ) {

                ndfw_admin.$options.find( '#ndfw_drip_api' ).parents( '.ndfw-options-row' ).show();                

                if ( ndfw_admin.$options.find( '#ndfw_drip_api' ).val() == '' ) {
                    ndfw_admin.$options.find( '#ndfw_drip_list' ).parents( '.ndfw-options-row' ).hide();
                } else {
                    ndfw_admin.$options.find( '#ndfw_drip_list' ).parents( '.ndfw-options-row' ).show();
                }

                ndfw_admin.$options.find( '#ndfw_klaviyo_api' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_klaviyo_list' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_mailchimp_api' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_mailchimp_list' ).parents( '.ndfw-options-row' ).hide();

            } else if ( service == 'klaviyo' ) {

                ndfw_admin.$options.find( '#ndfw_drip_api' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_drip_list' ).parents( '.ndfw-options-row' ).hide();  
                ndfw_admin.$options.find( '#ndfw_mailchimp_api' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_mailchimp_list' ).parents( '.ndfw-options-row' ).hide();

                ndfw_admin.$options.find( '#ndfw_klaviyo_api' ).parents( '.ndfw-options-row' ).show();

                if ( ndfw_admin.$options.find( '#ndfw_klaviyo_api' ).val() == '' ) {
                    ndfw_admin.$options.find( '#ndfw_klaviyo_list' ).parents( '.ndfw-options-row' ).hide();
                } else {
                    ndfw_admin.$options.find( '#ndfw_klaviyo_list' ).parents( '.ndfw-options-row' ).show();
                }

            } else if ( service == 'mailchimp' ) {

                ndfw_admin.$options.find( '#ndfw_drip_api' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_drip_list' ).parents( '.ndfw-options-row' ).hide();  
                ndfw_admin.$options.find( '#ndfw_klaviyo_api' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_klaviyo_list' ).parents( '.ndfw-options-row' ).hide();

                ndfw_admin.$options.find( '#ndfw_mailchimp_api' ).parents( '.ndfw-options-row' ).show();

                if ( ndfw_admin.$options.find( '#ndfw_mailchimp_api' ).val() == '' ) {
                    ndfw_admin.$options.find( '#ndfw_mailchimp_list' ).parents( '.ndfw-options-row' ).hide();
                } else {
                    ndfw_admin.$options.find( '#ndfw_mailchimp_list' ).parents( '.ndfw-options-row' ).show();
                }

            } else {

                ndfw_admin.$options.find( '#ndfw_drip_api' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_klaviyo_api' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_klaviyo_list' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_mailchimp_api' ).parents( '.ndfw-options-row' ).hide();
                ndfw_admin.$options.find( '#ndfw_mailchimp_list' ).parents( '.ndfw-options-row' ).hide();

            }
        }
    }

    ndfw_admin.init();

});