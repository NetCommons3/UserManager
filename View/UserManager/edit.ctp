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

<?php echo $this->element('UserManager.setting_tabs'); ?>
<?php echo $this->element('UserManager.subtitle'); ?>

<?php
	echo $this->MessageFlash->description(
		__d('user_manager', 'Input the user data, and press [OK].<br>Required items are marked by <strong class="text-danger h4">*</strong>.')
	);
?>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create('User', array('type' => 'file')); ?>

	<?php echo $this->element('Users.Users/edit_form', array('element' => 'UserManager.UserManager/render_edit_row')); ?>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				NetCommonsUrl::actionUrlAsArray(array('action' => 'index'))
			); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>

<?php if ($this->params['action'] === 'edit' && $canUserDelete) : ?>
	<?php echo $this->element('Users.Users/delete_form'); ?>
<?php endif;

