<div class="slices form">
<?php echo $this->Form->create('Slice'); ?>
	<fieldset>
		<legend><?php echo __('Add Slice'); ?></legend>
	<?php
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

		<li><?php echo $this->Html->link(__('List Slices'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Games'), array('controller' => 'games', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
	</ul>
</div>
