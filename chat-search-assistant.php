<?php
/**
 * Plugin Name: Chat Search Assistant
 * Description: A chat-based search assistant using OpenAI or traditional WP search.
 * Version: 1.0
 * Author: Waseem Usman
 */

defined('ABSPATH') || exit;
define('CSA_PLUGIN_PATH', plugin_dir_path(__FILE__));

require_once plugin_dir_path(__FILE__) . 'includes/class-csa-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-csa-settings.php';


function csa_render_chat_box() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/chat-box.php';
    return ob_get_clean();
}
add_shortcode('chat_search', 'csa_render_chat_box');

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('csa-style', plugins_url('assets/css/style.css', __FILE__));
    wp_enqueue_script('csa-script', plugins_url('assets/js/chat.js', __FILE__), ['jquery'], null, true);
    wp_localize_script('csa-script', 'CSA', [
        'ajax_url' => rest_url('chat-search/v1/query'),
        'nonce'    => wp_create_nonce('csa_nonce'),
    ]);
});

function csa_add_custom_capability() {
    $role = get_role('administrator');
    if ($role && !$role->has_cap('edit_chat_search_settings')) {
        $role->add_cap('edit_chat_search_settings');
    }
}
register_activation_hook(__FILE__, 'csa_add_custom_capability');

// Make sure this function is defined before the add_action
function csa_handle_query(WP_REST_Request $request) {
    $data = $request->get_json_params();
    $prompt = sanitize_text_field($data['prompt'] ?? '');

    if (empty($prompt)) {
        error_log('CSA Debug: Prompt is empty.');
        return new WP_REST_Response(['error' => 'Prompt is empty.'], 400);
    }

    require_once plugin_dir_path(__FILE__) . 'includes/class-csa-api.php';
    $csa_api = new CSA_API_Handler();
    $response = $csa_api->get_openai_response($prompt);

    error_log('CSA Debug: API response: ' . print_r($response, true));

    if (empty($response)) {
        error_log('CSA Debug: No response from API.');
        return new WP_REST_Response(['error' => 'No response from API.'], 500);
    }

    // Check for error messages and return as error
    if (stripos($response, 'error:') === 0 || stripos($response, 'OpenAI error:') === 0) {
        error_log('CSA Debug: API error: ' . $response);
        return new WP_REST_Response(['error' => $response], 500);
    }

    return new WP_REST_Response(['response' => $response]);
}


add_action('rest_api_init', function () {
  register_rest_route('chat-search/v1', '/query', [
    'methods'  => 'POST',
    'callback' => 'csa_handle_query',
    'permission_callback' => '__return_true', // Allow public access
  ]);
});

add_action('wp_footer', function () {
    if (is_admin()) return;

    $template_path = CSA_PLUGIN_PATH . 'templates/chat-box.php';
    if (file_exists($template_path)) {
        include $template_path;
    }
});