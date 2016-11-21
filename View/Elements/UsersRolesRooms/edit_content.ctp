<?php
/**
 * 参加ルームの選択の内容Element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Space', 'Rooms.Model');

echo $this->NetCommonsHtml->css('/user_manager/css/style.css');
?>

<div class="tab-content">
	<div id="user-manager-public-space" class="tab-pane">
		<article class="rooms-manager">
			<?php echo $this->Rooms->roomsRender(Space::PUBLIC_SPACE_ID,
					array(
						'dataElemen' => 'UsersRolesRooms/render_room_index',
						'headElement' => 'UsersRolesRooms/render_header'
					),
					array(
						'paginator' => false,
						'displaySpace' => true,
						'roomTreeList' => $rooms[Space::PUBLIC_SPACE_ID]['roomTreeList'],
						'rooms' => $rooms[Space::PUBLIC_SPACE_ID]['rooms'],
						'tableClass' => 'table table-hover'
					)
				); ?>
		</article>
	</div>

	<div id="user-manager-room-space" class="tab-pane active">
		<article class="rooms-manager">
			<?php echo $this->Rooms->roomsRender(Space::COMMUNITY_SPACE_ID,
					array(
						'dataElemen' => 'UsersRolesRooms/render_room_index',
						'headElement' => 'UsersRolesRooms/render_header'
					),
					array(
						'paginator' => false,
						'displaySpace' => false,
						'roomTreeList' => $rooms[Space::COMMUNITY_SPACE_ID]['roomTreeList'],
						'rooms' => $rooms[Space::COMMUNITY_SPACE_ID]['rooms'],
						'tableClass' => 'table table-hover'
					)
				); ?>
		</article>
	</div>
</div>
