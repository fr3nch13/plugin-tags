<?php 
// File: plugins/tags/View/Tags/index.ctp

$th = array(
	'Tag.name' => array('content' => __('Name'), 'options' => array('sort' => 'Tag.name')),
	'Tag.modified' => array('content' => __('Modified'), 'options' => array('sort' => 'Tag.modified')),
	'Tag.created' => array('content' => __('Created'), 'options' => array('sort' => 'Tag.created')),
	'actions' => array('content' => __('Actions'), 'options' => array('class' => 'actions')),
);

$td = array();

foreach ($tags as $i => $tag)
{
	$actions = array(
		$this->Html->link(__('View'), array('action' => 'view', $tag['Tag']['keyname'])),
	);
	if($this->Wrap->roleCheck(array('admin')))
	{
		$actions[] = $this->Html->link(__('Edit'), array('action' => 'edit', $tag['Tag']['id'], 'admin' => true));
		$actions[] = $this->Html->link(__('Delete'), array('action' => 'delete', $tag['Tag']['id']));
	}
	$actions = implode('', $actions);
		
	$td[$i] = array(
		$this->Html->link($tag['Tag']['name'], array('action' => 'view', $tag['Tag']['keyname'])),
		$this->Wrap->niceTime($tag['Tag']['modified']),
		$this->Wrap->niceTime($tag['Tag']['created']),
		array(
			$actions,
			array('class' => 'actions'),
		),
	);
}

echo $this->element('Utilities.page_index', array(
	'page_title' => __('Tags'),
	'th' => $th,
	'td' => $td,
));