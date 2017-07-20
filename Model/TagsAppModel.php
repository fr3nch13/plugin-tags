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
 * Tags Plugin AppModel
 *
 * @package tags
 */
App::uses('AppModel', 'Model');
class TagsAppModel extends AppModel 
{
	public $useTable = false;
	
	public function afterFind($results = array(), $primary = false)
	{
	/*
	 * See the app/Model/AppModel.php
	 */
		// get the defined counts for this record of it's associated records based on a passed condition
		if($this->getCounts !== false)
		{
			foreach($results as $i => $result)
			{	
				$_counts = array();
				foreach($this->getCounts as $alias => $counts)
				{
					foreach($counts as $count_key => $conditions)
					{
						$count_key = $alias.'.'.$count_key;
						$count = 0;
						// load the model
						if(!is_object($this->{$alias}))
						{
							App::uses($alias, 'Model');
							if(class_exists($alias)) $this->{$alias} = new $alias;
						}
						
						if(is_object($this->{$alias}))
						{
							$count = 0;
							if(isset($conditions['conditions']))
							{
								$count = $this->{$alias}->find('count', $conditions);
							}
							$count = ($count?$count:0);
						}
						$_counts[$count_key] = $count;
					}
				}
				$results[$i][$this->alias]['counts'] = $_counts;
			}
		}
		return parent::afterFind($results, $primary);
	}
/**
 * Customized paginateCount method
 *
 * @param array
 * @param integer
 * @param array
 * @return array
 */
	public function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$parameters = compact('conditions');
		if ($recursive != $this->recursive) {
			$parameters['recursive'] = $recursive;
		}
		if (isset($extra['type']) && isset($this->_findMethods[$extra['type']])) {
			$extra['operation'] = 'count';
			return $this->find($extra['type'], array_merge($parameters, $extra));
		} else {
			return $this->find('count', array_merge($parameters, $extra));
		}
	}
}
