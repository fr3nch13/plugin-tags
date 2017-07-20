<?php
/**
 * Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Tag model
 *
 * @package tags
 * @subpackage tags.models
 */
 
App::uses('TagsAppModel', 'Tags.Model');
class Tag extends TagsAppModel 
{

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Tag';
	
	public $useTable = 'tags';
	
	public $virtualFields = array(
		'full_name' => 'CONCAT(Tag.identifier, ": ", Tag.name)'
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Tagged' => array(
			'className' => 'Tags.Tagged',
			'foreignKey' => 'tag_id'
		),
	);

/**
 * HABTM associations
 *
 * @var array $hasAndBelongsToMany
 */
	public $hasAndBelongsToMany = array();

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array('rule' => 'notBlank'),
		'keyname' => array('rule' => 'notBlank'),
	);
	
	// define the fields that can be searched
	public $searchFields = array(
		'Tag.name',
	);

	public function getItem($model, $id)
	{
		$item = false;
		
		$this->getModel($model);
		
		if(is_object($this->{$model}))
		{
			$this->{$model}->recursive = -1;
			$item = $this->{$model}->read(null, $id);
		}
		return $item;
	}

	public function getModel($model)
	{
		$item = false;
		
		if(!is_object($this->{$model}))
		{
			App::uses($model, 'Model');
			
			if(class_exists($model))
			{
				$this->{$model} = new $model;
				return true;
			}
			return false;
		}
		
		return true;
		
		return $item;
	}

/**
 * Returns the data for a single tag
 *
 * @param string keyname
 * @return array
 */
	public function view($keyName = null, $getCounts = false) 
	{
		$result = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.keyname' => $keyName)
			)
		);
		$result['Tag']['models'] = $this->Tagged->find('list', array(
			'conditions' => array('Tagged.tag_id' => $result['Tag']['id']),
			'order' => array('Tagged.model' => 'asc'),
			'fields' => array('Tagged.model', 'Tagged.model'),
		));
		
		return $result;
	}


/**
 * Pre-populates the tag table with entered tags
 *
 * @param array post data, should be Contoller->data
 * @return boolean
 */
	public function add($postData = null) {
		if (isset($postData[$this->alias]['tags'])) {
			$this->Behaviors->attach('Tags.Taggable', array(
				'resetBinding' => true,
				'automaticTagging' => false));
			$this->Tag = $this;
			$result = $this->saveTags($postData[$this->alias]['tags'], false, false);
			unset($this->Tag);
			$this->Behaviors->detach('Tags.Taggable');
			return $result;
		}
	}
	
	public function multibyteKey($string = null) {
		$str = strtolower($string);
		$str = trim($str);
		$str = preg_replace('/\:\s+/', ' ', $str);
		$str = preg_replace('/\xE3\x80\x80/', ' ', $str);
		$str = str_replace(array(' ', ':', '-'), '_', $str);
		$str = preg_replace( '#[:\#\*"()~$^{}`@+=;,<>!&%\.\]\/\'\\\\|\[]#', "\x20", $str );
		$str = str_replace('?', '', $str);
		$str = trim($str);
		$str = preg_replace('#\x20+#', '', $str);
		return $str;
	}
}
