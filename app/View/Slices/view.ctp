<div class="slices view">
<h2><?php  echo __('Slice'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($slice['Slice']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Game'); ?></dt>
		<dd>
			<?php echo $this->Html->link($slice['Game']['id'], array('controller' => 'games', 'action' => 'view', $slice['Game']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Shots'); ?></dt>
		<dd>
			<?php echo h($slice['Slice']['shots']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Enemies Shot'); ?></dt>
		<dd>
			<?php echo h($slice['Slice']['enemies_shot']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Enemies Avoided'); ?></dt>
		<dd>
			<?php echo h($slice['Slice']['enemies_avoided']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rocks Shot'); ?></dt>
		<dd>
			<?php echo h($slice['Slice']['rocks_shot']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rocks Avoided'); ?></dt>
		<dd>
			<?php echo h($slice['Slice']['rocks_avoided']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Posteddate'); ?></dt>
		<dd>
			<?php echo h($slice['Slice']['posteddate']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Class'); ?></dt>
		<dd>
			<?php echo h($slice['Slice']['class']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Slice'), array('action' => 'edit', $slice['Slice']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Slice'), array('action' => 'delete', $slice['Slice']['id']), null, __('Are you sure you want to delete # %s?', $slice['Slice']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Slices'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Slice'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Games'), array('controller' => 'games', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
	</ul>
</div>
