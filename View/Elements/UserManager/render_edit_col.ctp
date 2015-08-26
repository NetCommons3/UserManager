<?php
/**
 * UserAttribute index col template
 *   - $row: UserAttributeLayout.row
 *   - $col: UserAttributeLayout.row
 *   - $layout: UserAttributeLayout
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="col-xs-12 col-sm-<?php echo (12 / $layout['UserAttributeLayout']['col']); ?>">
	<?php foreach ($userAttributes[$row][$col] as $userAttribute) : ?>
		<?php echo $this->UserEditForm->userEditInput($userAttribute); ?>
	<?php endforeach; ?>
</div>
