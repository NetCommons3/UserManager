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
<?php echo $this->element('UserManager.setting_tabs'); ?>
<?php echo $this->Rooms->spaceTabs($activeSpaceId, 'pills', $this->NetCommonsHtml->url(array('action' => 'edit', $activeUserId)) . '/%s'); ?>

<div class="nc-content-list">
	<?php echo $this->Rooms->roomsRender($activeSpaceId, 'UsersRolesRooms/render_room_index', 'UsersRolesRooms/render_header'); ?>
</div>

<div class="text-center">
	<a class="btn btn-default btn-workflow" href="<?php echo $this->NetCommonsHtml->url(array('action' => 'index')); ?>">
		<span class="glyphicon glyphicon-remove"></span>
		<?php echo __d('net_commons', 'Close'); ?>
	</a>
</div>
