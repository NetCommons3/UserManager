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

	<?php echo $this->Form->create('User', array('novalidate' => true)); ?>

	<div class="panel-body">
		<?php echo $this->SwitchLanguage->tablist('user-manager-'); ?>
		<br>

		<div class="tab-content">
			<?php echo $this->Form->hidden('User.id'); ?>
			<?php foreach (array_keys($this->data['UsersLanguage']) as $index) : ?>
				<?php echo $this->Form->hidden('UsersLanguage.' . $index . '.id'); ?>
				<?php echo $this->Form->hidden('UsersLanguage.' . $index . '.language_id'); ?>
			<?php endforeach; ?>

			<?php foreach ($userAttributeLayouts as $layout) : ?>
				<?php $row = $layout['UserAttributeLayout']['id']; ?>

				<?php echo $this->element('UserManager/render_edit_row', array('row' => $row, 'layout' => $layout)); ?>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="panel-footer text-center">
		<a class="btn btn-default btn-workflow" href="<?php echo $this->Html->url(array('action' => 'index')); ?>">
			<span class="glyphicon glyphicon-remove"></span>
			<?php echo __d('net_commons', 'Cancel'); ?>
		</a>

		<?php echo $this->Form->button(__d('net_commons', 'OK'), array(
				'class' => 'btn btn-primary btn-workflow',
				'name' => 'save',
			)); ?>
	</div>

	<?php echo $this->Form->end(); ?>
</div>
