<div class="slices form">
<?php echo $this->Form->create('Slice'); ?>
	<fieldset>
		<legend><?php echo __('Edit Slice'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('game_id');
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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Slice.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Slice.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Slices'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Games'), array('controller' => 'games', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
	</ul>
</div>
