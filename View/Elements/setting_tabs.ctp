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
	$notifyUserDisabled = 'disabled';
	$urlUsers = '';
	$urlRolesRoomsUser = '';
	$urlNotifyUser = '';
} else {
	$disabled = '';
	$urlUsers = array(
		'plugin' => 'user_manager', 'controller' => 'user_manager', 'action' => 'edit', h($activeUserId)
	);
	$urlRolesRoomsUser = array(
		'plugin' => 'user_manager', 'controller' => 'users_roles_rooms', 'action' => 'edit', h($activeUserId), Space::ROOM_SPACE_ID
	);
	if (! Hash::get($user, 'email')) {
		$notifyUserDisabled = 'disabled';
		$urlNotifyUser = '';
	} else {
		$notifyUserDisabled = '';
		$urlNotifyUser = array(
			'plugin' => 'user_manager', 'controller' => 'user_mail', 'action' => 'notify', h($activeUserId)
		);
	}
}
?>

<ul class="nav nav-tabs" role="tablist">
	<li class="<?php echo ($this->params['controller'] === 'user_manager' ? 'active' : $disabled); ?>">
		<?php echo $this->NetCommonsHtml->link(__d('user_manager', 'General setting'), $urlUsers); ?>
	</li>

	<li class="<?php echo ($this->params['controller'] === 'users_roles_rooms' ? 'active' : $disabled); ?>">
		<?php echo $this->NetCommonsHtml->link(__d('user_manager', 'Select the rooms to join'), $urlRolesRoomsUser); ?>
	</li>

	<li class="<?php echo ($this->params['controller'] === 'user_mail' ? 'active' : $notifyUserDisabled); ?>">
		<?php echo $this->NetCommonsHtml->link(__d('user_manager', 'Notify user by e-mail'), $urlNotifyUser); ?>
	</li>
</ul>

<br>
