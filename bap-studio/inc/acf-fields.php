<?php
/**
 * ACF Pro fields, registered in code so they live in Git.
 *
 * - "Studio settings" options page: banner, home carousel, pinned albums, contact and footer.
 * - Album fields: order, cover image, intro.
 * - Package fields: price, inclusions, highlight, button.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

/** Warn admins when ACF Pro is missing, since most content depends on it. */
add_action(
	'admin_notices',
	static function () {
		if ( function_exists( 'acf_add_options_page' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'B.A.P Studio theme needs the Advanced Custom Fields PRO plugin for Studio settings, albums and pricing packages.', 'bap-studio' ) . '</p></div>';
	}
);

add_action(
	'acf/init',
	static function () {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
					'page_title' => __( 'Studio settings', 'bap-studio' ),
					'menu_title' => __( 'Studio settings', 'bap-studio' ),
					'menu_slug'  => 'bap-studio-settings',
					'capability' => 'bap_manage_studio',
					'icon_url'   => 'dashicons-camera',
					'position'   => 2,
					'redirect'   => false,
				)
			);
		}

		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$tab = static fn( string $key, string $label ) => array(
			'key'       => 'field_bap_tab_' . $key,
			'label'     => $label,
			'type'      => 'tab',
			'placement' => 'top',
		);

		/* ---------- Studio settings ---------- */
		acf_add_local_field_group(
			array(
				'key'      => 'group_bap_settings',
				'title'    => __( 'Studio settings', 'bap-studio' ),
				'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'bap-studio-settings' ) ) ),
				'fields'   => array(
					$tab( 'banner', __( 'Banner', 'bap-studio' ) ),
					array(
						'key'          => 'field_bap_banner_image',
						'name'         => 'banner_image',
						'label'        => __( 'Background image', 'bap-studio' ),
						'type'         => 'image',
						'return_format' => 'id',
						'preview_size' => 'medium',
						'instructions' => __( 'Landscape photo, at least 2000px wide.', 'bap-studio' ),
					),
					array(
						'key'           => 'field_bap_banner_eyebrow',
						'name'          => 'banner_eyebrow',
						'label'         => __( 'Small line above the title', 'bap-studio' ),
						'type'          => 'text',
						'default_value' => 'Nhiếp ảnh cưới · Sự kiện · Prom',
					),
					array(
						'key'           => 'field_bap_banner_primary',
						'name'          => 'banner_primary',
						'label'         => __( 'Primary text (title)', 'bap-studio' ),
						'type'          => 'textarea',
						'rows'          => 2,
						'new_lines'     => 'br',
						'instructions'  => __( 'Wrap words in *stars* to show them in gold italics, e.g. Lưu giữ khoảnh khắc *đẹp nhất đời bạn*.', 'bap-studio' ),
						'default_value' => "Lưu giữ khoảnh khắc\n*đẹp nhất đời bạn*",
					),
					array(
						'key'           => 'field_bap_banner_secondary',
						'name'          => 'banner_secondary',
						'label'         => __( 'Secondary text', 'bap-studio' ),
						'type'          => 'textarea',
						'rows'          => 3,
						'new_lines'     => '',
						'default_value' => 'Từng khung hình được chăm chút về ánh sáng, bố cục và cảm xúc — để mỗi lần xem lại là một lần sống lại ngày hôm ấy.',
					),
					array(
						'key'           => 'field_bap_banner_cta',
						'name'          => 'banner_cta',
						'label'         => __( 'Main button', 'bap-studio' ),
						'type'          => 'link',
						'return_format' => 'array',
					),
					array(
						'key'           => 'field_bap_banner_cta2',
						'name'          => 'banner_cta2',
						'label'         => __( 'Second button', 'bap-studio' ),
						'type'          => 'link',
						'return_format' => 'array',
					),

					$tab( 'home', __( 'Home page', 'bap-studio' ) ),
					array(
						'key'           => 'field_bap_carousel',
						'name'          => 'home_carousel',
						'label'         => __( 'Carousel photos', 'bap-studio' ),
						'type'          => 'gallery',
						'return_format' => 'id',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
						'instructions'  => __( 'Pick photos from the Media Library. Drag to reorder.', 'bap-studio' ),
					),
					array(
						'key'           => 'field_bap_pinned',
						'name'          => 'pinned_albums',
						'label'         => __( 'Pinned albums (up to 3)', 'bap-studio' ),
						'type'          => 'taxonomy',
						'taxonomy'      => 'bap_album',
						'field_type'    => 'multi_select',
						'return_format' => 'id',
						'add_term'      => 0,
						'save_terms'    => 0,
						'load_terms'    => 0,
						'allow_null'    => 1,
					),
					array(
						'key'           => 'field_bap_pinned_title',
						'name'          => 'pinned_title',
						'label'         => __( 'Pinned albums heading', 'bap-studio' ),
						'type'          => 'text',
						'default_value' => 'Tác phẩm tiêu biểu',
					),
					array(
						'key'           => 'field_bap_pricing_title',
						'name'          => 'pricing_title',
						'label'         => __( 'Pricing heading', 'bap-studio' ),
						'type'          => 'text',
						'default_value' => 'Bảng giá dịch vụ',
					),
					array(
						'key'           => 'field_bap_pricing_page',
						'name'          => 'pricing_page',
						'label'         => __( 'Pricing page', 'bap-studio' ),
						'type'          => 'page_link',
						'post_type'     => array( 'page' ),
						'allow_null'    => 1,
						'instructions'  => __( 'Linked from the header and the home pricing section.', 'bap-studio' ),
					),
					array(
						'key'           => 'field_bap_contact_title',
						'name'          => 'contact_title',
						'label'         => __( 'Contact heading', 'bap-studio' ),
						'type'          => 'text',
						'default_value' => 'Hãy để chúng tôi đồng hành cùng ngày trọng đại của bạn',
					),
					array(
						'key'           => 'field_bap_contact_text',
						'name'          => 'contact_text',
						'label'         => __( 'Contact intro', 'bap-studio' ),
						'type'          => 'textarea',
						'rows'          => 2,
						'default_value' => 'Liên hệ để nhận tư vấn ý tưởng và báo giá chi tiết — miễn phí.',
					),
					array(
						'key'          => 'field_bap_contact_form',
						'name'         => 'contact_form',
						'label'        => __( 'Contact form shortcode', 'bap-studio' ),
						'type'         => 'text',
						'instructions' => __( 'Paste the Contact Form 7 shortcode, e.g. [contact-form-7 id="123"]. Leave empty to use the form the theme creates.', 'bap-studio' ),
					),

					$tab( 'contact', __( 'Contact & footer', 'bap-studio' ) ),
					array(
						'key'   => 'field_bap_studio_name',
						'name'  => 'studio_name',
						'label' => __( 'Studio name', 'bap-studio' ),
						'type'  => 'text',
					),
					array(
						'key'   => 'field_bap_phone',
						'name'  => 'phone',
						'label' => __( 'Phone', 'bap-studio' ),
						'type'  => 'text',
					),
					array(
						'key'          => 'field_bap_email',
						'name'         => 'email',
						'label'        => __( 'Email', 'bap-studio' ),
						'type'         => 'email',
						'instructions' => __( 'Contact form messages are sent here.', 'bap-studio' ),
					),
					array(
						'key'   => 'field_bap_address',
						'name'  => 'address',
						'label' => __( 'Address', 'bap-studio' ),
						'type'  => 'textarea',
						'rows'  => 2,
					),
					array(
						'key'   => 'field_bap_map',
						'name'  => 'map_url',
						'label' => __( 'Google Maps link', 'bap-studio' ),
						'type'  => 'url',
					),
					array(
						'key'          => 'field_bap_social',
						'name'         => 'social',
						'label'        => __( 'Social links', 'bap-studio' ),
						'type'         => 'repeater',
						'layout'       => 'table',
						'button_label' => __( 'Add link', 'bap-studio' ),
						'sub_fields'   => array(
							array(
								'key'     => 'field_bap_social_name',
								'name'    => 'name',
								'label'   => __( 'Name', 'bap-studio' ),
								'type'    => 'select',
								'choices' => array(
									'facebook'  => 'Facebook',
									'instagram' => 'Instagram',
									'tiktok'    => 'TikTok',
									'youtube'   => 'YouTube',
									'zalo'      => 'Zalo',
								),
							),
							array(
								'key'   => 'field_bap_social_url',
								'name'  => 'url',
								'label' => __( 'URL', 'bap-studio' ),
								'type'  => 'url',
							),
						),
					),
					array(
						'key'           => 'field_bap_footer_tagline',
						'name'          => 'footer_tagline',
						'label'         => __( 'Footer tagline', 'bap-studio' ),
						'type'          => 'text',
						'default_value' => 'Ảnh cưới · Sự kiện · Prom',
					),
					array(
						'key'           => 'field_bap_currency',
						'name'          => 'currency',
						'label'         => __( 'Currency symbol', 'bap-studio' ),
						'type'          => 'text',
						'default_value' => '₫',
					),
				),
			)
		);

		/* ---------- Album ---------- */
		acf_add_local_field_group(
			array(
				'key'      => 'group_bap_album',
				'title'    => __( 'Album details', 'bap-studio' ),
				'location' => array( array( array( 'param' => 'taxonomy', 'operator' => '==', 'value' => 'bap_album' ) ) ),
				'fields'   => array(
					array(
						'key'          => 'field_bap_album_order',
						'name'         => 'bap_order',
						'label'        => __( 'Order', 'bap-studio' ),
						'type'         => 'number',
						'min'          => 0,
						'instructions' => __( 'Lower numbers come first in the menu.', 'bap-studio' ),
					),
					array(
						'key'           => 'field_bap_album_cover',
						'name'          => 'cover',
						'label'         => __( 'Cover image', 'bap-studio' ),
						'type'          => 'image',
						'return_format' => 'id',
						'preview_size'  => 'medium',
						'instructions'  => __( 'Used on the home page card. Defaults to the newest photo in the album.', 'bap-studio' ),
					),
					array(
						'key'   => 'field_bap_album_intro',
						'name'  => 'intro',
						'label' => __( 'Short intro', 'bap-studio' ),
						'type'  => 'textarea',
						'rows'  => 3,
					),
				),
			)
		);

		/* ---------- Package ---------- */
		acf_add_local_field_group(
			array(
				'key'      => 'group_bap_package',
				'title'    => __( 'Package details', 'bap-studio' ),
				'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'bap_package' ) ) ),
				'position' => 'acf_after_title',
				'fields'   => array(
					array(
						'key'          => 'field_bap_pkg_price',
						'name'         => 'price',
						'label'        => __( 'Price', 'bap-studio' ),
						'type'         => 'text',
						'instructions' => __( 'Numbers only (e.g. 15000000) are formatted as 15.000.000 ₫. Text such as "Liên hệ" is shown as is.', 'bap-studio' ),
					),
					array(
						'key'          => 'field_bap_pkg_note',
						'name'         => 'price_note',
						'label'        => __( 'Price note', 'bap-studio' ),
						'type'         => 'text',
						'placeholder'  => '/ buổi chụp',
					),
					array(
						'key'   => 'field_bap_pkg_summary',
						'name'  => 'summary',
						'label' => __( 'One-line summary', 'bap-studio' ),
						'type'  => 'text',
					),
					array(
						'key'          => 'field_bap_pkg_items',
						'name'         => 'items',
						'label'        => __( 'What is included', 'bap-studio' ),
						'type'         => 'repeater',
						'layout'       => 'table',
						'button_label' => __( 'Add item', 'bap-studio' ),
						'sub_fields'   => array(
							array(
								'key'   => 'field_bap_pkg_item_text',
								'name'  => 'text',
								'label' => __( 'Item', 'bap-studio' ),
								'type'  => 'text',
							),
						),
					),
					array(
						'key'          => 'field_bap_pkg_highlight',
						'name'         => 'highlight',
						'label'        => __( 'Highlight this package', 'bap-studio' ),
						'type'         => 'true_false',
						'ui'           => 1,
						'instructions' => __( 'Shown in gold with a "Phổ biến" badge.', 'bap-studio' ),
					),
					array(
						'key'           => 'field_bap_pkg_button',
						'name'          => 'button_label',
						'label'         => __( 'Button label', 'bap-studio' ),
						'type'          => 'text',
						'default_value' => 'Đặt lịch',
					),
				),
			)
		);
	}
);

/** Limit pinned albums to 3. */
add_filter(
	'acf/validate_value/key=field_bap_pinned',
	static function ( $valid, $value ) {
		if ( true === $valid && is_array( $value ) && count( $value ) > 3 ) {
			return __( 'Pick at most 3 albums.', 'bap-studio' );
		}
		return $valid;
	},
	10,
	2
);
