<?php
/**
 * Rooms index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

	if (! isset($rolesRoomsUsers[$room['Room']['id']])) {
		$rolesRoomsUsers[$room['Room']['id']]['RolesRoomsUser']['id'] = null;
		$rolesRoomsUsers[$room['Room']['id']]['RolesRoomsUser']['roles_room_id'] = '';
		$rolesRoomsUsers[$room['Room']['id']]['RolesRoomsUser']['user_id'] = $activeUserId;
	}

	$data = array();
	if (isset($rolesRooms[$room['Room']['id']])) {
		$data = array(
			'RolesRoomsUser' => array(
				'id' => $rolesRoomsUsers[$room['Room']['id']]['RolesRoomsUser']['id'],
				'roles_room_id' => $rolesRoomsUsers[$room['Room']['id']]['RolesRoomsUser']['roles_room_id'],
				'user_id' => $rolesRoomsUsers[$room['Room']['id']]['RolesRoomsUser']['user_id'],
			),
			'Room' => array(
				'id' => $room['Room']['id'],
				'space_id' => $activeSpaceId,
			)
		);

		$tokenFields = Hash::flatten($data);

		$hiddenFields = $tokenFields;
		unset($hiddenFields['RolesRoomsUser.id'], $hiddenFields['RolesRoomsUser.roles_room_id']);
		$hiddenFields = array_keys($hiddenFields);

		$this->request->data = $data;
		$this->Token->unlockField('RolesRoomsUser.id');
		$tokens = $this->Token->getToken('RolesRoomsUser',
				'/user_manager/users_roles_rooms/edit/' . $activeUserId . '/' . $activeSpaceId . '.json',
				$tokenFields, $hiddenFields);
		$data += $tokens;
	}
?>

<tr class="<?php echo $this->Rooms->statusCss($room); ?>"
	ng-controller="UsersRolesRooms"
	ng-init="initialize(<?php echo h(json_encode($data, JSON_FORCE_OBJECT)); ?>)">

	<td>
		<?php echo $this->Rooms->roomName($room, $nest); ?>
	</td>

	<?php
		foreach ($defaultRoles as $key => $name) {
			$html = '';
			$ngClass = '';
			if (isset($rolesRooms[$room['Room']['id']])) {
				if (isset($rolesRooms[$room['Room']['id']][$key])) {
					$rolesRoomId = $rolesRooms[$room['Room']['id']][$key]['RolesRoom']['id'];
				} else {
					$rolesRoomId = '';
				}
				$options = array($rolesRoomId => '');

				if ($rolesRoomId || ! $room['Room']['default_participation']) {
					$html = $this->NetCommonsForm->radio('RolesRoom.' . $room['Room']['id'] . '.id', $options, array(
						'checked' => ($rolesRoomId === $rolesRoomsUsers[$room['Room']['id']]['RolesRoomsUser']['roles_room_id']),
						'hiddenField' => false,
						'ng-click' => 'sendPost(\'' . $rolesRoomId . '\')',
						'ng-disabled' => 'sending'
					));
				}

				$ngClass = ' ng-class="{\'success\': (rolesRoomId === \'' . $rolesRoomId . '\')}"';
			}

			echo '<td class="text-center"' . $ngClass . '">';
			echo $html;
			echo '</td>';
		}
	?>
</tr>
