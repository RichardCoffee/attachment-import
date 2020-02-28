<?php

require_once( '/home/oem/work/php/wordpress/wp-cli.php' );
require_once( '/home/oem/work/php/attachment-import/vendor/wordpress-importer/parsers.php' );


$parser = new WXR_Parser();
$file   = '/home/oem/work/php/attachment-import/xml/rtcenterprises.WordPress.2019-08-10.xml';
$doc    = $parser->parse( $file );

#print_r( $doc );

$keys = array_keys( $doc );
print_r( $keys );

$posts = $doc['posts'];
$ids = explode( ',', "2200,2219,2205,2206,2210,2214,2215,2202,2207,2204,2209,2208,2220,2203,2212,2213,2201,2218,2211,2216,2217" );
$mine = array_filter(
	$posts,
	function( $a ) use ( $ids ) {
#		if ( $a['post_type'] === 'attachment' ) return true;
#		return false;
		if ( ! ( strpos( $a['guid'], 'wpthemetestdata.wordpress.com' ) === false ) ) return false;
		if ( ! ( strpos( $a['guid'], 'http://wptest.io/demo' ) === false ) ) return false;
		if ( ! ( $a['post_type'] === 'post' ) ) return false;
		if ( $a['post_type'] === 'attachment' ) return false;
		if ( $a['post_type'] === 'forum' ) return false;
		if ( $a['post_type'] === 'topic' ) return false;
		if ( $a['post_type'] === 'nav_menu_item' ) return false;
		if ( $a['post_type'] === 'page' ) return false;
		return true;
		if ( $a['post_type'] === 'attachment' ) {
			if ( in_array( $a['post_id'], $ids ) ) return true;
		}
		return false;
	}
);
print_r( $mine );
/*
print_r( $mine[232] );
$cont = $mine[232]['post_content'];
$arr1 = explode( '"', $cont );
$arr2 = explode( ',', $arr1[3] );
print_r( $arr2 );
*/
#$titles = array_column( $mine, 'post_title' );
#print_r( $titles );
