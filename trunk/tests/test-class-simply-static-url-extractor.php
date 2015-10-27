<?php
/**
 * @package Simply_Static\Unit_tests
 */
class Simply_Static_Url_Extractor_Test extends WP_UnitTestCase {

	/**
	 * Set the test domain
	 */
	const DOMAIN = 'http://example.org';

	/**
	 * Set the test URL
	 */
	const URL = 'http://example.org/blog/my-first-blog-post';

	/**
	 * Helper function for creating extractors
	 */
	function build_extractor( $content_type, $body, $url = self::URL ) {
		return Simply_Static_Url_Extractor_Factory::build( $content_type, $body, $url );
	}

	/**
	 * Placeholder test to prevent PHPUnit from throwing errors
	 */
	public function test_class_is_tested() {
		$this->assertTrue( true );
	}

	/**
	 * Testing for URL extraction on increasingly more malformed link href values.
	 */
	public function test_extract_urls_with_html_links() {

		$content_and_urls = array(
			// basic relative url
			"<a href='/one.htm'>one</a>"
				=> self::DOMAIN . '/one.htm',
			// extra spacing around href attribute
			"<a href='    /two.htm  '>two</a>"
				=> self::DOMAIN . '/two.htm',
			// no quotes around href attribute
			"<a href=three.htm>three</a>"
				=> self::DOMAIN . '/blog/three.htm',
			// support for ../ links
			'<a href="../four.htm">four</a>'
				=> self::DOMAIN . '/four.htm',
			// support for ./ links
			'<a href=./five.htm>five</a>'
				=> self::DOMAIN . '/blog/five.htm',
			// extra spacing between href name and value
			'<a href = six.htm>six</a>'
				=> self::DOMAIN . '/blog/six.htm',
			// space in url
			"<a href='file seven.pdf'>seven</a>"
				=> self::DOMAIN . '/blog/file seven.pdf',
			// This fails:
			// space in url and no quotes
			//'<a href=file eight.pdf>eight</a>'
			//	=> self::DOMAIN . '/blog/file eight.pdf'
			//	multiple attributes with no spacing
			'<a href=nine.htm test=test>nine</a>'
				=> self::DOMAIN . '/blog/nine.htm',
			// relative url with path
			"<a href='/path/ten.htm'>ten</a>"
				=> self::DOMAIN . '/path/ten.htm',
			// query params get striped out
			"<a href='/11.htm?test=true'>11</a>"
				=> self::DOMAIN . '/11.htm',
			// fragments get striped out
			"<a href='/12.htm#test'>12</a>"
				=> self::DOMAIN . '/12.htm',
			// non-standard casing
			"<A HRef='/THIRTEEN.htm'>13</a>"
				=> self::DOMAIN . '/THIRTEEN.htm',
			// absolute url
			"<a href='" . self::DOMAIN . "/14'>14</a>"
				=> self::DOMAIN . '/14',
			// absolute url with fragment only
			"<a href='" . self::DOMAIN . "#section15'>15</a>"
				=> self::DOMAIN,
			// absolute url with path and fragment
			"<a href='" . self::DOMAIN . "/test#section16'>16</a>"
				=> self::DOMAIN . '/test',
			// absolute url with path
			"<a href='" . self::DOMAIN . "/test/17.htm'>17</a>"
				=> self::DOMAIN . '/test/17.htm',
			// external urls don't get included
			"<a href='http://www.external.com/18.htm'>18</a>"
				=> false
		);

		foreach ( $content_and_urls as $content => $url ) {

			$extractor = $this->build_extractor( 'html', $content );
			$extracted_url = array_shift( $extractor->extract_urls() );
			$this->assertEquals( $url, $extracted_url );

		}

	}

	/**
	 * Test URL extraction on other types of HTML elements.
	 */
	public function test_extract_urls_with_other_html_elements() {

		$content_and_urls = array(
			"<link rel='stylesheet' id='test-css'  href='/test.css' type='text/css' media='all' />"
				=> self::DOMAIN . '/test.css',
			"<link href='//fonts.googleapis.com/css?family=Judson:400,400italic,700' rel='stylesheet' type='text/css'>"
				=> false,
			'<applet codebase="/classes"></applet>'
				=> self::DOMAIN . '/classes',
			'<area shape="rect" coords="0,0,10,10" href="sun.htm">'
				=> self::DOMAIN . '/blog/sun.htm',
			'<ins cite="why.htm">Cited text.</ins>'
				=> self::DOMAIN . '/blog/why.htm',
			'<!-- <ins cite="why.htm">Cited text.</ins> -->'
				=> false
		);

		foreach ( $content_and_urls as $content => $url ) {

			$extractor = $this->build_extractor( 'html', $content );
			$extracted_url = array_shift( $extractor->extract_urls() );
			$this->assertEquals( $url, $extracted_url );

		}

	}

