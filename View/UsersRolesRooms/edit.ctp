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

<?php echo $this->element('UsersRolesRooms/space_tabs'); ?>

<div class="nc-content-list">

	<?php echo $this->element('Rooms.Rooms/render_index', array(
			'headElementPath' => 'UsersRolesRooms/head_room_roles',
			'elementPath' => 'UsersRolesRooms/select_rooms'
		)); ?>

</div>

<div class="text-center">
	<a class="btn btn-default btn-workflow" href="<?php echo $this->Html->url('/user_manager/user_manager/index'); ?>">
		<span class="glyphicon glyphicon-remove"></span>
		<?php echo __d('net_commons', 'Close'); ?>
	</a>
</div>
