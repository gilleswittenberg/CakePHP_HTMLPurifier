<?php
/**
 * Purifiable Behavior
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

App::uses('Set', 'Utility');
App::uses('HTMLPurifierWrapper', 'HTMLPurifier.Lib');

/**
 * Purifiable Behavior
 *
 * Purifies data from model
 *
 * @package		HTMLPurifier
 * @author 		Gilles Wittenberg
 */
class PurifiableBehavior extends ModelBehavior {

/**
 * Settings array
 *
 * @var 	array
 * @access 	public
 */
	public $settings = array();

/**
 * Default configuration settings
 *
 * @var 	array
 * @access 	protected
 */
	protected $_settings = array(
		'callback' => 'beforeSave',
		'fields' => array(),
		'overwrite' => false,
		'affix' => '_clean',
		'affix_position' => 'suffix',
		'HTMLPurifier' => array(),
	);

/**
 * Array holding HTMLPurifier instances
 *
 * @var 	array
 * @access 	protected
 */
	protected $_HTMLPurifierWrappers = array();

/**
 * Setup
 *
 * Setup Purifiable with the specified configuration settings.
 *
 * @param 	Model $model Model using Purifiable
 * @param 	array $config Configuration settings for $model
 * @return 	void
 * @access 	public
 */
	public function setup(Model $Model, $config = array()) {
		$this->settings[$Model->alias] = Set::merge($this->_settings, $config);
		if (is_string($this->settings[$Model->alias]['fields'])) {
			$this->settings[$Model->alias]['fields'] = array($this->settings[$Model->alias]['fields']);
		}
		$this->_HTMLPurifierWrappers[$Model->alias] = new HTMLPurifierWrapper($this->settings[$Model->alias]['HTMLPurifier']);
	}

/**
 * BeforeValidate callback
 *
 * @param 	Model $Model Model using Purifiable
 * @return 	boolean True
 * @access 	public
 */
	public function beforeValidate(Model $Model) {
		if ($this->settings[$Model->alias]['callback'] == 'beforeValidate') {
			$Model->data = $this->_purifyData($Model->alias, $Model->data);
		}
		return true;
	}

/**
 * BeforeSave callback
 *
 * @param 	Model $Model Model using Purifiable
 * @return 	boolean true
 * @access 	public
 */
	public function beforeSave(Model $Model) {
		if ($this->settings[$Model->alias]['callback'] == 'beforeSave') {
			$Model->data = $this->_purifyData($Model->alias, $Model->data);
		}
		return true;
	}

/**
 * AfterFind callback
 *
 * @param 	Model $Model Model using Purifiable
 * @param 	array $data Model data
 * @return 	boolean true
 * @access 	public
 */
	public function afterFind(Model $Model, $data) {
		if ($this->settings[$Model->alias]['callback'] == 'afterFind') {
			foreach ($data as $key => $value) {
				$data[$key] = $this->_purifyData($Model->alias, $value);
			}
		}
		return $data;
	}

/**
 * Purify Model data
 *
 * @param 	string $alias
 * @param 	array $data
 * @return 	void
 * @access 	protected
 */
	protected function _purifyData($alias, $data) {
		foreach ($this->settings[$alias]['fields'] as $fieldName) {
			if (!isset($data[$alias][$fieldName]) || empty($data[$alias][$fieldName])) {
				continue;
			}
			$purifiedField = $this->_HTMLPurifierWrappers[$alias]->purify($data[$alias][$fieldName]);
			if (!$this->settings[$alias]['overwrite']) {
				$affix = $this->settings[$alias]['affix'];
				if ($this->settings[$alias]['affix_position'] === 'prefix') {
					$fieldName = $affix . $fieldName;
				} else {
					$fieldName = $fieldName . $affix;
				}
			}
			$data[$alias][$fieldName] = $purifiedField;
		}
		return $data;
	}

/**
 * Purify string
 *
 * @param 	Model $Model Model using Purifiable
 * @param 	string $str String to be purified
 * @return 	string Purified string
 * @access 	public
 */
	public function purify(Model $Model, $str) {
		return $this->_HTMLPurifierWrappers[$Model->alias]->purify($str);
	}
}
