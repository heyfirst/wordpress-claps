<?php
/*
 * Plugin Name: Wordpress Claps
 * Plugin URI: http://github.com/firstziiz/wordpress-claps
 * Description: Claps Widget for Single Blog
 * Version: 0.0.1
 * Author: Pronto Tools
 * Author URI: http://github.com/firstziiz
 * License: The MIT License
 * License URI: https://opensource.org/licenses/MIT
 */
define('PT_DIR_URI', plugins_url('', __FILE__));

function pt_assets() {
  wp_enqueue_style('pt-wordpress-claping-style', PT_DIR_URI.'/style.css');
  wp_enqueue_script('pt-wordpress-claping-script', PT_DIR_URI.'/script.js');

  wp_localize_script(
    'pt-wordpress-claping-script',
    'pt_ajax_obj',
    array('ajax_url' => admin_url('admin-ajax.php'))
  );
}

function pt_hello() {

  $claps = get_post_meta(
    get_the_ID(),
    'pt_claps',
    true
  );

  if (is_single()){
    ?>
      <div class="pt-claping" onclick="clapping(<?php echo get_the_ID() ?>)">👏🏻 <span><?php echo $claps ?></span></div>
    <?php
  }
}

function pt_do_clapping() {
  if(empty($_POST['post_id'])) {
    return null;
  }

  $claps = get_post_meta(
    $_POST['post_id'],
    'pt_claps',
    true
  );

  if ($claps == null) {
    update_post_meta($_POST['post_id'], 'pt_claps', 1);
    wp_send_json(['claps' => 1]);
    wp_die();
  } else {

    $new_claps = (int)$claps + 1;
    update_post_meta(
      $_POST['post_id'],
      'pt_claps',
      $new_claps
    );

    wp_send_json([
      'claps' => $new_claps
    ]);
    wp_die();
  }
}

add_action('wp_head', 'pt_hello');
add_action('wp_enqueue_scripts', 'pt_assets');
add_action('wp_ajax_pt_clap_increase', 'pt_do_clapping');
add_action('wp_ajax_nopriv_pt_clap_increase', 'pt_do_clapping');
