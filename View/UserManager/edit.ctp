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

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create('User', array('type' => 'file')); ?>

	<?php echo $this->element('Users.Users/edit_form', array('element' => 'UserManager.UserManager/render_edit_row')); ?>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				$this->NetCommonsHtml->url(array('action' => 'index'))
			); ?>

		<?php echo $this->NetCommonsForm->button(
				'<span class="glyphicon glyphicon-envelope"></span> ' . __d('user_manager', 'Save and notify mail'),
				array(
					'name' => 'save_mail',
					'class' =>
					'btn btn-primary btn-workflow',
					'escape' => false,
					'ng-class' => '{disabled: sending}'
				)
			); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>

<?php if ($this->params['action'] === 'edit') : ?>
	<?php echo $this->element('Users.Users/delete_form'); ?>
<?php endif;

