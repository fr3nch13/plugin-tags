<?php

App::uses('AppHelper', 'View/Helper');

class TagHelper extends AppHelper 
{
	public $helpers = array(
		'Html' => array('className' => 'Utilities.HtmlExt'),
		'Form' => array('className' => 'Utilities.FormExt' ),
	);
	
	public function autocomplete($input_id = false, $options = array())
	{
		$defaults = array(
			'label' => __('Freeform Tags'),
			'path' => array(
				'plugin' => 'tags',
				'controller' => 'tags',
				'action' => 'autocomplete',
				'admin' => false,
			),
			'id' => 'tags_type_'. rand(0, 1000),
		);
		$defaults['description'] = __('Separate each %s with a comma(,).', Inflector::singularize($defaults['label']));
		$options = array_merge($defaults, $options);
		
		if(!$input_id) $input_id = 'tags';
		
		$this->Form->setEntity($input_id);
		$model = $this->Form->model();
		
		if(strpos($input_id, '.') === false)
		{
			$input_id = $model.'.'.$input_id;
		}
		
		$url = $this->Html->url($options['path']);
		$options['data-path'] = (is_array($options['path'])?$url:$options['path']);
		unset($options['path']);
		
		$out = $this->Form->input($input_id, $options);
		
		if(isset($options['update_tags']))
		{
			$input_id_parts = explode('.', $input_id);
			$field = array_pop($input_id_parts);
			array_push($input_id_parts, 'update_tags');
			array_push($input_id_parts, $field);
			$out .= $this->Form->input(implode('.', $input_id_parts), array(
				'type' => 'hidden',
				'value' => ($options['update_tags']?1:0),
			));
		}
		
		$script = '
		
		$(function() 
		{
			function extractLast( term ) 
			{
				return split( term ).pop();
			}
			function tagsSplit( val ) 
			{
				return val.split( /\s*,\s*/ );
			}
			
			$("#'.$options['id'].'")
				.bind( "keydown", function( event ) 
				{
					if ( event.keyCode === $.ui.keyCode.TAB && $( this ).data( "ui-autocomplete" ).menu.active )
					{
						event.preventDefault();
					}
				})
				.autocompleteUI({
					serviceUrl: $("#'.$options['id'].'").data("path"),
					minLength: 1,
					dataType: "json",
					focus: function() 
					{
						// prevent value inserted on focus
						return false;
					},
					onSelect: function( suggestion ) 
					{
						// build the value that should be in the input
						if(original = suggestion.data.original_query)
						{
							originals = tagsSplit(original);
							originals.pop();
							originals.push(suggestion.value);
							this.value = originals.join( ", " );
						}
						return false;
					}
				});
		});
		';
		
		$out .= $this->Html->scriptBlock($script, array('inline' => false));
		return $out;
	}
	
	public function linkTags($data = array())
	{
		if ($data) 
		{
			$tags = array();
			foreach($data as $tag)
			{
				$tagname = $tag['name'];
				if($tag['identifier'])
				{
					$tagname = $tag['identifier']. ': '. $tagname;
				}
				$tags[] = $this->Html->link($tagname, array('controller' => 'tags', 'action' => 'view', $tag['keyname'], 'admin' => false, 'plugin' => 'tags'));
 			}
			return implode(', ', $tags);
		}
		return '';
	}
}