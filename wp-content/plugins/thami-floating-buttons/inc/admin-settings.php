<?php
if (!defined('ABSPATH')) {
    exit;
}

class Thami_Float_Admin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu()
    {
        add_menu_page(
            'Thami Floating Buttons Settings',
            'Thami Buttons',
            'manage_options',
            'thami-float-buttons',
            array($this, 'settings_page'),
            'dashicons-phone', // Added phone icon
            27 // Right below Thami Crawler (which is at 26)
        );
    }

    public function register_settings()
    {
        register_setting('thami_float_options', 'thami_float_settings');

        add_settings_section(
            'thami_float_main_section',
            'General Settings',
            null,
            'thami-float-buttons'
        );

        add_settings_field(
            'enable_skype',
            'Enable Skype Inquiry',
            array($this, 'checkbox_field'),
            'thami-float-buttons',
            'thami_float_main_section',
            array('label_for' => 'enable_skype')
        );

        add_settings_field(
            'enable_zalo',
            'Enable Zalo',
            array($this, 'checkbox_field'),
            'thami-float-buttons',
            'thami_float_main_section',
            array('label_for' => 'enable_zalo')
        );

        add_settings_section(
            'thami_float_skype_section',
            'Skype Inquiry Configuration',
            null,
            'thami-float-buttons'
        );

        add_settings_field(
            'skype_label',
            'Skype Main Label',
            array($this, 'text_field'),
            'thami-float-buttons',
            'thami_float_skype_section',
            array('label_for' => 'skype_label')
        );

        add_settings_field(
            'skype_items',
            'Inquiry Items',
            array($this, 'skype_items_field'),
            'thami-float-buttons',
            'thami_float_skype_section',
            array(
                'label_for' => 'skype_items'
            )
        );

        add_settings_section(
            'thami_float_zalo_section',
            'Zalo Configuration',
            null,
            'thami-float-buttons'
        );

        add_settings_field(
            'zalo_label',
            'Zalo Label',
            array($this, 'text_field'),
            'thami-float-buttons',
            'thami_float_zalo_section',
            array('label_for' => 'zalo_label')
        );

        add_settings_field(
            'zalo_link',
            'Zalo Link',
            array($this, 'text_field'),
            'thami-float-buttons',
            'thami_float_zalo_section',
            array('label_for' => 'zalo_link')
        );
    }

    public function checkbox_field($args)
    {
        $options = get_option('thami_float_settings');
        $id = $args['label_for'];
        $value = isset($options[$id]) ? $options[$id] : 0;
        ?>
        <input type="checkbox" id="<?php echo $id; ?>" name="thami_float_settings[<?php echo $id; ?>]" value="1" <?php checked(1, $value); ?>>
        <?php
    }

    public function text_field($args)
    {
        $options = get_option('thami_float_settings');
        $id = $args['label_for'];
        $value = isset($options[$id]) ? esc_attr($options[$id]) : '';
        ?>
        <input type="text" id="<?php echo $id; ?>" name="thami_float_settings[<?php echo $id; ?>]" value="<?php echo $value; ?>"
            class="regular-text">
        <?php
    }

    public function textarea_field($args)
    {
        $options = get_option('thami_float_settings');
        $id = $args['label_for'];
        $value = isset($options[$id]) ? esc_textarea($options[$id]) : '';
        ?>
        <textarea id="<?php echo $id; ?>" name="thami_float_settings[<?php echo $id; ?>]" rows="8" cols="50"
            class="large-text"><?php echo $value; ?></textarea>
        <?php if (isset($args['description'])): ?>
            <p class="description"><?php echo $args['description']; ?></p>
        <?php endif;
    }

    public function skype_items_field($args)
    {
        $options = get_option('thami_float_settings');
        $id = $args['label_for'];
        $value = isset($options[$id]) ? esc_textarea($options[$id]) : '';
        ?>
        <textarea id="<?php echo $id; ?>" name="thami_float_settings[<?php echo $id; ?>]"
            style="display:none;"><?php echo $value; ?></textarea>

        <table class="widefat" id="skype-items-table" style="max-width: 600px; margin-bottom: 10px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 45%;">Label</th>
                    <th style="width: 45%;">SkypeID</th>
                    <th style="width: 10%;">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be populated by JS -->
            </tbody>
        </table>
        <button type="button" class="button" id="add-skype-item">Add New</button>
        <?php if (isset($args['description'])): ?>
            <p class="description"><?php echo $args['description']; ?></p>
        <?php endif; ?>

        <script>
            jQuery(document).ready(function ($) {
                var $textarea = $('#<?php echo $id; ?>');
                var $tbody = $('#skype-items-table tbody');

                function renderRows() {
                    var lines = $textarea.val().split('\n');
                    $tbody.empty();
                    $.each(lines, function (index, line) {
                        if ($.trim(line) !== '') {
                            var parts = line.split('|');
                            var label = parts[0] ? parts[0] : '';
                            var skypeId = parts[1] ? parts[1] : '';
                            addRow(label, skypeId);
                        }
                    });
                }

                function addRow(label, skypeId) {
                    var tr = $('<tr>');
                    tr.append('<td><input type="text" class="regular-text skype-label" style="width: 100%;" value="' + escapeHtml(label) + '"></td>');
                    tr.append('<td><input type="text" class="regular-text skype-id" style="width: 100%;" value="' + escapeHtml(skypeId) + '"></td>');
                    tr.append('<td><button type="button" class="button remove-row" style="color: #b32d2e; border-color: #b32d2e;">Delete</button></td>');
                    $tbody.append(tr);
                }

                function updateTextarea() {
                    var lines = [];
                    $tbody.find('tr').each(function () {
                        var label = $(this).find('.skype-label').val();
                        var skypeId = $(this).find('.skype-id').val();
                        if (label || skypeId) {
                            lines.push(label + '|' + skypeId);
                        }
                    });
                    $textarea.val(lines.join('\n'));
                }

                function escapeHtml(text) {
                    var map = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };
                    return text.replace(/[&<>"']/g, function (m) { return map[m]; });
                }

                $('#add-skype-item').on('click', function () {
                    addRow('', '');
                    updateTextarea();
                });

                $tbody.on('click', '.remove-row', function () {
                    $(this).closest('tr').remove();
                    updateTextarea();
                });

                $tbody.on('input', 'input', function () {
                    updateTextarea();
                });

                // Make rows sortable if jQuery UI Sortable is available
                if ($.fn.sortable) {
                    $tbody.sortable({
                        helper: function (e, ui) {
                            ui.children().each(function () {
                                $(this).width($(this).width());
                            });
                            return ui;
                        },
                        update: function (event, ui) {
                            updateTextarea();
                        }
                    });
                }

                renderRows();
            });
        </script>
        <?php
    }

    public function settings_page()
    {
        ?>
        <div class="wrap">
            <h1>Thami Floating Buttons Settings</h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('thami_float_options');
                do_settings_sections('thami-float-buttons');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}

new Thami_Float_Admin();
