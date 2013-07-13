<h2>Listing Entities</h2>
<p class="pull-right lead">
<?php echo Html::anchor('depend/depend/depend', 'Depends　→'); ?>
</p>
<br>
<br>

<?php if ($entities): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>Comment</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($entities as $entity): ?>
		<tr>
			<td><?php echo $entity->name; ?></td>
			<td><?php echo $entity->comment; ?></td>
			<td>
				<?php echo Html::anchor('depend/depend/view/'.$entity->id, 'View'); ?> |
				<?php echo Html::anchor('depend/depend/edit/'.$entity->id, 'Edit'); ?> |
				<?php echo Html::anchor('depend/depend/delete/'.$entity->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php else: ?>
<p>No Entities.</p>

<?php endif; ?>
<p>
	<?php echo Html::anchor('depend/depend/create', 'Add new Entity', array('class' => 'btn btn-success')); ?>

</p>

