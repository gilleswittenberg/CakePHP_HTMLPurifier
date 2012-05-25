<?php
/**
 * Purifier Component
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
 * PurifierComponent
 *
 * This component wraps some functionality of HTMLPurifierWrapper
 *
 * @package HTMLPurifier
 * @author 	Gilles Wittenberg
 */
class PurifierComponent extends Component {

/**
 * HTMLPurifierWrapper instance reference
 *
 * @var		HTMLPurifierWrapper
 * @access	protected
 */
	protected $_HTMLPurifierWrapper = null;

/**
 * Startup
 *
 * @param	Controller $controller
 * @param	array $settings
 * @return	void
 * @access	public
 */
	public function startup(Controller $controller, $settings = null) {
		$this->_HTMLPurifierWrapper = new HTMLPurifierWrapper($settings);
	}

/**
 * Facade to HTMLPurifierWrapper purifyArray method
 *
 * @param 	array $arr
 * @return 	array
 * @access 	public
 */
	public function purifyArray($arr) {
		return $this->_HTMLPurifierWrapper->purifyArray($arr);
	}

/**
 * Facade to HTMLPurifierWrapper purify method
 *
 * @param 	string $str
 * @return 	string
 * @access 	public
 */
	public function purify($str) {
		return $this->_HTMLPurifierWrapper->purify($str);
	}
}
