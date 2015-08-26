<?php
/**
 * Subtitle template
 *   - $spaceName
 *   - $roomNames
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $this->start('subtitle'); ?>
	<?php if ($userName !== '') : ?>
		<div class="text-muted visible-xs-inline-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
			( <?php echo h($userName); ?> )
		</div>
	<?php endif; ?>
<?php $this->end();
