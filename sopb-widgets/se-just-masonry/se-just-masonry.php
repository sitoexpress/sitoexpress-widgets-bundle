<?php
/*
Widget Name: SE Just Masonry Layout
Description: Just a legit masonry layout for images. Images can link to stuff or be lightboxed..
Author: Sito.Express
Author URI: https://sito.express
*/

class SE_Just_Masonry_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'se-just-masonry',
			__('SE Just Masonry', 'se-sopb-widgets'),
			array(
				'description' => __('Just a legit masonry layout for images. Images can link to stuff or be lightboxed.', 'se-sopb-widgets')
			),
			array(),
			false,
			plugin_dir_path(__FILE__)
		);

	}

	function initialize() {
		add_action( 'siteorigin_widgets_enqueue_frontend_scripts_' . $this->id_base, array( $this, 'enqueue_widget_scripts' ) );
	}

	function enqueue_widget_scripts( $instance ) {

		wp_enqueue_script('masonry');
		wp_enqueue_script('imagesloaded');
		wp_enqueue_script('just-masonry-init', SPB_URL.'sopb-widgets/se-just-masonry/js/se-just-masonry.js', array('masonry', 'imagesloaded'), SPB_VER, true);
		do_action('se_just_masonry_frontend_enqueue');

	}

	function get_widget_form(){
		return array(
			'widget_title' => array(
				'type' => 'text',
				'label' => __('Title', 'se-sopb-widgets'),
			),
			'multiple_items' => array(
        'type' => 'multiple_media',
        'label' => __( 'Add multiple image at once', 'so-widgets-test' ),
        'repeater' => array(
            'field' => 'items', // The ID of the repeater form field.
            'setting' => 'image', // The media form field inside of the repeater that'll be set when the user adds new images.
        ),
    	),
			'reverse_order' => array(
				'type' => 'checkbox',
				'default' => false,
				'label' => __('Reverse order? (useful when adding multiple images, as they\'re added at the bottom.)', 'se-sopb-widgets'),
			),
			'items' => array(
				'type' => 'repeater',
				'label' => __( 'Images', 'se-sopb-widgets' ),
				'item_label' => array(
					'selectorArray' => array(
						array(
							'selector' => "[id*='title']",
							'valueMethod' => 'val',
						),
						array(
							'selector' => '.media-field-wrapper .current .title',
							'valueMethod' => 'html'
						),
					),
				),
				'fields' => array(
					'image' => array(
						'type' => 'media',
						'label' => __( 'Image', 'se-sopb-widgets'),
						'fallback' => true,
					),
					'title' => array(
						'type' => 'text',
						'label' => __('Title', 'se-sopb-widgets'),
					),
					'url' => array(
						'type' => 'link',
						'label' => __('Destination URL', 'se-sopb-widgets'),
					),
					'new_window' => array(
						'type' => 'checkbox',
						'default' => false,
						'label' => __('Open in a new window', 'se-sopb-widgets'),
					),
				)
			),

			'title' => array(
				'type' => 'section',
				'label' => __( 'Image Title', 'se-sopb-widgets' ),
				'hide' => true,
				'fields' => array(
					'display' => array(
						'type' => 'checkbox',
						'label' => __( 'Display Image Title', 'se-sopb-widgets' ),
						'state_emitter' => array(
							'callback' => 'conditional',
							'args' => array(
								'title_display[show]: val',
								'title_display[hide]: ! val',
							),
						),
					),

					'position' => array(
						'type' => 'select',
						'label' => __( 'Title Position', 'se-sopb-widgets' ),
						'default' => 'below',
						'options' => array(
							'above' => __( 'Above Image', 'se-sopb-widgets' ),
							'below' => __( 'Below Image', 'se-sopb-widgets' ),
						),
						'state_handler' => array(
							'title_display[show]' => array( 'show' ),
							'title_display[hide]' => array( 'hide' ),
						)
					),

					'alignment' => array(
						'type' => 'select',
						'label' => __( 'Title Alignment', 'se-sopb-widgets' ),
						'default' => 'center',
						'options' => array(
							'left' => __( 'Left', 'se-sopb-widgets' ),
							'center' => __( 'Center', 'se-sopb-widgets' ),
							'right' => __( 'Right', 'se-sopb-widgets' ),
						),
						'state_handler' => array(
							'title_display[show]' => array( 'show' ),
							'title_display[hide]' => array( 'hide' ),
						),
					),

					'font' => array(
						'type' => 'font',
						'label' => __( 'Title Font', 'se-sopb-widgets' ),
						'state_handler' => array(
							'title_display[show]' => array( 'show' ),
							'title_display[hide]' => array( 'hide' ),
						),
					),

					'font_size' => array(
						'type' => 'measurement',
						'label' => __( 'Title Font Size', 'se-sopb-widgets' ),
						'default' => '0.9rem',
						'state_handler' => array(
							'title_display[show]' => array( 'show' ),
							'title_display[hide]' => array( 'hide' ),
						),
					),

					'color' => array(
						'type' => 'color',
						'label' => __( 'Title Color', 'se-sopb-widgets' ),
						'state_handler' => array(
							'title_display[show]' => array( 'show' ),
							'title_display[hide]' => array( 'hide' ),
						),
					),

					'padding' => array(
						'type' => 'color',
						'label' => __( 'Title Padding', 'se-sopb-widgets' ),
						'type' => 'multi-measurement',
						'autofill' => true,
						'default' => '5px 0px 10px 0px',
						'measurements' => array(
							'top' => array(
							'label' => __( 'Top', 'se-sopb-widgets' ),
							),
							'right' => array(
								'label' => __( 'Right', 'se-sopb-widgets' ),
							),
							'bottom' => array(
								'label' => __( 'Bottom', 'se-sopb-widgets' ),
							),
							'left' => array(
								'label' => __( 'Left', 'se-sopb-widgets' ),
							),
						),
						'state_handler' => array(
							'title_display[show]' => array( 'show' ),
							'title_display[hide]' => array( 'hide' ),
						),
					),
				),
			),

			'layout' => array(
				'type' => 'section',
				'label' => __( 'Layout', 'se-sopb-widgets' ),
				'fields' => array(
					'lightbox' => array(
						'type' => 'checkbox',
						'label' => __( 'Lightbox', 'se-sopb-widgets' ),
						'description' => __( 'Check if image should open in a lightbox. If enabled, image cannot link to stuff.', 'se-sopb-widgets' ),
						'default' => 'false'
					),

					'desktop' => array(
						'type' => 'section',
						'label' => __( 'Desktop', 'se-sopb-widgets' ),
						'fields' => array(
							'columns' => array(
								'type' => 'slider',
								'label' => __( 'Number of columns', 'se-sopb-widgets' ),
								'min' => 1,
								'max' => 6,
								'default' => 4,
							),
							'gutter' => array(
								'type' => 'number',
								'label' => __( 'Gutter', 'se-sopb-widgets'),
								'description' => __( 'Space between masonry items.', 'se-sopb-widgets' ),
							),
						),
					),

					'tablet' => array(
						'type' => 'section',
						'label' => __( 'Tablet', 'se-sopb-widgets' ),
						'hide' => true,
						'fields' => array(
							'break_point' => array(
								'type' => 'number',
								'lanel' => __( 'Breakpoint', 'se-sopb-widgets' ),
								'description' => __( 'Device width, in pixels, at which to collapse into a tablet view.', 'se-sopb-widgets' ),
								'default' => 810,
							),
							'columns' => array(
								'type' => 'slider',
								'label' => __( 'Number of columns', 'se-sopb-widgets' ),
								'min' => 1,
								'max' => 6,
								'default' => 2,
							),
							'gutter' => array(
								'type' => 'number',
								'label' => __( 'Gutter', 'se-sopb-widgets'),
								'description' => __( 'Space between masonry items.', 'se-sopb-widgets' ),
							),
						),
					),

					'mobile' => array(
						'type' => 'section',
						'label' => __( 'Mobile', 'se-sopb-widgets' ),
						'hide' => true,
						'fields' => array(
							'break_point' => array(
								'type' => 'number',
								'lanel' => __( 'Breakpoint', 'se-sopb-widgets' ),
								'description' => __( 'Device width, in pixels, at which to collapse into a mobile view.', 'se-sopb-widgets' ),
								'default' => 480,
							),
							'columns' => array(
								'type' => 'slider',
								'label' => __( 'Number of columns', 'se-sopb-widgets' ),
								'min' => 1,
								'max' => 3,
								'default' => 1,
							),
							'gutter' => array(
								'type' => 'number',
								'label' => __( 'Gutter', 'se-sopb-widgets'),
								'description' => __( 'Space between masonry items.', 'se-sopb-widgets' ),
							),
						),
					),
				),
			),
		);
	}

	public function get_template_variables( $instance, $args ) {
		$items = isset( $instance['items'] ) ? $instance['items'] : array();

		foreach ( $items as &$item ) {
			$link_atts = empty( $item['link_attributes'] ) ? array() : $item['link_attributes'];
			if ( ! empty( $item['new_window'] ) ) {
				$link_atts['target'] = '_blank';
				$link_atts['rel'] = 'noopener noreferrer';
			}
			$item['link_attributes'] = $link_atts;
			$item['title'] = $this->get_image_title( $item );
		}

		$gallery_id = uniqid('g_');

		return array(
			'args' => $args,
			'items' => !empty($instance['reverse_order']) ? array_reverse($items) : $items,
			'lightbox' => empty($instance['layout']['lightbox']) ? false : true,
			'gallery_id' => $gallery_id,
			'css' => $this->generate_css($instance['layout'], $gallery_id),
			'layouts' => array(
				'desktop' => siteorigin_widgets_underscores_to_camel_case(
					array(
						'num_columns' => empty( $instance['layout']['desktop']['columns'] ) ? 3 : $instance['layout']['desktop']['columns'],
						'gutter' => empty( $instance['layout']['desktop']['gutter'] ) ? 0 : (int) $instance['layout']['desktop']['gutter'],
					)
				),
				'tablet' => siteorigin_widgets_underscores_to_camel_case(
					array(
						'break_point' => empty( $instance['layout']['tablet']['columns'] ) ? '768px' : $instance['layout']['tablet']['break_point'],
						'num_columns' => empty( $instance['layout']['tablet']['columns'] ) ? 2 : $instance['layout']['tablet']['columns'],
						'gutter' => empty( $instance['layout']['tablet']['gutter'] ) ? 0 : (int) $instance['layout']['tablet']['gutter'],
					)
				),
				'mobile' => siteorigin_widgets_underscores_to_camel_case(
					array(
						'break_point' => empty( $instance['layout']['mobile']['columns'] ) ? '480px' : $instance['layout']['mobile']['break_point'],
						'num_columns' => empty( $instance['layout']['mobile']['columns'] ) ? 1 : $instance['layout']['mobile']['columns'],
						'gutter' => empty( $instance['layout']['mobile']['gutter'] ) ? 0 : (int) $instance['layout']['mobile']['gutter'],
					)
				),
			)
		);
	}

	function generate_css($layouts, $gallery_id) {

		$a_css = array();
		$types = array('desktop', 'tablet', 'mobile');

		foreach($layouts as $key => $prop) {

			if(!in_array($key, $types)) continue;

			$mgutter = ($layouts[$key]['gutter']/2).'px';
			$breakpoint = (isset($layouts[$key]['break_point'])) ? $layouts[$key]['break_point'] : 'desktop';

			$a_css[$breakpoint]['se-masonry-grid']['margin-left'] = -$mgutter."px";
			$a_css[$breakpoint]['se-masonry-grid']['margin-right'] = -$mgutter."px";
			$a_css[$breakpoint]['se-grid-item_se-grid-sizer']['width'] = round(100/$layouts[$key]['columns'], 2)."%";
			$a_css[$breakpoint]['se-grid-item']['padding-left'] = $mgutter;
			$a_css[$breakpoint]['se-grid-item']['padding-right'] = $mgutter;
			$a_css[$breakpoint]['se-grid-item']['margin-bottom'] = $layouts[$key]['gutter']."px";

		}

		$css = '';

		foreach($a_css as $breakpoint => $rules) {

			if(!is_numeric($breakpoint)) {
				foreach($rules as $selectors => $rule) {
					$css .= $this->get_selectors($selectors, $gallery_id);
					$css .= "{";
						foreach($rule as $prop => $val) {
							$css .= $prop.": ".$val.";";
						}
					$css .= "}";
				}
			} else {

				$css .= "@media screen and (max-width: ".$breakpoint."px) {";

				foreach($rules as $selectors => $rule) {
					$css .= $this->get_selectors($selectors, $gallery_id);
					$css .= "{";
						foreach($rule as $prop => $val) {
							$css .= $prop.": ".$val."; ";
						}
					$css .= "}";
				}

				$css .= "}";

			}

		}

		return $css;

	}

	function get_selectors($selectors, $gallery_id) {
		$selectors = explode('_', $selectors);
		foreach($selectors as &$selector) {
			$selector = " #".$gallery_id." .".$selector;
		}
		return implode(", ",$selectors);
	}

	/**
	 * Try to figure out an image's title for display.
	 *
	 * @param $image
	 *
	 * @return string The title of the image.
	 */
	private function get_image_title( $image ) {
		if ( ! empty( $image['title'] ) ) {
			$title = $image['title'];
		} else if ( apply_filters( 'siteorigin_widgets_auto_title', true, 'se-just-masonry' ) ) {
			$title = wp_get_attachment_caption( $image['image'] );
			if ( empty( $title ) ) {
				// We do not want to use the default image titles as they're based on the file name without the extension
				$file_name = pathinfo( get_post_meta( $image['image'], '_wp_attached_file', true ), PATHINFO_FILENAME );
				$title = get_the_title( $image['image'] );
				if ( $title == $file_name ) {
					return;
				}
			}
		} else {
			$title = '';
		}

		return $title;
	}

	function modify_instance( $instance ) {
		if ( empty( $instance ) ) {
			return array();
		}

		// If this Simple Masonry was created before the title settings were added, disable it by default.
		if ( ! empty( $instance['display'] ) || ! isset( $instance['title']['display'] ) ) {
			$instance['title']['title_display'] = false;
		}

		return $instance;
	}

}

siteorigin_widget_register( 'se-just-masonry', __FILE__, 'SE_Just_Masonry_Widget' );
