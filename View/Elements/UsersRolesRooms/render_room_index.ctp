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

$roomId = $room['Room']['id'];
if (! isset($rolesRoomsUsers['RolesRoomsUser'][$roomId])) {
	$rolesRoomsUsers['RolesRoomsUser'][$roomId]['id'] = null;
	$rolesRoomsUsers['RolesRoomsUser'][$roomId]['roles_room_id'] = '0';
	$rolesRoomsUsers['RolesRoomsUser'][$roomId]['room_id'] = $roomId;
	$rolesRoomsUsers['RolesRoomsUser'][$roomId]['user_id'] = $activeUserId;
}
$domId = $this->NetCommonsHtml->domId('RolesRoomsUser.' . $roomId . '.roles_room_id');

$parentRoomId = $room['Room']['parent_id'];
$parentDomId = $this->NetCommonsHtml->domId('RolesRoomsUser.' . $parentRoomId . '.roles_room_id');
$initValueArgument = '\'' . $domId . '\', ' .
	'\'' . $rolesRoomsUsers['RolesRoomsUser'][$roomId]['roles_room_id'] . '\'';
if ($room['Room']['space_id'] === Space::COMMUNITY_SPACE_ID &&
	$roomId != Space::getRoomIdRoot(Space::COMMUNITY_SPACE_ID)
) {
	$initValueArgument .= ', ' .
		'\'' . $roomId . '\', ' .
		'\'' . $parentRoomId . '\'';
}

?>

<tr class="<?php echo $this->Rooms->statusCss($room, 'text-'); ?>"
	ng-init="initValue(<?php echo $initValueArgument; ?>)">

	<td>
		<a href="" ng-controller="RoomsController"
			ng-click="showRoom(<?php echo $room['Space']['id'] . ', ' . $room['Room']['id'] . ', null, 0'; ?>)">

			<?php echo $this->Rooms->roomName($room, $nest); ?>
		</a>
		<?php echo $this->Rooms->statusLabel($room, '(%s)'); ?>
		<?php
			echo $this->NetCommonsForm->hidden(
				'RolesRoomsUser.' . $roomId . '.id',
				array('value' => $rolesRoomsUsers['RolesRoomsUser'][$roomId]['id'])
			);
			echo $this->NetCommonsForm->hidden(
				'RolesRoomsUser.' . $roomId . '.room_id',
				array('value' => $rolesRoomsUsers['RolesRoomsUser'][$roomId]['room_id'])
			);
			echo $this->NetCommonsForm->hidden(
				'RolesRoomsUser.' . $roomId . '.user_id',
				array('value' => $rolesRoomsUsers['RolesRoomsUser'][$roomId]['user_id'])
			);
			echo $this->NetCommonsForm->hidden(
				'RolesRoomsUser.' . $roomId . '.roles_room_id',
				array('value' => '0')
			);
		?>
	</td>

	<?php
		foreach ($defaultRoleOptions as $key => $name) {
			if ($room['Space']['id'] === Space::PUBLIC_SPACE_ID && !$key) {
				continue;
			}

			$html = '';
			$ngClass = '';
			if (isset($rolesRooms[$roomId])) {
				if (isset($rolesRooms[$roomId][$key])) {
					$rolesRoomId = $rolesRooms[$roomId][$key]['RolesRoom']['id'];
				} else {
					$rolesRoomId = '0';
					$key = 'delete';
				}
				$options = array($rolesRoomId => '');

				//マージンを付けないため、Formヘルパーを使う
				$html .= '<label for="' . $this->Form->domId('RolesRoomsUser.' . $roomId . '.roles_room_id' . $rolesRoomId) . '">';
				$html .= $this->Form->radio('RolesRoomsUser.' . $roomId . '.roles_room_id', $options, array(
					'checked' => ($rolesRoomId === $rolesRoomsUsers['RolesRoomsUser'][$roomId]['roles_room_id']),
					'ng-checked' => $domId . ' === \'' . $rolesRoomId . '\'',
					'ng-disabled' => $parentDomId . ' === \'0\'',
					'hiddenField' => false,
					'ng-click' => 'setRoleRoomId(\'' . $domId . '\', \'' . $rolesRoomId . '\', \'' . $roomId . '\')',
					'label' => false,
					'data-input-key' => $key . '_' . $room['Space']['id'],
					'data-dom-id' => $domId
				));
				$html .= '</label>';

				$ngClass = '\'success\': (' . $domId . ' === \'' . $rolesRoomId . '\')';
				if ($rolesRoomId === $rolesRoomsUsers['RolesRoomsUser'][$roomId]['roles_room_id']) {
					$ngClass .= ', \'active\': (' . $domId . ' !== \'' . $rolesRoomId . '\')';
				}
			}

			echo '<td class="text-center users-roles-rooms-select" ng-class="{' . $ngClass . '}">';
			echo $html;
			echo '</td>';
		}
	?>
</tr>
