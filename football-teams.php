<?php
/*
 Plugin Name:   Football Teams
 Description: A Custom WordPress plugin that will make it possible to store football/sports teams categorized by the league they are from and list them with a custom-made Elementor widget.
 Plugin URI:  https://westerndeal.com/
 Version:     1.0.0
 Author:      Abdullah
 Author URI:  https://profiles.wordpress.org/westerndeal/
 License: GPLv2 or later
 Text Domain: football-teams
 */

require_once plugin_dir_path( __FILE__ ) . 'elementor-widget.php';

function football_teams_post_type() {
  $labels = array(
    'name' => 'Football Teams',
    'singular_name' => 'Football Team',
    'menu_name' => 'Football Teams',
    'name_admin_bar' => 'Football Team',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Football Team',
    'new_item' => 'New Football Team',
    'edit_item' => 'Edit Football Team',
    'view_item' => 'View Football Team',
    'all_items' => 'All Football Teams',
    'search_items' => 'Search Football Teams',
    'not_found' => 'No Football Teams found',
    'not_found_in_trash' => 'No Football Teams found in Trash',
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'football-teams'),
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title', 'editor', 'thumbnail')
  );

  register_post_type('football_team', $args);
}

add_action('init', 'football_teams_post_type');

add_action('init', 'create_football_teams_taxonomy');

function create_football_teams_taxonomy() {
  $labels = array(
    'name' => 'Leagues',
    'singular_name' => 'League',
    'menu_name' => 'Leagues',
    'all_items' => 'All Leagues',
    'edit_item' => 'Edit League',
    'view_item' => 'View League',
    'update_item' => 'Update League',
    'add_new_item' => 'Add New League',
    'new_item_name' => 'New League Name',
    'search_items' => 'Search Leagues',
    'popular_items' => 'Popular Leagues',
    'separate_items_with_commas' => 'Separate leagues with commas',
    'add_or_remove_items' => 'Add or remove leagues',
    'choose_from_most_used' => 'Choose from the most used leagues',
    'not_found' => 'No leagues found',
  );

  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'league'),
  );

  register_taxonomy('league', 'football_team', $args);
}

function football_teams_logo_meta() {
  add_meta_box(
    'football_teams_logo',
    'Logo',
    'football_teams_logo_callback',
    'football_team',
    'normal',
    'high'
  );

  add_meta_box(
    'football_teams_nickname',
    'Nickname',
    'football_teams_nickname_callback',
    'football_team',
    'normal',
    'high'
  );

  add_meta_box(
    'football_teams_history',
    'History',
    'football_teams_history_callback',
    'football_team',
    'normal',
    'high'
  );
}
add_action('add_meta_boxes', 'football_teams_logo_meta');

function football_teams_logo_callback($post) {
  wp_nonce_field(basename(__FILE__), 'football_teams_logo_nonce');
  $logo = get_post_meta($post->ID, 'football_teams_logo', true);
  ?>
  <div class="wrap">
    <div id="football_teams_logo">
      <input type="text" name="football_teams_logo" value="<?php echo esc_url($logo); ?>" id="football_teams_logo_url">
      <input type="button" name="football_teams_logo_button" id="football_teams_logo_button" value="Upload Logo">
    </div>
  </div>
  <script>
    jQuery(document).ready(function($) {
      var mediaUploader;
      $('#football_teams_logo_button').click(function(e) {
        e.preventDefault();
        if (mediaUploader) {
          mediaUploader.open();
          return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
          title: 'Choose Image',
          button: {
            text: 'Choose Image'
          },
          multiple: false
        });
        mediaUploader.on('select', function() {
          var attachment = mediaUploader.state().get('selection').first().toJSON();
          $('#football_teams_logo_url').val(attachment.url);
        });
        mediaUploader.open();
      });
    });
  </script>
  <?php
}
add_action('save_post', 'save_football_teams_logo');

function save_football_teams_logo($post_id) {
  // Check if the nonce is valid
  if (!isset($_POST['football_teams_logo_nonce']) || !wp_verify_nonce($_POST['football_teams_logo_nonce'], basename(__FILE__))) {
    return;
  }

  // Check if the user has permission to save the data
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  // Update the custom field value
  update_post_meta($post_id, 'football_teams_logo', $_POST['football_teams_logo']);
}


function football_teams_nickname_callback($post) {
wp_nonce_field(basename(__FILE__), 'football_teams_nickname_nonce');
$nickname = get_post_meta($post->ID, 'football_teams_nickname', true);
echo '<input type="text" name="football_teams_nickname" value="' . esc_attr($nickname) . '" class="widefat">';
}

add_action('save_post', 'save_football_teams_nickname');

function save_football_teams_nickname($post_id) {
  // Check if the nonce is valid
  if (!isset($_POST['football_teams_nickname_nonce']) || !wp_verify_nonce($_POST['football_teams_nickname_nonce'], basename(__FILE__))) {
    return;
  }

  // Check if the user has permission to save the data
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  // Update the custom field value
  update_post_meta($post_id, 'football_teams_nickname', $_POST['football_teams_nickname']);
}

function football_teams_history_callback($post) {
  wp_nonce_field(basename(__FILE__), 'football_teams_history_nonce');
  $history = get_post_meta($post->ID, 'football_teams_history', true);
  echo '<textarea name="football_teams_history" class="widefat">' . esc_textarea($history) . '</textarea>';
}

add_action('save_post', 'save_football_teams_history');

function save_football_teams_history($post_id) {
  // Check if the nonce is valid
  if (!isset($_POST['football_teams_history_nonce']) || !wp_verify_nonce($_POST['football_teams_history_nonce'], basename(__FILE__))) {
    return;
  }

  // Check if the user has permission to save the data
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  // Update the custom field value
  update_post_meta($post_id, 'football_teams_history', $_POST['football_teams_history']);
}

function list_football_teams() {
  $args = array(
    'post_type' => 'football_team',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'tax_query' => array(
      array(
        'taxonomy' => 'league',
        'field' => 'slug',
        'terms' => 'premier-league',
      ),
    ),
  );

  $teams = new WP_Query($args);

  if ($teams->have_posts()) {
    echo '<ul>';
    while ($teams->have_posts()) {
      $teams->the_post();
      echo '<li>' . get_the_title() . '</li>';
    }
    echo '</ul>';
    wp_reset_postdata();
  } else {
    echo 'No teams found';
  }
}



