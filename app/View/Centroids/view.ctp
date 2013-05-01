<div class="centroids view">
<h2><?php  echo __('Centroid'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($centroid['Centroid']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('X'); ?></dt>
		<dd>
			<?php echo h($centroid['Centroid']['x']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Y'); ?></dt>
		<dd>
			<?php echo h($centroid['Centroid']['y']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Level'); ?></dt>
		<dd>
			<?php echo h($centroid['Centroid']['level']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Centroid'), array('action' => 'edit', $centroid['Centroid']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Centroid'), array('action' => 'delete', $centroid['Centroid']['id']), null, __('Are you sure you want to delete # %s?', $centroid['Centroid']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Centroids'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Centroid'), array('action' => 'add')); ?> </li>
	</ul>
</div>
