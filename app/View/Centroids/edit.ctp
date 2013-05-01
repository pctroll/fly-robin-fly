<div class="centroids form">
<?php echo $this->Form->create('Centroid'); ?>
	<fieldset>
		<legend><?php echo __('Edit Centroid'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('x');
		echo $this->Form->input('y');
		echo $this->Form->input('level');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Centroid.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Centroid.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Centroids'), array('action' => 'index')); ?></li>
	</ul>
</div>
