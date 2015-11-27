<?php
/**
 * RolesRoomsUser edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create(false, array('type' => 'file')); ?>

	<div class="panel-body">
		<h4 class="text-info">
			<?php echo __d('user_manager', 'Import title'); ?>
		</h4>

		<p>
			<?php echo __d('user_manager', 'Import description'); ?>
		</p>

		<hr>

		<?php echo $this->NetCommonsForm->input('import_csv', array('type' => 'file', 'class' => '', 'label' => false)); ?>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				$this->NetCommonsHtml->url(array('action' => 'index'))
			); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>
