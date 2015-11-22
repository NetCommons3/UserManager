<?php
/**
 * 後で見直す
 * UserManager index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $this->start('title_for_modal'); ?>
<?php echo __d('user_manager', 'User search'); ?>
<?php $this->end(); ?>

<?php echo $this->NetCommonsForm->create('UserSearch', array('type' => 'get')); ?>

<div class="panel panel-default">
	<div class="panel-body">
		<?php foreach ($userAttributeLayouts as $layout) : ?>
			<?php $row = $layout['UserAttributeLayout']['id']; ?>

			<?php echo $this->element('UserManager/render_search_row', array('row' => $row, 'layout' => $layout)); ?>
		<?php endforeach; ?>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancel(__d('net_commons', 'Cancel'), '', array(
			'type' => 'button',
			'ng-click' => 'cancel()'
		)); ?>
		<?php echo $this->Button->search(__d('net_commons', 'Search'), array(
			'type' => 'button',
			'ng-click' => 'search()'
		)); ?>
	</div>
</div>

<?php $this->end();