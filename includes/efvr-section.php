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
                'default' => '1',
                'options' => fvr_get_dropdown_options(),
                'label_block' => true,
            ]
        );

        $element->add_control(
            'fvr_relation_id',
            [
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '1',
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
                'type' => \Elementor\Controls_Manager::NUMBER,
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
        let isCtrlNameChanged = false;

        let fvr_add_edit = document.querySelector('.elementor-control.elementor-control-fvr_add_edit.elementor-control-type-select select[data-setting="fvr_add_edit"]');
        let rel_id = document.querySelector('.elementor-control.elementor-control-fvr_relation_id.elementor-control-type-text input[data-setting="fvr_relation_id"]');
        let rel_name = document.querySelector('.elementor-control.elementor-control-fvr_relation_name.elementor-control-type-text input[data-setting="fvr_relation_name"]');
        let master_value = document.querySelector('.elementor-control.elementor-control-fvr_master_value.elementor-control-type-number input[data-setting="fvr_master_value"]');

        fvr_add_edit.addEventListener('change', fvr_select_relation_changed);


        jQuery('#fvr-clear-all').click(fvr_remove_all_controllers);

        jQuery('#fvr-add-fake').click(function () {
            fvr_add_single_controller('Test')
        });

        rel_name.addEventListener('change', fvr_set_ctrl_name_changed);

        rel_name.addEventListener('blur', fvr_try_save_relation);

        master_value.addEventListener('blur', function () {
            fvr_update_relation(rel_name.value);
        });

        function fvr_try_save_relation() {
            if (isCtrlNameChanged) {
                if (rel_id.value != '1') {
                    fvr_add_edit.options[fvr_add_edit.selectedIndex].text = this.value;
                }

                fvr_update_relation(this.value);
                isCtrlNameChanged = false;
            }
        }

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

        function fvr_update_relation(relation_name) {
            let post_id = rel_id.value;
            let result = '';
            console.log(relation_name + ' post_id=' + post_id);
            if (!relation_name) return;

            jQuery.ajax({
                type: "post",
                url: "<?=admin_url('admin-ajax.php')?>",
                data: {
                    action: 'fvr_update_relation',
                    id: post_id,
                    title: relation_name,
                    master_value: master_value.value,
                },
                success: function (response) {
                    if (response) {
                        console.log(response);
                        result = JSON.parse(response);
                        console.log(result.success);
                        //Add new Relation to the Dropdown List
                        if (post_id == '1' && result.success) {
                            // create new option element
                            let opt = document.createElement('option');

                            // create text node to add to option element (opt)
                            opt.appendChild(document.createTextNode(result.title));

                            // set value property of opt
                            opt.value = result.post_id;

                            // add opt to end of select box (sel)
                            fvr_add_edit.appendChild(opt);
                            fvr_add_edit.value = result.post_id;

                            rel_id.value = result.post_id;
                        }
                    }
                }
            });
        }

        function fvr_select_relation_changed() {
            rel_id.value = fvr_add_edit.value;
            if (fvr_add_edit.value == '1') {
                rel_name.value = '';

            } else {
                rel_name.value = fvr_add_edit.options[fvr_add_edit.selectedIndex].text;
                fvr_get_relation_values(rel_id.value);
            }
            rel_id.value = fvr_add_edit.value;

            console.log(rel_id.value);
            console.log(fvr_add_edit.options[fvr_add_edit.selectedIndex].text);
        }

        function fvr_set_ctrl_name_changed() {
            isCtrlNameChanged = true;
        }

        function fvr_get_relation_values(id) {
            let res = false;
            jQuery.ajax({
                type: "post",
                url: "<?=admin_url('admin-ajax.php')?>",
                data: {
                    action: 'fvr_get_relation_meta',
                    id: id,
                },
                success: function (response) {
                    if (response) {
                        console.log(response);
                        res = JSON.parse(response);
                        master_value.value = res.master_value;

                    }
                }
            });
        }

    </script>
    <?php
    return ob_get_clean();

}

function fvr_get_relations()
{
    $query = array(
        'post_type' => 'efvr_relation',
        'posts_per_page' => -1,
        'order_by' => 'title',
        'order' => 'ASC',
    );

    return get_posts($query);
}

function fvr_get_dropdown_options()
{
    $relations = fvr_get_relations();
    $options = array('1' => __('Add New', 'efvr'));
    foreach ($relations as $relation) {
        $options[$relation->ID] = $relation->post_title;
    }
    return $options;
}