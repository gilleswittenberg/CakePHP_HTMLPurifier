<?php
/**
 * Purifier Component TestCase
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
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('PurifierComponent', 'HTMLPurifier.Controller/Component');

/**
 * TestPurifierController
 *
 * @package HTMLPurifier.Test
 * @author 	Gilles Wittenberg
 */
class TestPurifierController extends Controller {
}

/**
 * PurifierComponent TestCase
 *
 * @package HTMLPurifier.Test
 * @author 	Gilles Wittenberg
 */
class PurifierComponentTest extends CakeTestCase {

/**
 * PurifierComponent
 *
 * @var		PurifierComponent
 * @access	public
 */
	public $PurifierComponent = null;

/**
 * Controller
 *
 * @var		Controller
 * @access	public
 */
	public $Controller = null;

/**
 * Method executed before each test
 *
 * @return 	void
 * @access 	public
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->PurifierComponent = new PurifierComponent($Collection);
		$CakeRequest = new CakeRequest();
		$CakeResponse = new CakeResponse();
		$this->Controller = new TestPurifierController($CakeRequest, $CakeResponse);
		$this->PurifierComponent->startup($this->Controller);
	}

/**
 * Method executed after each test
 *
 * @return 	void
 * @access 	public
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->PurifierComponent);
		unset($this->Controller);
	}

/**
 * Test purifyArray
 *
 * @return 	void
 * @access 	public
 */
	public function testPurifyArray() {
		$this->assertInternalType('array', $this->PurifierComponent->purifyArray(array()));
	}

/**
 * Test purify
 *
 * @return 	void
 * @access 	public
 */
	public function testPurify() {
		$this->assertInternalType('string', $this->PurifierComponent->purify(''));
	}
}
