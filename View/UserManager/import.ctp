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

<h2>
	<?php echo __d('user_manager', 'Import title'); ?>
</h2>

<div class="well well-sm">
	<?php echo __d('user_manager', 'Import description'); ?>
</div>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create(false, array('type' => 'file')); ?>

	<div class="panel-body">
		<?php echo $this->NetCommonsForm->input('import_type', array(
			'type' => 'radio',
			'options' => array(
				ImportExportBehavior::IMPORT_TYPE_NEW => __d('user_manager', 'Error if have same datas.'),
				ImportExportBehavior::IMPORT_TYPE_UPDATE => __d('user_manager', 'Overwrite the same datas.'),
				ImportExportBehavior::IMPORT_TYPE_SKIP => __d('user_manager', 'Skip if have same datas.'),
			),
			'label' => __d('user_manager', 'Same datas'),
			'value' => ImportExportBehavior::IMPORT_TYPE_NEW
		)); ?>

		<?php echo $this->NetCommonsForm->input('import_csv', array(
			'type' => 'file',
			'class' => '',
			'label' => __d('user_manager', 'Import file'),
		)); ?>
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
