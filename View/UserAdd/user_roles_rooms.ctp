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

echo $this->NetCommonsHtml->css([
	'/rooms/css/style.css',
	'/plugin_manager/css/style.css',
]);
echo $this->NetCommonsHtml->script([
	'/user_manager/js/users_roles_rooms.js',
	'/rooms/js/rooms.js',
	'/rooms/js/role_permissions.js',
	'/rooms/js/room_role_permissions.js'
]);
?>

<?php echo $this->Wizard->navibar(UserAddController::WIZARD_USERS_ROLES_ROOMS); ?>

<?php echo $this->element('UserManager.UsersRolesRooms/edit_header'); ?>

<?php echo $this->NetCommonsForm->create('RolesRoomsUser'); ?>

	<?php echo $this->element('UserManager.UsersRolesRooms/edit_content'); ?>

	<div class="text-center">
		<?php echo $this->Wizard->buttons(
				UserAddController::WIZARD_USERS_ROLES_ROOMS,
				array(),
				array(),
				array(
					'title' => __d('net_commons', 'OK'),
					'icon' => false,
				)
			); ?>

		<?php if ($isNotify) : ?>
			<span class="well well-sm btn-workflow user-manager-check-notify">
				<?php echo $this->NetCommonsForm->checkbox('_UserManager.notify', array(
						'label' => __d('user_manager', 'To notify the user'),
						'checked' => true,
						'inline' => true
					)); ?>
			</span>
		<?php endif; ?>
	</div>

<?php echo $this->NetCommonsForm->end();