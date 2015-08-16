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

echo $this->Html->css(
	array(
		'/user_attributes/css/style.css'
	),
	array('plugin' => false)
);
?>

<?php echo $this->element('UserManager.subtitle'); ?>

<?php echo $this->element('UserManager.tabs', array('activeTab' => 'setting')); ?>

<?php echo $this->element('UserManager.setting_tabs'); ?>

<div class="panel panel-default">

	<?php echo $this->Form->create('User', array('novalidate' => true)); ?>

	<div class="panel-body">
		<?php echo $this->SwitchLanguage->tablist('user-manager-'); ?>
		<br>
		
		<div class="tab-content">
			<?php foreach ($userAttributeLayouts as $layout) : ?>
				<?php $row = $layout['UserAttributeLayout']['id']; ?>

				<?php echo $this->element('UserManager/render_edit_row', array('row' => $row, 'layout' => $layout)); ?>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="panel-footer text-center">
		<a class="btn btn-default btn-workflow" href="<?php echo $this->Html->url('/user_manager/user_manager/index'); ?>">
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
