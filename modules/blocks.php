<?php
/**
 * Load code specific to Gutenberg blocks which are not tied to a module.
 * This file is unusual, and is not an actual `module` as such.
 * It is included in ./module-extras.php
 *
 */

jetpack_register_block(
	'map',
	array(
		'render_callback' => 'jetpack_map_block_load_assets',
	)
);

jetpack_register_block(
	'mailchimp',
	array(
		'render_callback' => 'jetpack_mailchimp_block_load_assets',
	)
);

jetpack_register_block( 'vr' );

/**
 * Tiled Gallery block. Depends on the Photon module.
 *
 * @since 6.9.0
*/
if ( class_exists( 'Jetpack_Photon' ) && Jetpack::is_module_active( 'photon' ) ) {
	jetpack_register_block(
		'tiled-gallery',
		array(
			'render_callback' => 'jetpack_tiled_gallery_load_block_assets',
		)
	);

	/**
	 * Tiled gallery block registration/dependency declaration.
	 *
	 * @param array  $attr - Array containing the block attributes.
	 * @param string $content - String containing the block content.
	 *
	 * @return string
	 */
	function jetpack_tiled_gallery_load_block_assets( $attr, $content ) {
		$dependencies = array(
			'lodash',
			'wp-i18n',
			'wp-token-list',
		);
		Jetpack_Gutenberg::load_assets_as_required( 'tiled-gallery', $dependencies );

		/**
		 * Filter the output of the Tiled Galleries content.
		 *
		 * @module tiled-gallery
		 *
		 * @since 6.9.0
		 *
		 * @param string $content Tiled Gallery block content.
		 */
		return apply_filters( 'jetpack_tiled_galleries_block_content', $content );
	}
}

/**
 * Map block registration/dependency declaration.
 *
 * @param array  $attr - Array containing the map block attributes.
 * @param string $content - String containing the map block content.
 *
 * @return string
 */
function jetpack_map_block_load_assets( $attr, $content ) {
	$dependencies = array(
		'lodash',
		'wp-element',
		'wp-i18n',
	);

	$api_key = Jetpack_Options::get_option( 'mapbox_api_key' );

	Jetpack_Gutenberg::load_assets_as_required( 'map', $dependencies );
	return preg_replace( '/<div /', '<div data-api-key="'. esc_attr( $api_key ) .'" ', $content, 1 );
}

/**
 * Mailchimp block registration/dependency declaration.
 *
 * @param array $attr - Array containing the map block attributes.
 *
 * @return string
 */
function jetpack_mailchimp_block_load_assets( $attr ) {
	$values  = array();
	$blog_id = ( defined( 'IS_WPCOM' ) && IS_WPCOM ) ?
		get_current_blog_id() : Jetpack_Options::get_option( 'id' );
	Jetpack_Gutenberg::load_assets_as_required( 'mailchimp', null );
	$defaults = array(
		'title'            => esc_html__( 'Join my email list', 'jetpack' ),
		'emailPlaceholder' => esc_html__( 'Enter your email', 'jetpack' ),
		'submitLabel'      => esc_html__( 'Join My Email List', 'jetpack' ),
		'consentText'      => esc_html__( 'By clicking submit, you agree to share your email address with the site owner and MailChimp to receive marketing, updates, and other emails from the site owner. Use the unsubscribe link in those emails to opt out at any time.', 'jetpack' ),
		'processingLabel'  => esc_html__( 'Processing...', 'jetpack' ),
		'successLabel'     => esc_html__( 'Success! You\'ve been added to the list.', 'jetpack' ),
		'errorLabel'       => esc_html__( 'Oh no! Unfortunately there was an error. Please try reloading this page and adding your email once more.', 'jetpack' ),
	);
	foreach ( $defaults as $id => $default ) {
		$values[ $id ] = isset( $attr[ $id ] ) ? $attr[ $id ] : $default;
	}
	ob_start();
	?>
	<div class="wp-block-jetpack-mailchimp" data-blog-id="<?php echo( esc_attr( $blog_id ) ); ?>">
		<div class="components-placeholder">
			<h3><?php echo( esc_html( $values['title'] ) ); ?></h3>
			<form>
				<input
					type="text"
					class="components-text-control__input wp-block-jetpack-mailchimp-email"
					required
					placeholder="<?php echo( esc_attr( $values['emailPlaceholder'] ) ); ?>"
				/>
				<button type="submit" class="components-button is-button is-primary">
					<?php echo( esc_html( $values['submitLabel'] ) ); ?>
				</button>
				<figcaption>
					<?php echo( esc_html( $values['consentText'] ) ); ?>
				</figcaption>
			</form>
			<div class="wp-block-jetpack-mailchimp-notification wp-block-jetpack-mailchimp-processing">
				<?php echo( esc_html( $values['processingLabel'] ) ); ?>
			</div>
			<div class="wp-block-jetpack-mailchimp-notification wp-block-jetpack-mailchimp-success">
				<?php echo( esc_html( $values['successLabel'] ) ); ?>
			</div>
			<div class="wp-block-jetpack-mailchimp-notification wp-block-jetpack-mailchimp-error">
				<?php echo( esc_html( $values['errorLabel'] ) ); ?>
			</div>
		</div>
	</div>
	<?php
	$html = ob_get_clean();
	return $html;
}
