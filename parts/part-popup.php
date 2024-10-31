<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$popup_classes 		= ndfw_frontend()->popup_classes();

$form_headline 		= ndfw_settings()->get_setting( 'popup_form_headline', 		'content' );
$form_body 			= ndfw_settings()->get_setting( 'popup_form_body', 			'content' );
$form_note 			= ndfw_settings()->get_setting( 'popup_form_note', 			'content' );
$form_accept 		= ndfw_settings()->get_setting( 'popup_form_accept', 		'content' );
$form_decline 		= ndfw_settings()->get_setting( 'popup_form_decline', 		'content' );

$form_fname 		= ndfw_settings()->get_setting( 'popup_form_fname', 		'content' );
$form_lname 		= ndfw_settings()->get_setting( 'popup_form_lname', 		'content' );
$form_email 		= ndfw_settings()->get_setting( 'popup_form_email', 		'content' );

$form_name 			= ndfw_settings()->get_setting( 'popup_form_hide_name', 	'display' );

$success_headline 	= ndfw_settings()->get_setting( 'popup_success_headline', 	'content' );
$success_body 		= ndfw_settings()->get_setting( 'popup_success_body', 		'content' );
$success_cta_button = ndfw_settings()->get_setting( 'popup_success_cta_button', 'content' ); 

$consent_enabled 	= ndfw_settings()->get_setting( 'popup_consent_enabled', 'privacy' ); 
$consent_info 		= ndfw_settings()->get_setting( 'popup_consent_info', 'privacy' ); 
$consent_marketing 	= ndfw_settings()->get_setting( 'popup_consent_marketing', 'privacy' );  
$consent_note 		= ndfw_settings()->get_setting( 'popup_consent_note', 'privacy' );
$consent_text 		= ndfw_settings()->get_setting( 'popup_consent_text', 'privacy' ); 
?>
<div class="ndfw-popup <?php echo esc_html( $popup_classes ); ?>" id="ndfw-popup">
	<div class="ndfw-popup-wrapper ndfw-popup-scrollbar">
		<div class="ndfw-popup-media">
			<div class="ndfw-popup-media-image"></div>
		</div>
		<form class="ndfw-popup-content" id="ndfw-popup-form">
			<div class="ndfw-popup-content-wrapper">
				<?php if ( !empty( $form_headline ) ) : ?>
					<div class="ndfw-popup-headline">
						<h2><?php echo esc_html( $form_headline ); ?></h2>
					</div>
				<?php endif; ?>
				<?php if ( !empty( $form_body ) ) : ?>
					<div class="ndfw-popup-body">
						<p><?php echo esc_html( $form_body ); ?></p>
					</div>
				<?php endif; ?>
				<div class="ndfw-popup-form">
					<?php if ( $form_name == 'on' ) : ?>
					<div class="ndfw-popup-input ndfw-popup-input-half">
						<input type="text" name="ndfw_fname" id="ndfw-popup-form-fname" placeholder="<?php echo esc_html( $form_fname ); ?>" required>
					</div>
					<div class="ndfw-popup-input ndfw-popup-input-half">
						<input type="text" name="ndfw_lname" id="ndfw-popup-form-lname" placeholder="<?php echo esc_html( $form_lname ); ?>" required>
					</div>
					<?php endif; ?>
					<div class="ndfw-popup-input">
						<input type="email" name="ndfw_email" id="ndfw-popup-form-email" placeholder="<?php echo esc_html( $form_email ); ?>" required>
					</div>
				</div>

				<?php if ( ! empty( $form_note ) ) : ?>
				<div class="ndfw-popup-note">
					<p><?php echo esc_html( $form_note ); ?></p>
				</div>
				<?php endif; ?>

				<?php if ( $consent_enabled == 'on' ) : ?>
				<div class="ndfw-popup-consent">
					<div class="ndfw-popup-consent-wrapper">
						<div class="ndfw-popup-text">
							<p><?php echo esc_html( $consent_text ); ?></p>
						</div>

						<div class="ndfw-popup-input">
							<label for="ndfw-popup-consent-info">
							<input type="checkbox" name="ndfw_popup_consent_info" id="ndfw-popup-consent-info">
							<?php echo esc_html( $consent_info ); ?></label>
						</div>

						<div class="ndfw-popup-input">
							<label for="ndfw-popup-consent-marketing">
							<input type="checkbox" name="ndfw_popup_consent_marketing" id="ndfw-popup-consent-marketing">
							<?php echo esc_html( $consent_marketing ); ?></label>
						</div>

						<div class="ndfw-popup-text">
							<p><?php echo esc_html( $consent_note ); ?></p>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<div class="ndfw-popup-actions">
					<div class="ndfw-popup-action">
						<button type="submit"><?php echo esc_html( $form_accept ); ?></button>
					</div>
					<div class="ndfw-popup-action">
						<a href="#" id="ndfw-popup-form-close"><?php echo esc_html( $form_decline ); ?></a>
					</div>
				</div>

			</div>
		</form>
	</div>
</div>

<div class="ndfw-popup ndfw-popup-success <?php echo esc_html( $popup_classes ); ?>" id="ndfw-popup-success">
	<div class="ndfw-popup-wrapper">
		<div class="ndfw-popup-media">
			<div class="ndfw-popup-media-image"></div>
		</div>
		<div class="ndfw-popup-content">
			<div class="ndfw-popup-content-wrapper">
				<div class="ndfw-popup-headline">
					<h2><?php echo esc_html( $success_headline ); ?></h2>
				</div>
				<div class="ndfw-popup-body">
					<p><?php echo esc_html( $success_body ); ?></p>
				</div>
				<div class="ndfw-popup-actions">
					<div class="ndfw-popup-action">
						<button id="ndfw-popup-shop"><?php echo esc_html( $success_cta_button ); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>