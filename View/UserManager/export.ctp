<?php
/**
 * エクスポートtemplate
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css(array(
	'/user_manager/css/style.css',
	'/users/css/style.css',
));
?>

<h2>
	<?php echo __d('user_manager', 'Export title'); ?>
</h2>

<div class="well well-sm">
	<?php echo __d('user_manager', 'Export description'); ?>
</div>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create(false, array('type' => 'get', 'ng-submit' => false)); ?>

	<div class="panel-body">
		<div class="user-export-header">
			<?php echo $this->UserSearchForm->displaySearchButton(__d('user_manager', 'Search for the export members')); ?>
		</div>

		<?php
			foreach ($this->data['UserSearch'] as $key => $value) {
				echo $this->NetCommonsForm->hidden('UserSearch.' . $key);
			}
		?>

		<?php echo $this->NetCommonsForm->input('pass', array(
			'type' => 'text',
			'label' => __d('user_manager', 'Password'),
			'value' => substr(str_shuffle(ImportExportBehavior::RANDAMSTR), 0, 10),
			'help' => __d('user_manager', 'If you do not want to assign a password, please leave it blank.'),
		)); ?>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				NetCommonsUrl::actionUrlAsArray(array('action' => 'index', '?' => $cancelQuery)),
				array('ng-class' => null),
				array('ng-class' => null)
			); ?>
	</div>

	<?php echo $this->Form->end(); ?>
</div>
