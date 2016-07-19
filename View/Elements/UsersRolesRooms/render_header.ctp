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
	<?php foreach ($defaultRoles as $key => $name) : ?>
		<th class="text-center users-roles-rooms-all-select">
			<?php echo h($name); ?>

			<?php if (! in_array($key, [Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR, Role::ROOM_ROLE_KEY_CHIEF_EDITOR], true)) : ?>
				<div>
					<button class="btn btn-default btn-xs" ng-click="selectAll(<?php echo '\'' . $key . '\', \'' . $space['Space']['id'] . '\''; ?>)" onclick="return false">
						<?php echo __d('net_commons', 'All select'); ?>
					</button>
				</div>
			<?php endif; ?>
		</th>
	<?php endforeach; ?>
</tr>
