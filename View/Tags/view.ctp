<?php 
// File: plugins/tags/View/Tags/view.ctp
$details = array();
//$details[] = array('name' => __('Identifier'), 'value' => $tag['Tag']['identifier']);
$details[] = array('name' => __('Name'), 'value' => $tag['Tag']['name']);
$details[] = array('name' => __('Created'), 'value' => $this->Wrap->niceTime($tag['Tag']['created']));
//$details[] = array('name' => __('Modified'), 'value' => $this->Wrap->niceTime($tag['Tag']['modified']));

$stats = array();
$tabs = array();
$tbcnt = 0;
foreach($tag['Tag']['models'] as $model)
{
	$tbcnt++;
	$model_underscore = Inflector::underscore($model);
	$model_underscore_plural = Inflector::pluralize($model_underscore);
	$model_nice = Inflector::humanize($model_underscore);
	$model_nice_plural = Inflector::pluralize($model_nice);
	$stats[$model] = array(
		'id' => $model,
		'name' => __($model_nice_plural),
		'ajax_count_url' => array('controller' => $model_underscore_plural, 'action' => 'tag', $tag['Tag']['id'], 'plugin' => false),
		'tab' => array('tabs', (count($tabs) + 1)), // the tab to display
	);
	$tabs[$model] = array(
		'key' => $model,
		'title' => __($model_nice_plural),
		'url' => array('controller' => $model_underscore_plural, 'action' => 'tag', $tag['Tag']['id'], 'plugin' => false),
	);
}

echo $this->element('Utilities.page_view', array(
	'page_title' => __('%s: %s', __('Tag'), ($tag['Tag']['full_name']?$tag['Tag']['full_name']:$tag['Tag']['name'])),
	'details' => $details,
	'stats' => $stats,
	'tabs_id' => 'tabs',
	'tabs' => $tabs,
));