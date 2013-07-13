<h2>Viewing #<?php echo $entity->id; ?></h2>

<p>
	<strong>Name:</strong>
	<?php echo $entity->name; ?></p>
<p>
	<strong>Comment:</strong>
	<?php echo $entity->comment; ?></p>

<table class="table table-striped">
	<thead>
		<tr>
			<th>呼び出し元</th><th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($entity->entitycaller as $key => $caller): ?>
		<tr>
			<td><?php echo $caller->name; ?></td>
			<td>
			<?php if ( isset($err['entitycallers'][$key]['errkind'])): ?>
				<?php if ( $err['entitycallers'][$key]['errkind'] == 0 ): ?>
					<p style="color:#b94a48">Entityに登録されていません。</p>
				<?php endif ?>
			<?php endif ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php echo Html::anchor('depend/depend/edit/'.$entity->id, 'Edit'); ?> |
<?php echo Html::anchor('depend/depend', 'Back'); ?>
