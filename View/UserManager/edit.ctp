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

echo $this->NetCommonsHtml->css(
	array('/user_attributes/css/style.css', '/data_types/css/style.css')
);
?>

<?php echo $this->element('UserManager.subtitle'); ?>
<?php echo $this->element('UserManager.setting_tabs'); ?>

<div class="panel panel-default">

	<?php echo $this->NetCommonsForm->create('User'); ?>

	<div class="panel-body">
		<?php echo $this->SwitchLanguage->tablist('user-manager-'); ?>
		<br>

		<div class="tab-content">
			<?php echo $this->NetCommonsForm->hidden('User.id'); ?>
			<?php foreach (array_keys($this->data['UsersLanguage']) as $index) : ?>
				<?php echo $this->NetCommonsForm->hidden('UsersLanguage.' . $index . '.id'); ?>
				<?php echo $this->NetCommonsForm->hidden('UsersLanguage.' . $index . '.language_id'); ?>
			<?php endforeach; ?>

			<?php echo $this->UserAttributeLayout->renderRow('Users.Users/render_edit_row'); ?>
		</div>
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

<?php if ($this->params['action'] === 'edit') : ?>
	<?php echo $this->element('Users.Users/delete_form'); ?>
<?php endif;

