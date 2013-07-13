<h2>Editing Entity</h2>
<br>

<?php echo render('_form', $err); ?>

<p>
	<?php echo Html::anchor('depend/depend/view/'.$entity->id, 'View'); ?> |
	<?php echo Html::anchor('depend/depend', 'Back'); ?></p>
