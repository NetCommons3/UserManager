<?php
/**
 * UserAttribute index row template
 *   - $row: UserAttributeLayout.row
 *   - $layout: UserAttributeLayout
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->TableList->startTable(); ?>
	<thead>
		<tr>
			<?php echo $this->TableList->tableHeader(null, __d('user_manager', 'Item')); ?>

			<?php echo $this->TableList->tableHeader(null, __d('user_manager', 'Description')); ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($importHelp as $item) : ?>
			<?php echo $this->TableList->startTableRow(); ?>
				<?php echo $this->TableList->tableData(null, $item['title']); ?>

				<?php echo $this->TableList->tableData(null, $item['description']); ?>
			<?php echo $this->TableList->endTableRow(); ?>
		<?php endforeach; ?>
	</tbody>
<?php echo $this->TableList->endTable();