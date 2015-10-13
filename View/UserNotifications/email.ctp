<?php
/**
 * UserManager index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/user_attributes/css/style.css');
?>

<?php echo $this->element('UserManager.subtitle'); ?>

<?php echo $this->element('UserManager.setting_tabs'); ?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="form-group">
			<?php echo $this->Form->input('title', array(
					'type' => 'text',
					'label' => __d('user_manager', 'Mail title') . $this->element('NetCommons.required'),
					'class' => 'form-control',
					'error' => false,
				)); ?>

			<div class="has-error">
				<?php echo $this->Form->error('title', null, array(
						'class' => 'help-block'
					)); ?>
			</div>
		</div>

		<div class="form-group">
			<?php echo $this->Form->input('body', array(
					'type' => 'textarea',
					'label' => __d('user_manager', 'Mail body') . $this->element('NetCommons.required'),
					'class' => 'form-control',
					'error' => false,
				)); ?>

			<div class="has-error">
				<?php echo $this->Form->error('body', null, array(
						'class' => 'help-block'
					)); ?>
			</div>
		</div>
	</div>

	<div class="panel-footer text-center">
		<a class="btn btn-default btn-workflow" href="<?php echo $this->Html->url('/user_manager/user_manager/index'); ?>">
			<span class="glyphicon glyphicon-remove"></span>
			<?php echo __d('net_commons', 'Close'); ?>
		</a>

		<?php echo $this->Form->button('<span class="glyphicon glyphicon-envelope"></span> ' . __d('user_manager', 'Send'), array(
				'class' => 'btn btn-info btn-workflow',
				'name' => 'send',
			)); ?>
	</div>
</div>
