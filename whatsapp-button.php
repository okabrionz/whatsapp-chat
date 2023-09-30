<?php

/**
 * Plugin Name: WhatsApp Button
 * Description: Add a customizable WhatsApp button to your website.
 * Version: 1.0
 * Author: Oka bRionZ
 * Author URI: https://www.okabrionz.com
 */

// Activation Hook
register_activation_hook(__FILE__, 'whatsapp_button_activate');

function whatsapp_button_activate()
{
    // Add any activation code here
}

// Deactivation Hook
register_deactivation_hook(__FILE__, 'whatsapp_button_deactivate');

function whatsapp_button_deactivate()
{
    // Add any deactivation code here
}

// Add WhatsApp button to pages
function add_whatsapp_button()
{
    $phone_number = get_option('whatsapp_phone_number'); // Get the phone number from the admin dashboard
    $button_text = get_option('whatsapp_button_text'); // Get the button text from the admin dashboard

    if (!empty($phone_number)) {
        echo '<a href="https://wa.me/' . esc_attr($phone_number) . '" class="whatsapp-button" target="_blank">' . esc_html($button_text) . '</a>';
    }
}

add_action('wp_footer', 'add_whatsapp_button');

// Enqueue the CSS for styling the button
function enqueue_whatsapp_button_styles()
{
    wp_enqueue_style('whatsapp-button-styles', plugins_url('css/whatsapp-button.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'enqueue_whatsapp_button_styles');

// Create admin settings page
function whatsapp_button_menu()
{
    add_menu_page('WhatsApp Button Settings', 'WhatsApp Button', 'manage_options', 'whatsapp-button-settings', 'whatsapp_button_settings_page');
}

add_action('admin_menu', 'whatsapp_button_menu');

// Settings page HTML
function whatsapp_button_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
?>
    <div class="wrap">
        <h2>WhatsApp Button Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('whatsapp_button_settings_group'); ?>
            <?php do_settings_sections('whatsapp-button-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Settings fields
function whatsapp_button_settings_init()
{
    register_setting('whatsapp_button_settings_group', 'whatsapp_phone_number');
    register_setting('whatsapp_button_settings_group', 'whatsapp_button_text');

    add_settings_section('whatsapp_button_settings_section', 'WhatsApp Button Settings', 'whatsapp_button_settings_section_callback', 'whatsapp-button-settings');

    add_settings_field('whatsapp_phone_number', 'Phone Number (62XXXXXX)', 'whatsapp_phone_number_callback', 'whatsapp-button-settings', 'whatsapp_button_settings_section');
    add_settings_field('whatsapp_button_text', 'Button Text', 'whatsapp_button_text_callback', 'whatsapp-button-settings', 'whatsapp_button_settings_section');
}

function whatsapp_button_settings_section_callback()
{
    echo 'Enter your WhatsApp phone number and button text below:';
}

function whatsapp_phone_number_callback()
{
    $phone_number = get_option('whatsapp_phone_number');
    echo '<input type="text" id="whatsapp_phone_number" name="whatsapp_phone_number" value="' . esc_attr($phone_number) . '" />';
}

function whatsapp_button_text_callback()
{
    $button_text = get_option('whatsapp_button_text');
    echo '<input type="text" id="whatsapp_button_text" name="whatsapp_button_text" value="' . esc_attr($button_text) . '" />';
}

add_action('admin_init', 'whatsapp_button_settings_init');
