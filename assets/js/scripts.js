jQuery( function( $ ) {

    var ndfw = {
        popup_reveal: '',
        $popup: $( '#ndfw-popup' ),
        $popup_success: $( '#ndfw-popup-success' ),
        init: function() {

            this.popup_reveal = ndfw_settings.popup_reveal;

            $( document.body ).bind( 'init_popup', this.init_popup );

            $( document.body ).bind( 'show_popup', this.show_popup );
            $( document.body ).bind( 'show_popup_preview', this.show_popup_preview );

            $( document.body ).bind( 'close_popup', this.close_popup );
            $( document.body ).bind( 'close_popup_preview', this.close_popup_preview );

            $( document.body ).bind( 'success_popup', this.show_popup_success );

            // POPUP CLOSE BUTTON
            this.$popup.find( '#ndfw-popup-form-close' ).on( 'click', this.close_popup);

            // POPUP FORM SUBMIT
            this.$popup.find( '#ndfw-popup-form' ).on( 'submit', this.new_subscriber);

            // POPUP FORM SUBMIT BUTTON
            this.$popup.find( '#ndfw-popup-form-submit' ).on( 'click', this.new_subscriber);

            // POPUP SUCCESS SHOP BUTTON
            this.$popup_success.find( '#ndfw-popup-shop' ).on( 'click', this.shop);

            jQuery(window).load( function() {
                $( document.body ).trigger( 'init_popup' );
            });

        },
        update_impressions: function() {

            var data  = {
                action: 'ndfw_update_popup_impressions',
                security: ndfw_popup_form.update_popup_impressions_nonce
            }

            $.post( ndfw_popup_form.ajax_url, data );

            return false;

        },
        new_subscriber: function(event) {
            event.preventDefault();

            var $wrapper = ndfw.$popup.find( '.ndfw-popup-wrapper' );
            var $form = ndfw.$popup.find( '#ndfw-popup-form' );

            // VERIFY DATA
            var discount_data = {
                action: 'ndfw_check_subscriber',
                form_data: $form.serialize(),
                security: ndfw_popup_form.check_subscriber_nonce
            }

            $wrapper.block( { message: null, overlayCSS: { background: '#fff', opacity: 0.6 } } );

            $.post( ndfw_popup_form.ajax_url, discount_data, function( response ) {

                var result = JSON.parse(response);

                $form.find('.ndfw-popup-message').remove();

                $wrapper.unblock();

                if ( result[ 'status' ] != 'success' ) {

                    var msg = '<div class="ndfw-popup-message"><div class="woocommerce-error">' + result['error_message'] + '</div></div>';
                    $form.find( '.ndfw-popup-form' ).before( msg );
                    return false;

                } else {

                    var a = ndfw.add_subscriber();

                }

            } );

            return false;

        },
        add_subscriber: function() {

            var $wrapper = ndfw.$popup.find( '.ndfw-popup-wrapper' );
            var $form = ndfw.$popup.find( '#ndfw-popup-form' );

            var $consent = ndfw.$popup.find( '.ndfw-popup-consent' );

            if ( $consent.length ) {

                var consent_info = ndfw.$popup.find( '#ndfw-popup-consent-info' ).prop( 'checked' );
                var consent_marketing = ndfw.$popup.find( '#ndfw-popup-consent-marketing' ).prop( 'checked' );

                if ( !consent_info && !consent_marketing ) {

                    if ( !$consent.hasClass( 'ndfw-popup-consent-visible' ) ) {

                        $form.find( '.ndfw-popup-headline' ).hide();
                        $form.find( '.ndfw-popup-body' ).hide();
                        $form.find( '.ndfw-popup-form' ).hide();
                        $form.find( '.ndfw-popup-note' ).hide();

                        ndfw.$popup.find( '#ndfw-popup-consent-info' ).prop( 'required', true );
                        ndfw.$popup.find( '#ndfw-popup-consent-marketing' ).prop( 'required', true );

                        var popup = anime( {
                            targets: '#ndfw-popup .ndfw-popup-consent',
                                height: { value: '100%', duration: 400, easing: 'easeOutQuad' },
                                opacity: { value: [0, 1], duration: 400, easing: 'easeOutQuad' },
                            begin: function(anim) {
                                $consent.addClass( 'ndfw-popup-consent-visible' );
                            },
                        } );

                        return false;

                    }

                }

            }

            var discount_data  = {
                action: 'ndfw_add_subscriber',
                form_data: $form.serialize(),
                security: ndfw_popup_form.add_subscriber_nonce
            }

            $wrapper.block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            $.post( ndfw_popup_form.ajax_url, discount_data, function( response ) {

                var result = JSON.parse(response);

                if ( result['status'] == 'success' ) {

                    $( document.body ).trigger( 'success_popup' );

                } else {

                    $form.find( '.ndfw-popup-message' ).remove();
                    var msg = '<div class="ndfw-popup-message"><div class="woocommerce-error">' + result['error_message'] + '</div></div>';
                    $form.find( '.ndfw-popup-form' ).before( msg );

                }

                $wrapper.unblock();

            } );

            return false;

        },
        close_popup: function(event) {

            event.preventDefault();

            ndfw.$popup.addClass( 'ndfw-popup-hidden' );

            ndfw.$popup.remove();

            document.cookie = encodeURIComponent( '_ndfw_popup_dismissed' ) + "=" + encodeURIComponent( 'true' ) + "" + "; path=/";
            
            return false;

        },
        close_popup_preview: function(event) {

            event.preventDefault();

            var popup = anime( {
                targets: '#ndfw-popup',
                    scale: { value: [1, 1.1], duration: 0, easing: 'easeOutQuad' },
                    opacity: { value: [1, 0], duration: 100, easing: 'easeOutQuad' },
                begin: function(anim) {
                    ndfw.$popup.css( 'z-index', -1 );
                    ndfw.$popup.removeClass( 'ndfw-popup-visible' );
                }
            } );

            return false;

        },
        addEvent: function (obj, event, callback) {

            if ( obj.addEventListener ) {
                obj.addEventListener(event, callback, false);
            } else if ( obj.attachEvent ) {
                obj.attachEvent("on" + event, callback);
            }

        },
        init_popup: function() {

            if ( !ndfw.$popup.length ) {
                return false;
            }

            if ( ndfw.$popup.hasClass( 'ndfw-popup-preview' ) ) {
                return false;
            }

            var Scrollbar = window.Scrollbar;
            Scrollbar.init( document.querySelector( '.ndfw-popup-content' ) );

            ndfw.init_timer();

            if ( ndfw.popup_reveal == '' ) {

                $( document.body ).trigger( 'show_popup' );

            } else if ( ndfw.popup_reveal == 'after_5' ) {

                setTimeout( function(){
                    $( document.body ).trigger( 'show_popup' );
                }, 5000 );

            } else if ( ndfw.popup_reveal == 'after_20' ) {

                setTimeout( function(){
                    $( document.body ).trigger( 'show_popup' );
                }, 20000 );

            } else if ( ndfw.popup_reveal == 'on_exit' ) {

                ndfw.addEvent(document, "mouseout", function(e) {

                    e = e ? e : window.event;

                    if ( e.target.tagName.toLowerCase() == "input" ) {
                        return;
                    }

                    var vpWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

                    if ( e.clientX >= (vpWidth - 50) ) {
                        return;
                    }

                    if ( e.clientY >= 50 ) {
                        return;
                    }

                    var from = e.relatedTarget || e.toElement;
                    if ( !from ) {
                        $( document.body ).trigger( 'show_popup' );
                    }

                }.bind(this));

            } else if ( ndfw.popup_reveal == 'scroll_mid' ) {

                jQuery(window).scroll( function() {
                    var position = $(window).scrollTop();
                    var height = $(document).innerHeight() - $(window).height();
                    var percentage = position / height;
                    if ( percentage >= 0.5 ) {
                        $( document.body ).trigger( 'show_popup' );
                    }
                });

            } else if ( ndfw.popup_reveal == 'scroll_bottom' ) {

                jQuery(window).scroll( function() {
                    var position = $(window).scrollTop();
                    var height = $(document).innerHeight() - $(window).height();
                    var percentage = position / height;
                    if ( percentage > 0.95 ) {
                        $( document.body ).trigger( 'show_popup' );
                    }
                });

            }

        },
        show_popup: function() {

            if ( ndfw.$popup.hasClass( 'ndfw-popup-visible' ) || ndfw.$popup.hasClass( 'ndfw-popup-hidden' ) ) {
                return false;
            }

            var popup = anime({
                targets: '#ndfw-popup',
                    scale: { value: [1.1, 1], duration: 400, easing: 'easeOutQuad' },
                    opacity: { value: [0, 1], duration: 400, easing: 'easeOutQuad' },
                begin: function(anim) {
                    ndfw.$popup.css( 'z-index', 9999 );
                    ndfw.$popup.addClass( 'ndfw-popup-visible' );
                },
                complete: function(anim) {

                    var s = ndfw.update_impressions();

                    var popup_height_1 = ndfw.$popup.find('.ndfw-popup-content').height();
                    var popup_height_2 = ndfw.$popup_success.find('.ndfw-popup-content').height();
                    if ( popup_height_1 > popup_height_2 ) {
                        var margin = ( popup_height_1 - popup_height_2 ) / 2;
                        ndfw.$popup_success.find('.ndfw-popup-content-wrapper').css( 'margin-top', margin + 'px' ).css( 'margin-bottom', margin + 'px' );
                    }

                }
            });

        },
        show_popup_preview: function() {

            if ( ndfw.$popup.hasClass( 'ndfw-popup-visible' ) || ndfw.$popup.hasClass( 'ndfw-popup-hidden' ) ) {
                return false;
            }

            var popup = anime( {
                targets: '#ndfw-popup',
                    scale: { value: [1.1, 1], duration: 400, easing: 'easeOutQuad' },
                    opacity: { value: [0, 1], duration: 400, easing: 'easeOutQuad' },
                begin: function(anim) {
                    ndfw.$popup.css( 'z-index', 9999 );
                    ndfw.$popup.addClass( 'ndfw-popup-visible' );
                },
                complete: function(anim) {

                    var popup_height_1 = ndfw.$popup.find( '.ndfw-popup-content' ).height();
                    var popup_height_2 = ndfw.$popup_success.find( '.ndfw-popup-content' ).height();
                    if ( popup_height_1 > popup_height_2 ) {
                        var margin = ( popup_height_1 - popup_height_2 ) / 2;
                        ndfw.$popup_success.find( '.ndfw-popup-content-wrapper' ).css( 'margin-top', margin + 'px' ).css( 'margin-bottom', margin + 'px' );
                    }

                }
            } );

        },
        show_popup_success: function() {

            event.preventDefault();

            ndfw.$popup.remove();

            document.cookie = encodeURIComponent( '_ndfw_discount_activated' ) + "=" + encodeURIComponent( 'true' ) + "" + "; path=/";
            
            var popup = anime({
                targets: '#ndfw-popup-success',
                    scale: { value: [1.1, 1], duration: 400, easing: 'easeOutQuad' },
                    opacity: { value: [0, 1], duration: 400, easing: 'easeOutQuad' },
                begin: function(anim) {
                    ndfw.$popup_success.css( 'z-index', 9999 );
                }
            });

            return false;

        },
        shop: function(){
            ndfw.$popup_success.remove();
        },
        init_timer: function() {

            var timer = this.$popup.find( '#ndfw-popup-timer p' );
            var timer_start = timer.attr( 'data-start' );
            var timer_value = timer.attr( 'data-value' );

            timer_value--;
            timer.attr( 'data-value', timer_value );

            document.cookie = encodeURIComponent( '_ndfw_timer_start' ) + "=" + encodeURIComponent( timer_start ) + "" + "; path=/";
            document.cookie = encodeURIComponent( '_ndfw_timer_value' ) + "=" + encodeURIComponent( timer_value ) + "" + "; path=/";

            var fm = [
                Math.floor(timer_value / 60 / 60 / 24),
                Math.floor(timer_value / 60 / 60) % 24,
                Math.floor(timer_value / 60) % 60,
                timer_value % 60
            ];

            var days    = ( ( fm[ '0' ] < 10 ) ? '0' : '' ) + fm[ '0' ];
            var hours   = ( ( fm[ '1' ] < 10 ) ? '0' : '' ) + fm[ '1' ];
            var minutes = ( ( fm[ '2' ] < 10 ) ? '0' : '' ) + fm[ '2' ];
            var seconds = ( ( fm[ '3' ] < 10 ) ? '0' : '' ) + fm[ '3' ];

            timer.find( '[data-index="0"]' ).text( days );
            timer.find( '[data-index="1"]' ).text( hours );
            timer.find( '[data-index="2"]' ).text( minutes );
            timer.find( '[data-index="3"]' ).text( seconds );

            if ( timer_value === 0 ) {

                if ( ndfw_settings.timer_action === 'hide' ) {

                    $( document.body ).trigger( 'close_popup' );

                } else if ( ndfw_settings.timer_action === 'reset' ) {

                    ndfw.$popup.addClass( 'ndfw-popup-hidden' );
                    ndfw.$popup.remove();

                } 

                return false;
            }
            
            setTimeout( function() {
                ndfw.init_timer();
            }, 1000);
        },

    }

    ndfw.init();

});