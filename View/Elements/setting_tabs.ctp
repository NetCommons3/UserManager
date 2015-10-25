<?php
/**
 * User setting tabs template
 *   - $activeUserId: Active users.id.
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Space', 'Rooms.Model');

if ($this->params['action'] === 'add') {
	$disabled = 'disabled';
	$urlUsers = '';
	$urlRolesRoomsUser = '';
	$urlNotifyUser = '';
} else {
	$disabled = '';
	$urlUsers = '/user_manager/user_manager/edit/' . h($activeUserId) . '/';
	$urlRolesRoomsUser = '/user_manager/users_roles_rooms/edit/' . h($activeUserId) . '/' . Space::ROOM_SPACE_ID;
	$urlNotifyUser = '/user_manager/user_notifications/email/' . h($activeUserId) . '/';
}
?>

<ul class="nav nav-tabs" role="tablist">
	<li class="<?php echo ($this->params['controller'] === 'user_manager' ? 'active' : $disabled); ?>">
		<?php echo $this->Html->link(__d('user_manager', 'General setting'), $urlUsers); ?>
	</li>

	<li class="<?php echo ($this->params['controller'] === 'users_roles_rooms' ? 'active' : $disabled); ?>">
		<?php echo $this->Html->link(__d('user_manager', 'Select the rooms to join'), $urlRolesRoomsUser); ?>
	</li>

	<li class="<?php echo ($this->params['controller'] === 'user_notifications' ? 'active' : $disabled); ?>">
		<?php echo $this->Html->link(__d('user_manager', 'Notify user by e-mail'), $urlNotifyUser); ?>
	</li>
</ul>

<br>
