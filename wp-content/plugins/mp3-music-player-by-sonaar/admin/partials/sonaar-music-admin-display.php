<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       sonaar.io
 * @since      1.0.0
 *
 * @package    Sonaar_Music
 * @subpackage Sonaar_Music/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="sonaar_music">
  <b-jumbotron class="text-center" bg-variant="dark" text-variant="white">
  <div class="logo"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ) . '../img/sonaar-music-logo-white.png')?>"></div>
  <div class="headertxt">
    <h1>settings</h1>
    <div><p class="text-center tagline">Change the overall look and feel of your MP3 Player</p></div>
  </div>
  </b-jumbotron>
 </div>