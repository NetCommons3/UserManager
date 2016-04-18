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

<?php echo $this->element('NetCommons.javascript_alert'); ?>
<?php echo $this->element('UserManager.subtitle'); ?>
<?php echo $this->Wizard->navibar(UserManagerAppController::WIZARD_USERS_ROLES_ROOMS); ?>

<?php echo $this->MessageFlash->description(
	__d('user_manager', 'Please choose whether to participate in what role the members in each room. After changing the role of the member, it will soon be registered.<br>' .
						'When to notify the user, please press the [Next]. If you want to exit this screen, please press the [Cancel].')
); ?>

<?php echo $this->Rooms->spaceTabs($activeSpaceId, 'pills', $this->NetCommonsHtml->url(array('action' => 'edit', $activeUserId)) . '/%s'); ?>

<div class="nc-content-list">
	<?php echo $this->Rooms->roomsRender($activeSpaceId, 'UsersRolesRooms/render_room_index', 'UsersRolesRooms/render_header'); ?>
</div>

<div class="text-center">
	<div class="text-center">
		<?php echo $this->Wizard->buttons(
				UserManagerAppController::WIZARD_USERS_ROLES_ROOMS,
				array(),
				array(),
				array('url' => $this->Wizard->naviUrl(UserManagerAppController::WIZARD_MAIL))
			); ?>
	</div>
</div>
