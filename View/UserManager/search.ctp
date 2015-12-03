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

$domId = $this->NetCommonsForm->domId('UserSearch.' . $this->params['action'] . '_' . 'form');

?>

<?php $this->start('title_for_modal'); ?>
<?php echo __d('user_manager', 'User search'); ?>
<?php $this->end(); ?>

<?php echo $this->NetCommonsForm->create('UserSearch', array('type' => 'get')); ?>

<div class="panel panel-default" ng-init="initialize('<?php echo $domId; ?>')">
	<div class="panel-body">
		<?php foreach ($userAttributeLayouts as $layout) : ?>
			<?php $row = $layout['UserAttributeLayout']['id']; ?>

			<?php echo $this->element('UserManager/render_search_row', array('row' => $row, 'layout' => $layout)); ?>
		<?php endforeach; ?>

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<?php echo $this->UserSearchForm->userSearchRoomsSelect(); ?>
			</div>
			<div class="col-xs-12 col-sm-6">
				<?php echo $this->UserSearchForm->userSearchGroupsSelect(); ?>
			</div>
		</div>

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