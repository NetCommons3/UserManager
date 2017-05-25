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

<?php echo $this->element('UserManager.setting_tabs'); ?>
<div ng-cloak>
	<?php echo $this->element('UserManager.subtitle'); ?>
	<?php echo $this->element('UserManager.UsersRolesRooms/edit_header'); ?>

	<?php echo $this->NetCommonsForm->create('RolesRoomsUser'); ?>

		<?php echo $this->NetCommonsForm->hidden('User.id', array('value' => $activeUserId)); ?>

		<article ng-controller="UsersRolesRooms">
			<?php echo $this->element('UserManager.UsersRolesRooms/edit_content'); ?>
		</article>

		<div class="text-center">
			<?php echo $this->Button->cancelAndSave(
					__d('net_commons', 'Cancel'),
					__d('net_commons', 'OK'),
					NetCommonsUrl::actionUrlAsArray(['controller' => 'user_manager', 'action' => 'index'])
				); ?>
		</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>