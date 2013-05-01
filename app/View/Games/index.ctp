<div class="games index">
	<h2><?php echo __('Games'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('time'); ?></th>
			<th><?php echo $this->Paginator->sort('energy'); ?></th>
			<th><?php echo $this->Paginator->sort('shots'); ?></th>
			<th><?php echo $this->Paginator->sort('enemies_shot'); ?></th>
			<th><?php echo $this->Paginator->sort('enemies_avoided'); ?></th>
			<th><?php echo $this->Paginator->sort('rocks_shot'); ?></th>
			<th><?php echo $this->Paginator->sort('rocks_avoided'); ?></th>
			<th><?php echo $this->Paginator->sort('posteddate'); ?></th>
			<th><?php echo $this->Paginator->sort('class'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($games as $game): ?>
	<tr>
		<td><?php echo h($game['Game']['id']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['time']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['energy']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['shots']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['enemies_shot']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['enemies_avoided']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['rocks_shot']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['rocks_avoided']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['posteddate']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['class']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $game['Game']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $game['Game']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $game['Game']['id']), null, __('Are you sure you want to delete # %s?', $game['Game']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Game'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Slices'), array('controller' => 'slices', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Slice'), array('controller' => 'slices', 'action' => 'add')); ?> </li>
	</ul>
</div>
