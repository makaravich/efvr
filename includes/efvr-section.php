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
            'fvr_relation_id',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
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
    <script type="text/javascript">

        jQuery('#fvr-clear-all').click(fvr_remove_all_controllers);

        jQuery('#fvr-add-fake').click(function () {
            fvr_add_single_controller('Test')
        });

        jQuery('.elementor-control.elementor-control-fvr_relation_name.elementor-control-type-text input[data-setting="fvr_relation_name"]').blur(function () {
            fvr_update_relation(this.value);
        });

        function fvr_add_single_controller(ctrl_value) {
            //let ctrl_value='Ctrlr';
            // We add an element to the repeater by simulating a click on the add button
            jQuery('.elementor-button.elementor-button-default.elementor-repeater-add').click();

            // We grab the latest item of the Repeater field (the one we just triggered the creation of
            var jQuery_scope_repeater_row = jQuery('.elementor-control-fvr_list .elementor-repeater-fields:last-child');

            // We set the value of the input field
            jQuery('.elementor-control.elementor-control-fvr_controller_name.elementor-control-type-text input[data-setting="fvr_controller_name"]', jQuery_scope_repeater_row).val(ctrl_value);

            // We trigger the input event to ask Elementor to update the widget preview
            jQuery('.elementor-control.elementor-control-fvr_controller_name.elementor-control-type-text input[data-setting="fvr_controller_name"]', jQuery_scope_repeater_row).trigger('input');

        }

        function fvr_remove_all_controllers() {
            jQuery('.elementor-repeater-row-tool.elementor-repeater-tool-remove').each(function () {
                jQuery(this).click();
                console.log('All controllers has been removed');
            });
        }

        function fvr_update_relation(rel_name) {
            console.log(rel_name);
            let post_id = jQuery('.elementor-control.elementor-control-fvr_relation_id.elementor-control-type-text input[data-setting="fvr_relation_id"]').value;
            jQuery.ajax({
                type: "post",
                url: "<?=admin_url('admin-ajax.php')?>",
                data: {
                    action: 'fvr_update_relation',
                    id: post_id,
                    title: rel_name,
                },
                success: function (response) {
                    if (response) {
                        console.log(response);
                    }
                }
            });
        }

        function fvr_select_relation_changed() {
            let post_id = jQuery('.elementor-control.elementor-control-fvr_relation_id.elementor-control-type-text input[data-setting="fvr_relation_id"]');
            
        }
    </script>
    <?php
    return ob_get_clean();

}