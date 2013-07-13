<?php echo Form::open(); ?>

	<fieldset>
		<div class="container">
			<div class="span3">
				<?php echo Form::label('Name', 'name'); ?>

				<div class="input">
					<?php echo Form::input('name', Input::post('name', isset($entity) ? $entity->name : ''), array('class' => 'span3')); ?>

				</div>
			</div>
			<div class="span4">
				<?php echo Form::label('Comment', 'comment'); ?>

				<div class="input">
					<?php echo Form::textarea('comment', Input::post('comment', isset($entity) ? $entity->comment : ''), array('class' => 'span3', 'rows' => 2)); ?>

				</div>
			</div>
		</div>
		<div class="clearfix">
			<?php echo Form::label('呼び出し元', 'entitycaller'); ?>

			<div class="input">
				<?php echo Form::textarea('entitycaller', Input::post('entitycaller', isset($entity) ? $entity->entitycaller : ''), array('class' => 'span8', 'rows' => 4)); ?>

			</div>

			<?php if ( isset($entitycallers)): ?>
				<?php foreach ($entitycallers as $key => $caller): ?>
					<?php if ( isset( $caller['errkind'] )): ?>
						<?php if ( $caller['errkind'] == 0 ): ?>
							<p style="color:#b94a48">
								<?php echo $caller['name']; ?>は、
								Entityに登録されていません。
							</p>
						<?php endif ?>
					<?php endif ?>
				<?php endforeach; ?>
			<?php endif; ?>

		</div>
		<div class="actions">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>

		</div>
	</fieldset>
<?php echo Form::close(); ?>