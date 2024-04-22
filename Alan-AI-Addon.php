<?php
/**
 * Plugin Name: Alan AI Chatbot
 * Plugin URI: https://webxlr8.com
 * Description: This plugin integrates the Alan AI Chatbot into your WordPress website.
 * Version: 1.0.1
 * Author: webxlr8.com
 * Author URI: https://webxlr8.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function alan_ai_chatbot_addon_scripts() {
    wp_enqueue_script('alan-ai-chatbot', 'https://studio.alan.app/web/lib/alan_lib.min.js', array(), '1.0', true);

    $api_key = get_option('alan_ai_chatbot_api_key');

    $script = '
        var alanBtnInstance = alanBtn({
            key: "' . $api_key . '",
            onCommand: function (commandData) {
                if (commandData.command === "go:back") {
                    // Call client code that will react to the received command
                }
            },
            rootEl: document.getElementById("alan-btn"),
        });
    ';

    wp_add_inline_script('alan-ai-chatbot', $script);
}

function alan_ai_chatbot_addon_menu() {
    add_options_page('Alan AI Chatbot Addon Settings', 'Alan AI Chatbot Addon', 'manage_options', 'alan-ai-chatbot-addon-settings', 'alan_ai_chatbot_addon_settings_page');
}

function alan_ai_chatbot_addon_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['alan_ai_chatbot_api_key']) && wp_verify_nonce($_POST['_wpnonce'], 'alan_ai_chatbot_nonce')) {
        update_option('alan_ai_chatbot_api_key', sanitize_text_field($_POST['alan_ai_chatbot_api_key']));
    }

    $api_key = get_option('alan_ai_chatbot_api_key');

    ?>
    <div class="wrap">
        <h1>Alan AI Chatbot Addon Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('alan_ai_chatbot_nonce', '_wpnonce'); ?>
            <label for="alan_ai_chatbot_api_key">API Key:</label>
            <input type="text" name="alan_ai_chatbot_api_key" id="alan_ai_chatbot_api_key" value="<?php echo esc_attr($api_key); ?>">
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'alan_ai_chatbot_addon_menu');
add_action('wp_enqueue_scripts', 'alan_ai_chatbot_addon_scripts');