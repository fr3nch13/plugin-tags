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
 * Tags Controller
 *
 * @package tags
 * @subpackage tags.controllers
 */
class TagsController extends TagsAppController 
{

/**
 * Name
 *
 * @var string
 */
	public $name = 'Tags';

	public function tagged($model = false, $id = null) 
	{
	/**
	 * Lists out the tags for a given Item 
	 *
	 */
		$this->Prg->commonProcess();
		
		$model = Inflector::camelize(trim($model));	 	
	 	
	 	$conditions = array(
			'Tagged.model' => $model,
			'Tagged.foreign_key' => $id
		);
		
		$conditions = $this->Tag->Tagged->conditions($conditions, $this->passedArgs);
		
		$tags = $this->Tag->Tagged->find('all', array(
			'recursive' => 0,
			'conditions' => $conditions,
			'order' => array('Tag.name' => 'asc'),
		));
		
		if(isset($this->request->params['named']['getcount'])
		and $this->request->params['named']['getcount']
//		and $this->request->isAjax()
		)
		{
			$this->set('count', count($tags));
			return $this->render('Utilities./Elements/getcount', 'ajax_nodebug');
			exit;
		}
		
		$this->set('model', $model);
		$this->set('tags', $tags);
		
		return $this->render('tagged');
	}
	
	public function index() 
	{
		$this->Prg->commonProcess();
		
		// include just the user information
		$this->Tag->recursive = 0;
		$this->paginate['order'] = array('Tag.created' => 'desc');
		$this->paginate['conditions'] = $this->Tag->parseCriteria($this->passedArgs);
		$this->set('tags', $this->paginate());
	}
	
	public function view($keyName = null) 
	{
		$tag = $this->Tag->view($keyName, true);
		if (!$tag) 
		{
			throw new NotFoundException(__('Invalid %s', __('Tag')));
		}
		$this->set('tag', $tag);
	}
	
	public function autocomplete()
	{
	 	Configure::write('debug', 1);
	 	$this->autoRender = false;
	 	
	 	$query = (isset($_GET['query'])?trim($_GET['query']):'');
	 	$originalQuery = $query;
	 	if(stripos($query, ',') !== false)
	 	{
	 		$query = explode(',', $query);
	 		$query = array_pop($query);
	 		$query = trim($query);
	 	}
		
		$items = $this->Tag->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'Tag.name LIKE ' => $query. '%',
			),
		));
		
		$i = 0;
		$response = array('query' => $query, 'suggestions' => array());
		foreach($items as $item)
		{
			$response['suggestions'][$i]['value'] = $item['Tag']['name'];
			$response['suggestions'][$i]['data'] = $item['Tag'];
			$response['suggestions'][$i]['data']['original_query'] = $originalQuery;
			$i++;
		}
		echo json_encode($response);
	}
	
	public function tag($model = false, $model_id = false)
	{
	}
	
	public function manager_tagged($model = false, $id = null) 
	{
		return $this->tagged($model, $id);
	}

	public function admin_tagged($model = false, $id = null) 
	{
	/**
	 * Lists out the tags for a given Item 
	 *
	 */
		$this->Prg->commonProcess();
		
		$model = Inflector::camelize(trim($model));	 	
	 	
	 	$conditions = array(
			'Tagged.model' => $model,
			'Tagged.foreign_key' => $id
		);
		
		$conditions = $this->Tag->Tagged->conditions($conditions, $this->passedArgs);
		$tags = $this->Tag->Tagged->find('all', array(
			'recursive' => 0,
			'conditions' => $conditions,
			'order' => array('Tag.name' => 'asc'),
		));
		
		if(isset($this->request->params['named']['getcount'])
		and $this->request->params['named']['getcount']
//		and $this->request->isAjax()
		)
		{
			$this->set('count', count($tags));
			return $this->render('Utilities./Elements/getcount', 'ajax_nodebug');
			exit;
		}
		
		$this->set('model', $model);
		$this->set('tags', $tags);
	}
	
	public function admin_index() 
	{
	/**
	 * index method
	 *
	 * Displays all public Categories
	 */
		$this->Prg->commonProcess();
		
		// include just the user information
		$this->Tag->recursive = 0;
		$this->paginate['order'] = array('Tag.created' => 'desc');
		$this->paginate['conditions'] = $this->Tag->parseCriteria($this->passedArgs);
		$this->set('tags', $this->paginate());
	}
	
	public function admin_view($keyName = null) 
	{
		$tag = $this->Tag->view($keyName, true);
		if (!$tag) 
		{
			throw new NotFoundException(__('Invalid %s', __('Tag')));
		}
		
		$this->set('tag', $tag);
	}
	
	public function admin_add() 
	{
	/**
	 * add method
	 *
	 * @return void
	 */
		if ($this->request->is('post')) 
		{
			$this->Tag->create();
			
			if ($this->Tag->add($this->request->data)) 
			{
				$redirect = array('action' => 'view', $this->Tag->id);
				
				if($this->Category->sessionVectorId)
				{
					$redirect = $this->Category->sessionRedirect;
				}
				$this->Session->setFlash(__d('tags', 'The Tags have been saved.'));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('The Tag could not be saved. Please, try again.'));
			}
		}
	}
	
//
	public function admin_edit($id = null) 
	{
	/**
	 * edit method
	 *
	 * @param string $id
	 * @return void
	 */
		$this->Tag->id = $id;
		if (!$this->Tag->exists()) 
		{
			throw new NotFoundException(__('Invalid Tag'));
		}
		if ($this->request->is('post') || $this->request->is('put')) 
		{
			// modify the key_name, this is the only place it's allowed
			$full_name = $this->request->data['Tag']['identifier']. ': '. $this->request->data['Tag']['name'];
			$keyname = $this->Tag->multibyteKey($full_name);
			$this->request->data['Tag']['keyname'] = $keyname;
			
			if($existing_tag = $this->Tag->view($keyname))
			{
				if($existing_tag['Tag']['id'] != $this->request->data['Tag']['id'])
				{
					$this->Session->setFlash(__('A tag already exists with this name and identifier'));
					$this->render();
					return;
				}
			}
			
			if ($this->Tag->save($this->request->data)) 
			{
				$this->Session->setFlash(__('The Tag has been saved'));
				$this->redirect(array('action' => 'view', $keyname));
			}
			else
			{
				$this->Session->setFlash(__('The Tag could not be saved. Please, try again.'));
			}
		}
		else
		{
			$this->Tag->recursive = -1;
			$this->request->data = $this->Tag->read(null, $id);
		}
	}
	
	public function admin_delete($id = null) 
	{
		if ($this->Tag->delete($id)) 
		{
			$this->Session->setFlash(__d('tags', 'Tag deleted.'));
		} 
		else 
		{
			$this->Session->setFlash(__d('tags', 'Invalid Tag.'));
		}
		$this->redirect(array('action' => 'index'));
	}
}
