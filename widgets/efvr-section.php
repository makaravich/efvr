<?php
add_action('elementor/element/after_section_end', function ($element, $section_id, $args) {
    /** @var \Elementor\Element_Base $element */

    if (/*'common' === $element->get_name() &&*/ 'section_custom_css_pro' === $section_id) {

        $element->start_controls_section(
            'efvr-tab',
            [
                'label' => __('Frontend Value Relations', 'efvr'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'fvr_add_edit',
            [
                'label' => __('Edit existing or add new relation', 'efvr'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'new',
                'options' => [
                    'new' => __('Add New', 'efvr'),
                    '1' => __('Something', 'efvr'),
                ],
                'label_block' => true,
            ]
        );

        $element->add_control(
            'fvr_relation_name',
            [
                'label' => __('Set, edit or delete relation name', 'efvr'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'fvr_controller_name',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );

        $element->add_control(
            'fvr_list',
            [
                'label' => __('Paste specific controller name to be matched to each other', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'fvr_controller_name' => __('', 'plugin-domain'),
                    ],
                ],
                'title_field' => '{{{ fvr_controller_name }}}',
            ]
        );

        $element->add_control(
            'fvr_master_value',
            [
                'label' => __('Edit master value', 'efvr'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $element->end_controls_section();
    }
}, 10, 3);