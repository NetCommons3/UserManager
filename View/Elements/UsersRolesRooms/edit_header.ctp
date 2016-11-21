<?php
/**
 * 参加ルームの選択のヘッダーElement
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css(['/rooms/css/style.css', '/user_manager/css/style.css']);
echo $this->NetCommonsHtml->script('/user_manager/js/users_roles_rooms.js');
?>

<?php
	echo $this->MessageFlash->description(
		__d('user_manager', 'Please choose whether to participate in what role the members in each room, and press the [OK].<br>' .
							'If you want to exit this screen, please press the [Cancel].')
	);
?>

<?php echo $this->Rooms->spaceTabs(Space::COMMUNITY_SPACE_ID, 'pills', array(
	Space::PUBLIC_SPACE_ID => array(
		'url' => '#user-manager-public-space',
		'attributes' => array(
			'aria-controls' => 'user-manage-public-space',
			'role' => 'tab',
			'data-toggle' => 'tab',
		),
	),
	Space::COMMUNITY_SPACE_ID => array(
		'url' => '#user-manager-room-space',
		'attributes' => array(
			'aria-controls' => 'user-manage-room-space',
			'role' => 'tab',
			'data-toggle' => 'tab',
		),
	),
));
