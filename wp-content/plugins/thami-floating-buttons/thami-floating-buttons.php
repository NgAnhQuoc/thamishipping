<?php
/**
 * Plugin Name: Thami Floating Support Buttons
 * Description: Floating support buttons matching thamishipping.com (Skype Inquiry and Zalo)
 * Version: 1.0
 * Author: Antigravity
 */

if (!defined('ABSPATH')) {
    exit;
}

define('THAMI_FLOAT_PATH', plugin_dir_url(__FILE__));

// Include admin settings
require_once plugin_dir_path(__FILE__) . 'inc/admin-settings.php';

// Set default options on activation
function thami_float_activate()
{
    $defaults = array(
        'enable_skype' => 1,
        'enable_zalo' => 1,
        'skype_label' => 'ONLINE' . "\n" . 'INQUIRY',
        'skype_items' => "LCL Export|tmclcl_tthao\nFCL Export|tmcfcl_tthao\nAir Export|tmca_ngoc\nImport (LCL, FCL, Air)|tmccl_thanh\nDomestic|tmclcl_truc\nOther Inquiries|tmclcl_thuong",
        'zalo_label' => 'ONLINE',
        'zalo_link' => 'https://zalo.me/0901139019'
    );
    if (!get_option('thami_float_settings')) {
        update_option('thami_float_settings', $defaults);
    }
}
register_activation_hook(__FILE__, 'thami_float_activate');

function thami_float_enqueue_scripts()
{
    wp_enqueue_style('thami-float-style', THAMI_FLOAT_PATH . 'assets/css/style.css', array(), '1.0');
    wp_enqueue_script('thami-float-script', THAMI_FLOAT_PATH . 'assets/js/script.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'thami_float_enqueue_scripts');

function thami_float_add_buttons()
{
    $options = get_option('thami_float_settings');

    $enable_skype = isset($options['enable_skype']) ? $options['enable_skype'] : 0;
    $enable_zalo = isset($options['enable_zalo']) ? $options['enable_zalo'] : 0;

    if (!$enable_skype && !$enable_zalo)
        return;
    ?>
    <div id="cs-thamibutton-wrapper">
        <div class="cs-thamibutton-container">
            <?php if ($enable_skype):
                $skype_items = isset($options['skype_items']) ? explode("\n", $options['skype_items']) : array();
                ?>
                <div class="cs-thamibutton-submenu">
                    <?php
                    foreach ($skype_items as $item) {
                        $parts = explode('|', trim($item));
                        if (count($parts) === 2) {
                            $label = $parts[0];
                            $skype_id = $parts[1];
                            ?>
                            <a href="skype:<?php echo esc_attr($skype_id); ?>?chat" class="cs-thamibutton-submenu-item">
                                <img src="<?php echo THAMI_FLOAT_PATH; ?>assets/img/skype.png" alt="Skype">
                                <span><?php echo esc_html($label); ?></span>
                            </a>
                            <?php
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>

            <div class="cs-thamibutton-main-buttons">
                <?php if ($enable_skype): ?>
                    <div class="cs-thamibutton-main-btn cs-thamibutton-btn-skype" id="cs-thamibutton-btn-inquiry">
                        <div class="cs-thamibutton-btn-text"><?php echo nl2br(esc_html($options['skype_label'])); ?></div>
                        <div class="cs-thamibutton-btn-icon">
                            <img src="<?php echo THAMI_FLOAT_PATH; ?>assets/img/skype.png" alt="Skype">
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($enable_zalo): ?>
                    <a href="<?php echo esc_url($options['zalo_link']); ?>" target="_blank"
                        class="cs-thamibutton-main-btn cs-thamibutton-btn-zalo">
                        <div class="cs-thamibutton-btn-text"><?php echo esc_html($options['zalo_label']); ?></div>
                        <div class="cs-thamibutton-btn-icon">
                            <img src="<?php echo THAMI_FLOAT_PATH; ?>assets/img/zalo.png" alt="Zalo">
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'thami_float_add_buttons');
