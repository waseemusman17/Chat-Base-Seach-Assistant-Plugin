<?php

if (!defined('ABSPATH')) exit;

class CSA_Settings {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page() {
        add_options_page(
            'Chat Search Settings',
            'Chat Search Assistant',
            'manage_options',
            'chat-search-settings',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('csa_settings_group', 'csa_openai_api_key');
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Chat Search Assistant Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('csa_settings_group');
                do_settings_sections('csa_settings_group');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Groq API Key</th>
                        <td>
                            <input type="text" name="csa_openai_api_key" value="<?php echo esc_attr(get_option('csa_openai_api_key')); ?>" style="width: 400px;" />
                            <p class="description">Enter your Groq API key (from https://console.groq.com)</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
new CSA_Settings();
