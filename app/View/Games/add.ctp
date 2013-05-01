<div class="games form">
<?php echo $this->Form->create('Game'); ?>
	<fieldset>
		<legend><?php echo __('Add Game'); ?></legend>
	<?php
		echo $this->Form->input('time');
		echo $this->Form->input('energy');
		echo $this->Form->input('shots');
		echo $this->Form->input('enemies_shot');
		echo $this->Form->input('enemies_avoided');
		echo $this->Form->input('rocks_shot');
		echo $this->Form->input('rocks_avoided');
		echo $this->Form->input('posteddate');
		echo $this->Form->input('class');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Games'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Slices'), array('controller' => 'slices', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Slice'), array('controller' => 'slices', 'action' => 'add')); ?> </li>
	</ul>
</div>
