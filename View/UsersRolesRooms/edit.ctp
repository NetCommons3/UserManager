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

echo $this->NetCommonsHtml->css('/rooms/css/style.css');
echo $this->NetCommonsHtml->script('/user_manager/js/users_roles_rooms.js');
?>

<?php echo $this->element('UserManager.setting_tabs'); ?>
<?php echo $this->element('UserManager.subtitle'); ?>
<?php echo $this->element('UserManager.UsersRolesRooms/edit_header'); ?>

<?php echo $this->NetCommonsForm->create('RolesRoomsUser'); ?>

	<?php echo $this->NetCommonsForm->hidden('User.id', array('value' => $activeUserId)); ?>

	<?php echo $this->element('UserManager.UsersRolesRooms/edit_content'); ?>

	<div class="text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				$this->NetCommonsHtml->url(['controller' => 'user_manager', 'action' => 'index'])
			); ?>
	</div>

<?php echo $this->NetCommonsForm->end();