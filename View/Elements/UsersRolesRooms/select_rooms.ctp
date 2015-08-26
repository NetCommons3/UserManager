<?php
/**
 * Room link template
 *   - $spaceId: spaces.id
 *   - $roomId: rooms.id
 *   - $nest: nest count
 *   - $roomName: rooms_languages.name
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$roomName = str_repeat('<span class="rooms-tree"></span>', $nest) . h($roomName);
?>

<?php
	if (! isset($rolesRoomsUsers[$roomId])) {
		$rolesRoomsUsers[$roomId]['RolesRoomsUser']['id'] = null;
		$rolesRoomsUsers[$roomId]['RolesRoomsUser']['roles_room_id'] = '';
		$rolesRoomsUsers[$roomId]['RolesRoomsUser']['user_id'] = $activeUserId;
	}

	$data = array();
	if (isset($rolesRooms[$roomId])) {
		$data = array(
			'RolesRoomsUser' => array(
				'id' => $rolesRoomsUsers[$roomId]['RolesRoomsUser']['id'],
				'roles_room_id' => $rolesRoomsUsers[$roomId]['RolesRoomsUser']['roles_room_id'],
				'user_id' => $rolesRoomsUsers[$roomId]['RolesRoomsUser']['user_id'],
			),
			'Room' => array(
				'id' => $roomId,
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

<tr class="<?php echo (! $active ? 'danger' : ''); ?>" ng-controller="UsersRolesRooms"
	ng-init="initialize(<?php echo h(json_encode($data, JSON_FORCE_OBJECT)); ?>)">

	<td>
		<?php echo $roomName; ?>
	</td>

	<?php foreach ($roles as $key => $name) : ?>
		<?php
			$html = '';
			$ngClass = '';
			if (isset($rolesRooms[$roomId])) {
				if (isset($rolesRooms[$roomId][$key])) {
					$rolesRoomeId = $rolesRooms[$roomId][$key]['RolesRoom']['id'];
				} else {
					$rolesRoomeId = '';
				}
				$options = array($rolesRoomeId => '');

				if ($rolesRoomeId || ! $room['Room']['default_participation']) {
					$html = $this->Form->radio('RolesRoom.' . $roomId . '.id', $options, array(
						'checked' => ($rolesRoomeId === $rolesRoomsUsers[$roomId]['RolesRoomsUser']['roles_room_id']),
						'hiddenField' => false,
						'ng-click' => 'sendPost(\'' . $rolesRoomeId . '\')',
						'ng-disabled' => 'sending'
					));
				}

				$ngClass = ' ng-class="{\'success\': (rolesRoomId === \'' . $rolesRoomeId . '\')}"';
			}
		?>

		<td class="text-center"<?php echo $ngClass; ?>>
			<?php echo $html; ?>
		</td>
	<?php endforeach; ?>
</tr>
