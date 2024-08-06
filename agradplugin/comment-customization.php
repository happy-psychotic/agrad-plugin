<?php
// Apply comment customization only if enabled
function wpcustom_customize_comment_form() {
  $options = get_option('wpcustom_settings');

  if (isset($options['wpcustom_comments']) && $options['wpcustom_comments']) {
      // Your comment customization code here
      add_filter('comment_form_defaults', 'wpcustom_custom_comment_form');
  }
}
add_action('init', 'wpcustom_customize_comment_form');

function wpcustom_custom_comment_form($defaults) {
  // Default comment form includes name, email address and website URL
  // Default comment form elements are hidden when user is logged in

  add_filter('comment_form_default_fields', 'custom_fields');
  function custom_fields($fields) {

      $commenter = wp_get_current_commenter();

      $fields[ 'author' ] = '<p class="comment-form-author">'.
        '<label for="author">' . __( 'Name' ) . '</label>'.
        '<span class="required"> * </span>' .
        '<input id="author" name="author" placeholder="فارسی و الزامی" type="text" value="'. esc_attr( $commenter['comment_author'] ) .
        '" size="30" ' . "required" . ' /></p>';
        
      $fields[ 'email' ] = '<p class="comment-form-email">'.
        '<label for="email">' . __( 'Email' ) . '</label>'.
        '<input id="email" name="email" placeholder="جهت اطلاع رسانی" type="text" value="'. esc_attr( $commenter['comment_author_email'] ) .
        '" size="30" /></p>';

      if(isset($fields['url'])){
          unset($fields['url']);
      }
    return $fields;
  }
  function require_comment_name($fields) {
  
  if ($fields['comment_author'] == '')
    wp_die('
    لطفا نام خود را وارد کنید 
    <hr>
    <form>
      <input type="button" value="بازگشت" onclick="history.back()">
    </form>
    ');
    
    return $fields;
  }
  add_filter('preprocess_comment', 'require_comment_name');
  add_filter( 'cancel_comment_reply_link', '__return_false' );

  add_filter('comment_form_defaults','my_comment_form_title');
  function my_comment_form_title ($defaults) {

    $defaults['title_reply'] = (__( 'پرسش و پاسخ','my-text-domain' ));

    return $defaults;

  }
  add_filter('get_comment_author', 'wpag_comment_author_display_name');

  function wpag_comment_author_display_name($author) {
      global $comment;
      if (!empty($comment->user_id)){
          $user=get_userdata($comment->user_id);
          $author=$user->display_name;    
      }

      return $author;
  }
  // Remove the logout link in comment form
  add_filter( 'comment_form_logged_in', '__return_empty_string' );
}

?>