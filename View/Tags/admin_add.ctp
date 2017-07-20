<?php
// File: plugins/tags/View/Tags/admin_add.ctp
 ?>
<div class="top">
	<h1><?php echo __('Add Tags'); ?></h1>
</div>
<div class="center">
	<div class="form">
		<?php echo $this->Form->create('Tag');?>
		    <fieldset>
		        <legend><?php echo __('Add Tags'); ?></legend>
		    	<?php
					echo $this->Form->input('tags', array(
						'label' => __('Tags'), 
						'between' => __('(list of tags separated by comma)'),
					));
		    	?>
		    </fieldset>
		<?php echo $this->Form->end(__('Save Tags')); ?>
	</div>
</div>