<?php
/**
 * Add widget banner to elementor
 *
 * @since   1.6.2
 * @package Claue
 */
 
class Claue_Elementor_Service_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'claue-service';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Icon and Text', 'claue' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image';
	}
    
    /**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'icon', 'text' );
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'claue-elements' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {
	   
        $this->start_controls_section(
			'claue_addons_service',
			array(
				'label' => __( 'Content', 'claue' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

        $this->add_control(
            'icon',
            array(
                'label' => esc_html__( 'Icon', 'claue' ),
                'type' => \Elementor\Controls_Manager::ICONS,
            )
        );

        $this->add_control(
            'icon_style',
            array(
                'label' => esc_html__( 'Icon Style', 'claue' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__( 'Default', 'claue' ),
                    'square' => esc_html__( 'Square', 'claue' ),
                    'circle' => esc_html__( 'Circle', 'claue' ),
                ),
                'default' => '',
            )
        );

        $this->add_control(
            'icon_size',
            array(
                'label' => esc_html__( 'Icon Size', 'claue' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'small' => esc_html__( 'Small', 'claue' ),
                    'medium' => esc_html__( 'Medium', 'claue' ),
                    'large' => esc_html__( 'Large', 'claue' ),
                ),
                'default' => 'small',
            )
        );

        $this->add_control(
            'icon_position',
            array(
                'label' => esc_html__( 'Icon Position', 'claue' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'tc' => esc_html__( 'Top', 'claue' ),
                    'tr' => esc_html__( 'Right', 'claue' ),
                    'tl' => esc_html__( 'Left', 'claue' ),
                ),
                'default' => 'tc',
            )
        );

        $this->add_control(
            'title',
            array(
                'label' => esc_html__( 'Title', 'claue' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => '',
            )
        );

        $this->add_control(
            'entry',
            array(
                'label' => esc_html__( 'Content', 'claue' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'claue' ),
                'description' => '',
                'type' => \Elementor\Controls_Manager::COLOR,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__( 'Title Color', 'claue' ),
                'description' => '',
                'type' => \Elementor\Controls_Manager::COLOR,
            )
        );

        $this->add_control(
            'content_color',
            array(
                'label' => esc_html__( 'Content Color', 'claue' ),
                'description' => '',
                'type' => \Elementor\Controls_Manager::COLOR,
            )
        );

        ///////////////////////
        
        $this->add_control(
			'css_animation',
			array(
				'label' => esc_html__( 'CSS Animation', 'claue' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => array(
                    'top-to-bottom' => esc_html__( 'Top to bottom', 'claue' ),
					'bottom-to-top' => esc_html__( 'Bottom to top', 'claue' ),
					'left-to-right' => esc_html__( 'Left to right', 'claue' ),
					'right-to-left' => esc_html__( 'Right to left', 'claue' ),
					'appear' => esc_html__( 'Appear from center', 'claue' ),
				),
				'default' => 'top-to-bottom',
			)
		);
        
        $this->add_control(
			'class',
			array(
				'label' => esc_html__( 'Extra class name', 'claue' ),
                'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'claue' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => '',
			)
		);

		$this->end_controls_section();

	}

	/**
     * Create shortcode row
	 * 
	 * @return string
	 */
	public function create_shortcode() {

		$settings = $this->get_settings_for_display();
        
        $args_row = '';
        
        //////////////////
        $args_row .= $settings['css_animation'] ? ' css_animation="'.esc_attr($settings['css_animation']).'"' : '';
        
        $args_row .= $settings['class'] ? ' class="'.esc_attr($settings['class']).'"' : '';

        /////////////////////

        $args_row .= $settings['icon'] ? ' icon="'.esc_attr($settings['icon']['value']).'"' : '';

        $args_row .= $settings['icon_style'] ? ' icon_style="'.esc_attr($settings['icon_style']).'"' : '';

        $args_row .= $settings['icon_size'] ? ' icon_size="'.esc_attr($settings['icon_size']).'"' : '';

        $args_row .= $settings['icon_position'] ? ' icon_position="'.esc_attr($settings['icon_position']).'"' : '';

        $args_row .= $settings['title'] ? ' title="'.esc_attr($settings['title']).'"' : '';

        $args_row .= $settings['entry'] ? ' entry="'.esc_attr($settings['entry']).'"' : '';

        $args_row .= $settings['icon_color'] ? ' icon_color="'.esc_attr($settings['icon_color']).'"' : '';

        $args_row .= $settings['title_color'] ? ' title_color="'.esc_attr($settings['title_color']).'"' : '';

        $args_row .= $settings['content_color'] ? ' content_color="'.esc_attr($settings['content_color']).'"' : '';
        
        return '[claue_addons_service'.$args_row.']';

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 */
	protected function render() {

		echo do_shortcode( $this->create_shortcode() );
		
	}
    
    /**
	 * Render widget as plain content.
	 *
	 * Override the default behavior by printing the shortcode instead of rendering it.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	 public function render_plain_content() {
	   
		// In plain mode, render without shortcode
		echo wp_kses_post( $this->create_shortcode() );
        
	 }

}
/////////////////////
