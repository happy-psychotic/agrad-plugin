<?php
// Add admin menu for settings
function wpcustom_add_admin_menu() {
    add_options_page(
        'Agrad Plugin Settings',
        'Agrad Plugin',
        'manage_options',
        'wpcustom_settings',
        'wpcustom_settings_page'
    );
}
add_action('admin_menu', 'wpcustom_add_admin_menu');

// Render the settings page
function wpcustom_settings_page() {
    ?>
    <div class="wrap">
        <h1>Agrad Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wpcustom_options_group');
            do_settings_sections('wpcustom_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function wpcustom_register_settings() {
    register_setting(
        'wpcustom_options_group',
        'wpcustom_settings',
        'wpcustom_settings_sanitize'
    );

    add_settings_section(
        'wpcustom_main_section',
        'Main Settings',
        null,
        'wpcustom_settings'
    );

    add_settings_field(
        'wpcustom_security',
        'Enable Security',
        'wpcustom_security_field',
        'wpcustom_settings',
        'wpcustom_main_section'
    );

    add_settings_field(
        'wpcustom_comments',
        'Enable Comment Customization',
        'wpcustom_comments_field',
        'wpcustom_settings',
        'wpcustom_main_section'
    );
}
add_action('admin_init', 'wpcustom_register_settings');

// Sanitize settings input
function wpcustom_settings_sanitize($input) {
    $input['wpcustom_security'] = isset($input['wpcustom_security']) ? 1 : 0;
    $input['wpcustom_comments'] = isset($input['wpcustom_comments']) ? 1 : 0;
    return $input;
}

// Render the security field
function wpcustom_security_field() {
    $options = get_option('wpcustom_settings');
    $checked = isset($options['wpcustom_security']) ? 'checked' : '';
    echo '<input type="checkbox" name="wpcustom_settings[wpcustom_security]" value="1" ' . $checked . ' />';
}

// Render the comment customization field
function wpcustom_comments_field() {
    $options = get_option('wpcustom_settings');
    $checked = isset($options['wpcustom_comments']) ? 'checked' : '';
    echo '<input type="checkbox" name="wpcustom_settings[wpcustom_comments]" value="1" ' . $checked . ' />';
}

// Set default options on plugin activation
function wpcustom_set_default_options() {
    $default_options = array(
        'wpcustom_security' => 1, // Enabled by default
        'wpcustom_comments' => 1, // Enabled by default
    );
    add_option('wpcustom_settings', $default_options);
}
register_activation_hook(__FILE__, 'wpcustom_set_default_options');
?>
