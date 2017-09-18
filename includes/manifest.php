<?php

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );

/** Delete Empty value **/
$manifest = array_filter(get_option( 'manifest' ), function($value) { return $value !== ''; });
/** Booleean as bool() **/

if (isset($manifest["prefer_related_applications"])) {
  $manifest["prefer_related_applications"] = filter_var($manifest["prefer_related_applications"], FILTER_VALIDATE_BOOLEAN);
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($manifest,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
