<?php
/**
 * Elementor_FVR_Widget1.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */

class Elementor_FVR_Widget1 extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve EFVR widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'efvr';
    }

    /**
     * Get widget title.
     *
     * Retrieve EFVR widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return __('EFVR', 'efvr');
    }

    /**
     * Get widget icon.
     *
     * Retrieve EFVR widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'fa fa-code';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the EFVR widget belongs to.
     *
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_categories()
    {
        return ['general'];
    }

    /**
     * Register EFVR widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {

/*        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'efvr'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => __('URL to embed', 'efvr'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => __('https://your-link.com', 'efvr'),
            ]
        );

        $this->end_controls_section();*/



    }

    /**
     * Render EFVR widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {

        $settings = $this->get_settings_for_display();

        $html = wp_oembed_get($settings['url']);

        echo '<div class="fvr-elementor-widget">';

        echo ($html) ? $html : $settings['url'];

        echo '</div>';

    }

}