	/**
	 * Test URL extraction on HTML elements with multiple attributes (possibly)
	 * containing URLs.
	 */
	public function test_extract_urls_with_multiple_html_attributes() {

		$content_and_urls = array(
			'<iframe src="/default.asp" longdesc="w3s.txt"></iframe>' => array(
				self::DOMAIN . '/default.asp',
				self::DOMAIN . '/blog/w3s.txt' ),
			'<img src="w3html.gif" alt="W3Schools.com" longdesc="w3html.txt">' => array(
				self::DOMAIN . '/blog/w3html.gif',
				self::DOMAIN . '/blog/w3html.txt' ),
			'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="/swflash.cab#version=9,0,28,0">' => array(
				self::DOMAIN . '/swflash.cab' ),
			'<OBJECT CLASSID="/yahtzee.py" CODETYPE="application/x-python" TITLE="My Yahtzee Game"></OBJECT>' => array(
				self::DOMAIN . '/yahtzee.py' ),
			'<video controls src="movie.ogg" poster="/images/w3html5.gif"></video>' => array(
				self::DOMAIN . '/blog/movie.ogg',
				self::DOMAIN . '/images/w3html5.gif' )
		);

		foreach ( $content_and_urls as $content => $urls ) {

			$extractor = $this->build_extractor( 'html', $content );
			$extracted_urls = $extractor->extract_urls();

			foreach ( $extracted_urls as $extracted_url ) {
				$this->assertTrue( in_array( $extracted_url, $urls ) );
			}

		}

	}

	// TODO: Not presently handling delimited attributes.
	// /**
	//  * Test URL extraction on HTML elements with attributes containing space or
	//  * comma delimited lists of URLs.
	//  */
	// public function test_extract_urls_with_delimited_attributes() {

	// 	$content_and_urls = array(
	// 		'<object classid="java:giant-dog.class" archive="/giant-dog.jar /giant-dog2.jar /giant-dog3.jar"></object>' => array(
	// 			self::DOMAIN . '/giant-dog.jar',
	// 			self::DOMAIN . '/giant-dog2.jar',
	// 			self::DOMAIN . '/giant-dog3.jar' ),
	// 		'<applet archive="/archive/myjar.jar,/archive/myjar2.jar,/archive/myjar3.jar"></applet>' => array(
	// 			self::DOMAIN . '/archive/myjar.jar',
	// 			self::DOMAIN . '/archive/myjar2.jar',
	// 			self::DOMAIN . '/archive/myjar3.jar' )
	// 	);

	// 	foreach ( $content_and_urls as $content => $urls ) {

	// 		$extractor = $this->build_extractor( 'html', $content );
	// 		$extracted_urls = $extractor->extract_urls();

	// 		foreach ( $extracted_urls as $extracted_url ) {
	// 			$this->assertTrue( in_array( $extracted_url, $urls ) );
	// 		}

	// 	}

	// }

	/**
	 * Test URL extraction on CSS style attributes on HTML elements.
	 */
	public function test_extract_urls_with_css_style_attributes() {

		$content_and_urls = array(
			'<body style=\'background: #00ff00 url("/smiley.gif") no-repeat fixed center;\'></body>'
				=> self::DOMAIN . '/smiley.gif',
			"<div style='background-image: url(\"/one.png\")'></div>"
				=> self::DOMAIN . '/one.png',
			"<div STYLE='background-IMAGE: url(/two.png)'></div>"
				=> self::DOMAIN . '/two.png',
			"<div style=\"background-image: url('/three.png')\"></div>"
				=> self::DOMAIN . '/three.png'
		);

		foreach ( $content_and_urls as $content => $url ) {

			$extractor = $this->build_extractor( 'html', $content );
			$extracted_url = array_shift( $extractor->extract_urls() );
			$this->assertEquals( $url, $extracted_url );

		}

	}

	/**
	 * Test URL extraction on CSS style blocks on HTML pages.
	 */
	public function test_extract_urls_with_css_style_blocks() {

		$content_and_urls = array(
			'<STYLE> @import url("/import.css") print; </STYLE>'
				=> self::DOMAIN . '/import.css',
			"<style> div { background-image: url(/image-one.png); } </style>"
				=> self::DOMAIN . '/image-one.png'
		);

		foreach ( $content_and_urls as $content => $url ) {

			$extractor = $this->build_extractor( 'html', $content );
			$extracted_url = array_shift( $extractor->extract_urls() );
			$this->assertEquals( $url, $extracted_url );

		}

	}

	/**
	 * Test URL extraction on CSS files.
	 */
	public function test_extract_urls_on_css_files() {

		$content_and_urls = array(
			'@import url("./one.css") print;'
				=> self::DOMAIN . '/blog/one.css',
			'@import url("../two.css") projection, tv;'
				=> self::DOMAIN . '/two.css',
			'@import \'three.css\';'
				=> self::DOMAIN . '/blog/three.css',
			'@import "./four.css" print;'
				=> self::DOMAIN . '/blog/four.css',
			'@import url("chrome://five/");'
				=> false,
			'@import url(\'six.css\') screen and (orientation:landscape);'
				=> self::DOMAIN . '/blog/six.css',
			'.seven { background-image: url(seven.png); }'
				=> self::DOMAIN . '/blog/seven.png',
			'.eight { background-image: url("eight.png"); }'
				=> self::DOMAIN . '/blog/eight.png',
			'.nine { background-image: url(  \'nine.png\'  ); }'
				=> self::DOMAIN . '/blog/nine.png'
		);

		foreach ( $content_and_urls as $content => $url ) {

			$extractor = $this->build_extractor( 'css', $content );
			$extracted_url = array_shift( $extractor->extract_urls() );
			$this->assertEquals( $url, $extracted_url );

		}

	}

}
