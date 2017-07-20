<?php
// File: plugins/tags/View/Tags/admin_edit.ctp
?>
<div class="top">
	<h1><?php echo __('Edit Tag'); ?></h1>
</div>

<div class="center">
	<div class="form">
		<?php echo $this->Form->create('Tag');?>
			<fieldset>
				<legend><?php echo __('Edit Tag'); ?></legend>
				<?php
					echo $this->Form->input('id', array(
							'type' => 'hidden'
							));
//					echo $this->Form->input('identifier');
					echo $this->Form->input('name');
		    	?>
		    </fieldset>
		<?php echo $this->Form->end(__('Save Tag')); ?>
	</div>
</div>