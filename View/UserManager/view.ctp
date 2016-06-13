<?php
/**
 * UserAttribute index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Space', 'Rooms.Model');
?>

<?php $this->start('title_for_modal'); ?>
<?php echo __d('users', 'User information'); ?>
<?php $this->end(); ?>

<?php if (isset($rooms)) : ?>
	<ul class="nav nav-tabs" role="tablist">
		<li class="active">
			<a href="#user-information" aria-controls="user-infomation" role="tab" data-toggle="tab">
				<?php echo __d('users', 'User information'); ?>
			</a>
		</li>

		<li>
			<a href="#user-rooms" aria-controls="user-rooms" role="tab" data-toggle="tab">
				<?php echo __d('users', 'Rooms'); ?>
			</a>
		</li>
	</ul>
<?php endif; ?>

<div class="tab-content">
	<div class="tab-pane active" id="user-information">
		<div class="text-right nc-edit-link">
			<?php echo $this->Button->editLink(__d('net_commons', 'Edit'),
					array('controller' => 'user_manager', 'action' => 'edit', 'key' => $user['User']['id']),
					array('iconSize' => ' btn-sm')
				); ?>
		</div>
		<?php echo $this->element('Users.Users/view_information', array('editLink' => false)); ?>
	</div>

	<?php if (isset($rooms)) : ?>
		<div class="tab-pane" id="user-rooms">
			<div class="pull-right">
				<?php echo $this->Button->editLink('',
						array('controller' => 'users_roles_rooms', 'key' => $user['User']['id']),
						array('tooltip' => true, 'iconSize' => ' btn-sm')
					); ?>
			</div>
			<?php echo $this->element('Users.Users/view_rooms'); ?>
		</div>
	<?php endif; ?>
</div>
