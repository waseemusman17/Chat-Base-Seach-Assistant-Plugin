<?php
class CSA_Settings {
    public static function init() {
        add_action('admin_menu', [self::class, 'add_settings_page']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    public static function add_settings_page() {
        add_options_page(
            'Chat Search Settings',
            'Chat Search',
            'edit_chat_search_settings',
            'csa-settings',
            [self::class, 'render_settings_page']
        );
    }

    public static function register_settings() {
        register_setting('csa_settings_group', 'csa_openai_api_key');
        register_setting('csa_settings_group', 'csa_temperature');
        register_setting('csa_settings_group', 'csa_system_prompt');

        add_settings_section('csa_main_section', 'Main Settings', null, 'csa-settings');

        add_settings_field('csa_openai_api_key', 'OpenAI API Key', function () {
            echo '<input type="text" name="csa_openai_api_key" value="' . esc_attr(get_option('csa_openai_api_key')) . '" class="regular-text">';
        }, 'csa-settings', 'csa_main_section');

        add_settings_field('csa_temperature', 'Temperature (0-1)', function () {
            echo '<input type="number" step="0.1" min="0" max="1" name="csa_temperature" value="' . esc_attr(get_option('csa_temperature', 0.7)) . '">';
        }, 'csa-settings', 'csa_main_section');

        add_settings_field('csa_system_prompt', 'System Prompt', function () {
            echo '<textarea name="csa_system_prompt" rows="4" class="large-text">' . esc_textarea(get_option('csa_system_prompt', 'You are a helpful assistant.')) . '</textarea>';
        }, 'csa-settings', 'csa_main_section');
    }

    public static function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Chat Search Assistant Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('csa_settings_group');
                do_settings_sections('csa-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
CSA_Settings::init();
