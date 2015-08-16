<?php
/**
 * Main tabs template
 *   - $activeUserId: Active users.id.
 *   - $activeTab: Active tab. seeach or setting
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<ul class="nav nav-tabs" role="tablist">
	<li class="<?php echo ($activeTab === 'search' ? 'active' : ''); ?>">
		<a href="<?php echo $this->Html->url('/user_manager/user_manager/index/'); ?>">
			<?php echo __d('user_manager', 'User Search'); ?>
		</a>
	</li>

	<li class="<?php echo ($activeTab === 'setting' ? 'active' : ''); ?>">
		<?php if (isset($activeUserId)) : ?>
			<a href="<?php echo $this->Html->url('/user_manager/user_manager/edit/' . $activeUserId . '/'); ?>">
				<?php echo __d('user_manager', 'Edit user info'); ?>
			</a>
		<?php else : ?>
			<a href="<?php echo $this->Html->url('/user_manager/user_manager/add/'); ?>">
				<?php echo __d('user_manager', 'Add new user'); ?>
			</a>
		<?php endif; ?>
	</li>
</ul>

<br>
