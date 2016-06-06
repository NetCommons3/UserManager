<?php
/**
 * タブElement
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$urlUsers = array(
	'plugin' => 'user_manager', 'controller' => 'user_manager', 'action' => 'edit', 'key' => h($activeUserId)
);
$urlRolesRoomsUser = array(
	'plugin' => 'user_manager', 'controller' => 'users_roles_rooms', 'action' => 'edit', 'key' => h($activeUserId)
);
?>

<ul class="nav nav-tabs" role="tablist">
	<li class="<?php echo ($this->params['controller'] === 'user_manager' ? 'active' : ''); ?>">
		<?php echo $this->NetCommonsHtml->link(__d('user_manager', 'General setting'), $urlUsers); ?>
	</li>

	<li class="<?php echo ($this->params['controller'] === 'users_roles_rooms' ? 'active' : ''); ?>">
		<?php echo $this->NetCommonsHtml->link(__d('user_manager', 'Select the rooms to join'), $urlRolesRoomsUser); ?>
	</li>
</ul>
