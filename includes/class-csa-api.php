<?php

if (!defined('ABSPATH')) exit;

class CSA_API_Handler {
    private $api_key;

    public function __construct() {
        $this->api_key = get_option('csa_openai_api_key'); // Set via admin or hardcode for now
    }

    public function get_openai_response($prompt) {
        if (!$this->api_key) {
            error_log('Groq API key is missing.');
            return 'Error: API key not set.';
        }

        $response = wp_remote_post('https://api.groq.com/openai/v1/chat/completions', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->api_key,
            ],
            'body' => json_encode([
                'model' => 'llama3-70b-8192',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]),
            'timeout' => 20,
        ]);

        if (is_wp_error($response)) {
            error_log('Groq WP Error: ' . $response->get_error_message());
            return 'Something went wrong. Please try again.';
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['choices'][0]['message']['content'])) {
            error_log('Groq API Response Error: ' . $body);
            return 'Something went wrong. Please try again.';
        }

        return trim($data['choices'][0]['message']['content']);
    }

}
