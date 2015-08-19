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

echo $this->Html->css(
	array(
		'/rooms/css/style.css'
	),
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
?>

<?php echo $this->element('UserManager.subtitle'); ?>

<?php echo $this->element('UserManager.setting_tabs'); ?>

<?php echo $this->element('UsersRolesRooms/space_tabs'); ?>

<?php echo $this->Form->create(null, array('novalidate' => true)); ?>


<?php echo $this->element('Rooms.Rooms/render_index', array('elementPath' => 'UsersRolesRooms/select_rooms')); ?>

<div class="text-center">
	<a class="btn btn-default btn-workflow" href="<?php echo $this->Html->url('/user_manager/user_manager/index'); ?>">
		<span class="glyphicon glyphicon-remove"></span>
		<?php echo __d('net_commons', 'Close'); ?>
	</a>
</div>

<?php echo $this->Form->end();
