<?php

$testfile = '/home/oem/Downloads/rtcenterprises.WordPress.2019-08-10.xml';
/*
if ( file_exists( $testfile ) ) {
	// Read entire file into string
	$xmlfile = file_get_contents( $testfile );
	// Convert xml string into an object
	$xml = simplexml_load_string( $xmlfile );
#	$xml = simplexml_load_file( $testfile );
	print_r( $xml );
} else {
	exit( "Failed to open $testfile" );
}*/

#$reader = XMLReader::open( 'file://' . $testfile );
#print_r( $reader );

$doc = new DOMDocument();
$doc->load( 'file://' . $testfile );
print_r( $doc );
