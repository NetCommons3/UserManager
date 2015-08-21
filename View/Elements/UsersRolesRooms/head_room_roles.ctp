<?php
/**
 * Space tabs template
 *   - $activeSpaceId: Active spaces.id.
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<tr class="">
	<th></th>
	<?php foreach ($roomRoles as $key => $name) : ?>
		<th>
			<?php echo h($name); ?>
		</th>
	<?php endforeach; ?>
</tr>
