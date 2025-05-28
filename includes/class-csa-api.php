<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class CSA_API_Handler {
    private $api_key;

    public function __construct() {
        $this->api_key = get_option('csa_openai_api_key');
    }

    public function get_openai_response($prompt) {
        if (!$this->api_key) {
            error_log('OpenAI API key is missing.');
            return 'Error: API key not set.';
        }

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->api_key,
            ],
            'body' => json_encode([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]),
            'timeout' => 20,
        ]);

        if (is_wp_error($response)) {
            error_log('OpenAI WP Error: ' . $response->get_error_message());
            return 'Something went wrong. Please try again.';
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['choices'][0]['message']['content'])) {
            error_log('OpenAI API Response Error: ' . $body);
            return 'Something went wrong. Please try again.';
        }

        return trim($data['choices'][0]['message']['content']);
    }
}
