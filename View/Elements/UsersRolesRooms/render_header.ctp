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
?>

<tr class="">
	<th> </th>
	<?php foreach ($defaultRoleOptions as $key => $name) : ?>
		<?php
			if ($space['Space']['id'] === Space::PUBLIC_SPACE_ID && !$key) {
				continue;
			}
		?>

		<th class="text-center users-roles-rooms-all-select">
			<?php echo $this->Rooms->roomRoleName($key, ['help' => true, 'default' => $name]); ?>

			<?php if (! in_array($key, [Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR, Role::ROOM_ROLE_KEY_CHIEF_EDITOR], true)) : ?>
				<div>
					<?php
						if ($key === '') {
							$key = 'delete';
						}
					?>
					<button type="button" class="btn btn-default btn-xs"
							ng-click="selectAll(<?php echo '\'' . $key . '\', \'' . $space['Space']['id'] . '\''; ?>)">
						<?php echo __d('net_commons', 'All select'); ?>
					</button>
				</div>
			<?php endif; ?>
		</th>
	<?php endforeach; ?>
</tr>
