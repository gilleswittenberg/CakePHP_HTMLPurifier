<?php
/**
 * HTMLPurifierWrapper TestCase
 *
 * PHP 5
 *
 * Copyright 2012, Gilles Wittenberg (http://www.gilleswittenberg.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) 2012, Gilles Wittenberg
 * @license	MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('HTMLPurifierWrapper', 'HTMLPurifier.Lib');

/**
 * Test case for HTMLPurifierWrapper
 *
 * @package	HTMLPurifier.Test
 * @author	Gilles Wittenberg
 */
class HTMLPurifierWrapperTest extends CakeTestCase {

/**
 * Method executed before each test
 *
 * @return 	void
 * @access 	public
 */
	public function startTest() {
		parent::setUp();
		$this->HTMLPurifierWrapper = new HTMLPurifierWrapper();
	}

/**
 * Method executed after each test
 *
 * @return 	void
 * @access 	public
 */
	public function endTest() {
		parent::tearDown();
	}

/**
 * Test purify with empty config
 *
 * @return 	void
 * @access 	public
 */
	public function testPurifyEmptyConfig() {
		$this->HTMLPurifierWrapper->configure();
		$this->assertEquals('<p class="test">test</p>', $this->HTMLPurifierWrapper->purify('<p class="test">test</p><script>'));
	}

/**
 * Test purify with HTML Doctype
 *
 * @return 	void
 * @access 	public
 */
	public function testPurifyConfigHTML() {
		$config = array(
			'HTML' => array(
				'Doctype' => 'XHTML 1.0 Strict'
			)
		);
		$this->HTMLPurifierWrapper->configure($config);
		$this->assertEquals('<p>test<br /></p>', $this->HTMLPurifierWrapper->purify('<p>test<br></p><script>'));
	}

/**
 * Test purify with Attr AllowedClasses
 *
 * @return 	void
 * @access 	public
 */
	public function testPurifyConfigAttr() {
		$config = array(
			'Attr' => array(
				'AllowedClasses' => array()
			)
		);
		$this->HTMLPurifierWrapper->configure($config);
		$this->assertEquals('<p>test</p>', $this->HTMLPurifierWrapper->purify('<p class="test">test</p><script>'));
	}

/**
 * Test purify with Youtube Filter
 *
 * @return 	void
 * @access 	public
 */
	public function testPurifyWithYoutubeFilter() {
		$config = array(
			'Filter' => array(
				'HTMLPurifier_Filter_Youtube'
			)
		);
		$youtubeObject = '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/nto6EvPFO0Q /><param name="wmode" value="transparent" /><embed src="http://www.youtube.com/v/nto6EvPFO0Q" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350" /></object>';
		$this->HTMLPurifierWrapper->configure($config);
		$result = $this->HTMLPurifierWrapper->purify($youtubeObject . '<script>alert("XSS");</script>');
		$this->assertEquals('</object>', substr($result, -9));
	}

/**
 * Test purify with Youtube Filter and prepending namespace
 *
 * @return 	void
 * @access 	public
 */
	public function testPurifyWithYoutubeFilterPrependingNamespace() {
		$config = array(
			'Filter' => array(
				'Youtube'
			)
		);
		$youtubeObject = '<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/nto6EvPFO0Q /><param name="wmode" value="transparent" /><embed src="http://www.youtube.com/v/nto6EvPFO0Q" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350" /></object>';
		$this->HTMLPurifierWrapper->configure($config);
		$result = $this->HTMLPurifierWrapper->purify($youtubeObject . '<script>alert("XSS");</script>');
		$this->assertEquals('</object>', substr($result, -9));
	}

/**
 * Test purifyArray
 *
 * @return 	void
 * @access	public
 */
	public function testPurifyArray() {
		$str = '<p>test</p><script>alert("XSS");</script>';
		$expectedStr = '<p>test</p>';
		$arr = array(
			'first' => $str,
			'second' => array(
				$str, $str
			)
		);
		$result = $this->HTMLPurifierWrapper->purifyArray($arr);
		$this->assertEquals($expectedStr, $result['first']);
		$this->assertEquals($expectedStr, $result['second'][0]);
		$this->assertEquals($expectedStr, $result['second'][1]);
	}
}
