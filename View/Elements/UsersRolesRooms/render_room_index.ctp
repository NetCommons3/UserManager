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
?>

<tr class="<?php echo $this->Rooms->statusCss($room, 'text-'); ?>"
	ng-init="initValue(<?php echo '\'' . $domId . '\', \'' . $rolesRoomsUsers['RolesRoomsUser'][$roomId]['roles_room_id'] . '\''; ?>)">

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
		?>
	</td>

	<?php
		foreach ($defaultRoles as $key => $name) {
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
					'hiddenField' => false,
					'ng-click' => $domId . ' = \'' . $rolesRoomId . '\'',
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
