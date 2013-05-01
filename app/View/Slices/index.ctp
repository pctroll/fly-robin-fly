<div class="slices index">
	<h2><?php echo __('Slices'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('game_id'); ?></th>
			<th><?php echo $this->Paginator->sort('shots'); ?></th>
			<th><?php echo $this->Paginator->sort('enemies_shot'); ?></th>
			<th><?php echo $this->Paginator->sort('enemies_avoided'); ?></th>
			<th><?php echo $this->Paginator->sort('rocks_shot'); ?></th>
			<th><?php echo $this->Paginator->sort('rocks_avoided'); ?></th>
			<th><?php echo $this->Paginator->sort('posteddate'); ?></th>
			<th><?php echo $this->Paginator->sort('class'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($slices as $slice): ?>
	<tr>
		<td><?php echo h($slice['Slice']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($slice['Game']['id'], array('controller' => 'games', 'action' => 'view', $slice['Game']['id'])); ?>
		</td>
		<td><?php echo h($slice['Slice']['shots']); ?>&nbsp;</td>
		<td><?php echo h($slice['Slice']['enemies_shot']); ?>&nbsp;</td>
		<td><?php echo h($slice['Slice']['enemies_avoided']); ?>&nbsp;</td>
		<td><?php echo h($slice['Slice']['rocks_shot']); ?>&nbsp;</td>
		<td><?php echo h($slice['Slice']['rocks_avoided']); ?>&nbsp;</td>
		<td><?php echo h($slice['Slice']['posteddate']); ?>&nbsp;</td>
		<td><?php echo h($slice['Slice']['class']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $slice['Slice']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $slice['Slice']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $slice['Slice']['id']), null, __('Are you sure you want to delete # %s?', $slice['Slice']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Slice'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Games'), array('controller' => 'games', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
	</ul>
</div>
