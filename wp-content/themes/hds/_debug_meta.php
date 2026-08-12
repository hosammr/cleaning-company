<?php
$wp_root = dirname( __DIR__, 3 );
require_once $wp_root . '/wp-load.php';

echo "<pre>\n";

echo "--- get_registered_meta_keys('post') ---\n";
$all = get_registered_meta_keys( 'post' );
foreach ( $all as $key => $config ) {
    if ( str_starts_with( $key, 'hds_' ) ) {
        echo "$key:\n";
        echo "  subtype: " . var_export( $config['subtype'] ?? 'NOT SET', true ) . "\n";
        echo "  show_in_rest: " . var_export( $config['show_in_rest'], true ) . "\n";
        echo "  type: " . var_export( $config['type'], true ) . "\n";
    }
}

echo "\n--- get_registered_meta_keys('post', 'hds_vacancy') ---\n";
$sub = get_registered_meta_keys( 'post', 'hds_vacancy' );
echo count( $sub ) . " keys\n";
foreach ( $sub as $key => $config ) {
    echo "  $key: " . var_export( $config['type'], true ) . "\n";
}

echo "\n--- REST controller meta ---\n";
$controller = new WP_REST_Posts_Controller( 'hds_vacancy' );
$schema = $controller->get_item_schema();
$meta = $schema['properties']['meta'] ?? 'NO META';
echo var_export( array_keys( $schema['properties'] ), true ) . "\n";
if ( is_array( $meta ) ) {
    echo "Meta properties: " . var_export( array_keys( $meta['properties'] ?? [] ), true ) . "\n";
}

echo "</pre>";
