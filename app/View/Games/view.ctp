<div class="games view">
<h2><?php  echo __('Game'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($game['Game']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Time'); ?></dt>
		<dd>
			<?php echo h($game['Game']['time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Energy'); ?></dt>
		<dd>
			<?php echo h($game['Game']['energy']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Shots'); ?></dt>
		<dd>
			<?php echo h($game['Game']['shots']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Enemies Shot'); ?></dt>
		<dd>
			<?php echo h($game['Game']['enemies_shot']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Enemies Avoided'); ?></dt>
		<dd>
			<?php echo h($game['Game']['enemies_avoided']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rocks Shot'); ?></dt>
		<dd>
			<?php echo h($game['Game']['rocks_shot']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rocks Avoided'); ?></dt>
		<dd>
			<?php echo h($game['Game']['rocks_avoided']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Posteddate'); ?></dt>
		<dd>
			<?php echo h($game['Game']['posteddate']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Class'); ?></dt>
		<dd>
			<?php echo h($game['Game']['class']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Game'), array('action' => 'edit', $game['Game']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Game'), array('action' => 'delete', $game['Game']['id']), null, __('Are you sure you want to delete # %s?', $game['Game']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Games'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Slices'), array('controller' => 'slices', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Slice'), array('controller' => 'slices', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Slices'); ?></h3>
	<?php if (!empty($game['Slice'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Game Id'); ?></th>
		<th><?php echo __('Shots'); ?></th>
		<th><?php echo __('Enemies Shot'); ?></th>
		<th><?php echo __('Enemies Avoided'); ?></th>
		<th><?php echo __('Rocks Shot'); ?></th>
		<th><?php echo __('Rocks Avoided'); ?></th>
		<th><?php echo __('Posteddate'); ?></th>
		<th><?php echo __('Class'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($game['Slice'] as $slice): ?>
		<tr>
			<td><?php echo $slice['id']; ?></td>
			<td><?php echo $slice['game_id']; ?></td>
			<td><?php echo $slice['shots']; ?></td>
			<td><?php echo $slice['enemies_shot']; ?></td>
			<td><?php echo $slice['enemies_avoided']; ?></td>
			<td><?php echo $slice['rocks_shot']; ?></td>
			<td><?php echo $slice['rocks_avoided']; ?></td>
			<td><?php echo $slice['posteddate']; ?></td>
			<td><?php echo $slice['class']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'slices', 'action' => 'view', $slice['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'slices', 'action' => 'edit', $slice['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'slices', 'action' => 'delete', $slice['id']), null, __('Are you sure you want to delete # %s?', $slice['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Slice'), array('controller' => 'slices', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
