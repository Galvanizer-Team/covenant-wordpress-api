<?php

/**
 * Plugin Name: CL Custom API Route 
 * Description: Exposes a custom API route for the next.js app to consume
 * Version: 1.0
 * Author: David Crammer
 * Author URI: https://davidcrammer.com
 */

function handle_custom_route_request($request)
{
  $posts = get_posts(array(
    'post_type' => 'post',
    'posts_per_page' => -1,
  ));

  $formatted_posts = array_map(function ($post) {
    return array(
      'id' => $post->ID,
      'title' => $post->post_title,
      'content' => $post->post_content,
      'excerpt' => $post->post_excerpt,
      'slug' => $post->post_name,
      'date' => $post->post_date,
      'featured_image' => get_the_post_thumbnail_url($post->ID),
    );
  }, $posts);

  return $formatted_posts;
}

function custom_api_route()
{
  register_rest_route('cl/v1', 'posts', array(
    'methods' => 'GET',
    'callback' => 'handle_custom_route_request',
  ));
}
add_action('rest_api_init', 'custom_api_route');
