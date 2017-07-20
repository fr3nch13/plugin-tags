<?php 
// File: plugins/tags/View/Tags/admin_tagged.ctp

$items = array();

foreach ($tags as $i => $tag)
{
	$items[$i] = array(
		'title' => $tag['Tag']['name'],
		'url' => array('action' => 'view', $tag['Tag']['keyname']),
	);
}

echo $this->element('Utilities.page_cloud', array(
	'page_title' => __('Tags'),
	'items' => $items,
	));
?>

<?php
// include any scripts that would be created for things like pagination
echo $this->Js->writeBuffer();
?>

	<?php 
		if(Configure::read('debug') > 0)
		{
			echo $this->element('sql_dump'); 
		}
?>