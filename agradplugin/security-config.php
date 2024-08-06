<?php
// Add security settings
function wpcustom_security_settings() {
    $options = get_option('wpcustom_settings');

    $htaccess_file = ABSPATH . '.htaccess';
    if (file_exists($htaccess_file) && is_writable($htaccess_file)) {
        $current_rules = file_get_contents($htaccess_file);
        $htaccess_rules_to_add = "
<files ~ \"^.*\.([Hh][Tt][Aa])\">
order allow,deny
deny from all
satisfy all
</files>
<files wp-config.php>
order allow,deny
deny from all
</files>
<Files xmlrpc.php>
order deny,allow
deny from all
</Files>
RewriteEngine On
RewriteBase /
RewriteRule ^wp-admin/includes/ - [F,L]
RewriteRule !^wp-includes/ - [S=3]
RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]
RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]
RewriteRule ^wp-includes/theme-compat/ - [F,L]
";

        // Check if the rules are already present
        if (isset($options['wpcustom_security']) && $options['wpcustom_security']) {
            if (strpos($current_rules, $htaccess_rules_to_add) === false) {
                file_put_contents($htaccess_file, $htaccess_rules_to_add, FILE_APPEND);
            }
        } else {
            // Remove security rules from .htaccess
            $new_rules = str_replace($htaccess_rules_to_add, '', $current_rules);
            file_put_contents($htaccess_file, $new_rules);
        }

        // Secure or revert wp-config.php permissions
        if (isset($options['wpcustom_security']) && $options['wpcustom_security']) {
            wpsh_secure_wpconfig();
        } else {
            wpsh_revert_wpconfig();
        }
    }
}
add_action('init', 'wpcustom_security_settings');

// Secure wp-config.php permissions
function wpsh_secure_wpconfig() {
    $wpconfig_path = ABSPATH . 'wp-config.php';
    if (file_exists($wpconfig_path)) {
        @chmod($wpconfig_path, 0400);
    }

    // Disable file editing
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }

    // Disallow access to XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');

    // Disable JSON API endpoints
    add_filter('json_enabled', '__return_false');
    add_filter('json_jsonp_enabled', '__return_false');
}

// Revert wp-config.php permissions
function wpsh_revert_wpconfig() {
    $wpconfig_path = ABSPATH . 'wp-config.php';
    if (file_exists($wpconfig_path)) {
        @chmod($wpconfig_path, 0644);
    }
}

?>
