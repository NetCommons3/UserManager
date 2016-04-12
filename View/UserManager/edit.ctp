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

<?php echo $this->element('UserManager.subtitle'); ?>
<?php echo $this->element('UserManager.setting_tabs'); ?>
<?php echo $this->MessageFlash->description(__d('user_manager', 'Input the user data, and press &#039;OK&#039; button.<br>Required items are marked by <strong class="text-danger h4">*</strong>.')); ?>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create('User', array('type' => 'file')); ?>

	<?php echo $this->element('Users.Users/edit_form', array('element' => 'UserManager.UserManager/render_edit_row')); ?>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				$this->NetCommonsHtml->url(array('action' => 'index'))
			); ?>

		<?php echo $this->Button->button(__d('user_manager', 'Save and notify mail'),
				array(
					'name' => 'save_mail',
					'icon' => 'glyphicon-envelope',
					'class' => 'btn btn-primary btn-workflow',
					'ng-class' => '{disabled: sending}'
				)
			); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>

<?php if ($this->params['action'] === 'edit') : ?>
	<?php echo $this->element('Users.Users/delete_form'); ?>
<?php endif;

