<?php
/**
 * PurifierModel Fixture
 *
 * PHP 5
 *
 * Copyright 2012, Gilles Wittenberg (http://www.gilleswittenberg.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2012, Gilles Wittenberg
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * PurifiableModelFixture
 *
 * @package HTMLPurifier.Test
 * @author 	Gilles Wittenberg
 */
class TestPurifiableModelFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var 	array
 * @access	public
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'title' => array('type' => 'string', 'length' => 255, 'null' => false),
		'body' => 'text',
	);

/**
 * Records
 *
 * @var 	array
 * @access	public
 */
	public $records = array(
		array(
			'id' => 1,
			'title' => 'Dirty record',
			'body' => '<p>test</p><script>alert("XSS");</script>'
		)
	);
}
