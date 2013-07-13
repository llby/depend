<h2>Mapping Depends</h2>
<p class="pull-right lead">
<?php echo Html::anchor('depend/depend/dependup', 'Update'); ?> | 
<?php echo Html::anchor('depend/depend/dependchk', 'Check'); ?> | 
<?php echo Html::anchor('depend/depend', 'Entities　→'); ?>
</P>
<br>
<br>

<?php if ($entities): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th style="background-color:#D9EDF7">Name</th>
			<th style="background-color:#D9EDF7">Comment</th>
			<?php for ($i = 0;$i <= $depends_max; $i++): ?>
				<th style="background-color:#D9EDF7">
				<?php foreach ($dependcolumn as $dc): ?>
					<?php if ($dc->level == $i): ?>
						<?php echo Form::open('depend/depend/columnup'); ?>
							<?php echo Form::hidden('id', $dc->id); ?>
							<?php echo Form::input('name', $dc->name, array('class' => 'input-mini', 'placeholder' => 'name')); ?>
							<br>
							<?php echo Form::textarea('contents', $dc->contents, array('class' => 'input-mini', 'placeholder' => 'contents', 'rows' => 2)); ?>
							<br>
							<?php echo Form::submit('lf', '<', array('class' => 'btn btn-primary btn-mini')); ?>
							<?php echo Form::submit('up', '+', array('class' => 'btn btn-primary btn-mini')); ?>
							<?php echo Form::submit('dl', '-', array('class' => 'btn btn-primary btn-mini')); ?>
							<?php echo Form::submit('rg', '>', array('class' => 'btn btn-primary btn-mini')); ?>
						<?php echo Form::close(); ?>
						</th>
						<?php continue 2; ?>
					<?php endif ?>
				<?php endforeach ?>
				<?php echo Form::open('depend/depend/columnadd'); ?>
					<?php echo Form::hidden('level', $i); ?>
					<?php echo Form::input('name', '', array('class' => 'input-mini', 'placeholder' => 'name')); ?>
					<br>
					<?php echo Form::textarea('contents', '', array('class' => 'input-mini', 'placeholder' => 'contents', 'row' => 2)); ?>
					<br>
					<?php echo Form::submit('lf', '<', array('class' => 'btn btn-primary btn-mini')); ?>
					<?php echo Form::submit('submit', '+', array('class' => 'btn btn-primary btn-mini')); ?>
					<?php echo Form::submit('rg', '>', array('class' => 'btn btn-primary btn-mini')); ?>
				<?php echo Form::close(); ?>
				</th>
			<?php endfor; ?>
		</tr>
	</thead>
	<?php foreach ($entities as $key1 => $entity): ?>
	<tbody>
		<tr>
			<td><?php echo $entity->name; ?></td>
			<td>
				<?php if ( isset($err['entities'])): ?>
					<?php if ( isset($err['entities'][$key1]['errkind'])): ?>
						<p style="color:#b94a48">
						<?php if ($err['entities'][$key1]['errkind'] == 0): ?>
							<?php echo $entity->name; ?>が最上位レイヤーにありません<br>
							<?php echo $entity->name; ?>を、この行の最上位にしてください。
						<?php elseif ($err['entities'][$key1]['errkind'] == 1): ?>
							最上位レイヤーに<?php echo $entity->name; ?>意外のものがあります。<br>
							最上位レイヤーは、<?php echo $entity->name; ?>だけにしてください。
						<?php endif; ?>
						</p>
					<?php endif; ?>
				<?php endif; ?>
				<?php echo $entity->comment; ?>
			</td>
			<?php for ($i = 0;$i <= $depends_max; $i++): ?>
				<td>
				<?php foreach ($depends as $key2 => $depend): ?>
					<?php if ($entity->id == $depend->depend_entity_id): ?>
					<?php if ($i == $depend->level): ?>
						<?php if ( isset($err['depends'][$key2]['errkind'])): ?>
							<p style="color:#b94a48">
							<?php if ($err['depends'][$key2]['errkind'] == 0): ?>
								<?php echo $depend->entity->name; ?>のレイヤーが揃っていません<br>
								<?php echo $depend->entity->name; ?>のレイヤーを全て同じにしてください
							<?php endif; ?>
							</p>
						<?php endif; ?>
						<?php echo $depend->entity->name; ?>
						<?php echo Form::open('depend/depend/dependmv'); ?>
							<?php echo Form::hidden('id', $depend->id); ?>
							<?php echo Form::submit('dw', '<', array('class' => 'btn btn-primary btn-mini')); ?>
							<?php echo Form::submit('up', '>', array('class' => 'btn btn-primary btn-mini')); ?>
						<?php echo Form::close(); ?>
					<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
				</td>
			<?php endfor; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php else: ?>
<p>No Depends Map.</p>

<?php endif; ?>
<p>
   <?php echo Html::anchor('depend/depend/dependmk', 'Make new Depends Map', array('class' => 'btn btn-success', 'onclick' => "return confirm('Are you sure?')")); ?>

</p>

