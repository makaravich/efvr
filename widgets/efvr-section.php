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

        $element->add_control(
            'fvr_js',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'default' => '',
                'raw' => fvr_get_admin_js(),
            ]
        );


        $element->end_controls_section();
    }
}, 10, 3);

function fvr_get_admin_js()
{
    ob_start();
    ?>
    <button id="fvr-clear-all">Clear All</button>
    <button id="fvr-add-fake">Add Fake</button>
    <script>
        //jQuery('.elementor-repeater-row-tool.elementor-repeater-tool-remove').click();
        jQuery('#fvr-clear-all').click(function () {
            jQuery('.elementor-repeater-row-tool.elementor-repeater-tool-remove').each(function () {
                jQuery(this).click();
                console.log('!!!');
            });
        });

        jQuery('#fvr-add-fake').click(function () {
            // We add an element to the repeater by simulating a click on the add button
            jQuery('.elementor-repeater-add', jQuery_scope_control_repeater).click();

            // We grab the latest item of the Repeater field (the one we just triggered the creation of
            var jQuery_scope_repeater_row = jQuery('.elementor-control-fvr_list .elementor-repeater-fields:last-child');

            // We set the value of the input field
            jQuery('.elementor-control.elementor-control-fvr_controller_name.elementor-control-type-text input[data-setting="fvr_controller_name"]', jQuery_scope_repeater_row).val('MY_FIELD_VALUE');

            // We trigger the input event to ask Elementor to update the widget preview
            jQuery('.elementor-control.elementor-control-fvr_controller_name.elementor-control-type-text input[data-setting="fvr_controller_name"]', jQuery_scope_repeater_row).trigger('input');

        });


    </script>
    <?php
    return ob_get_clean();

}