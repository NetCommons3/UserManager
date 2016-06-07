<?php
/**
 * RolesRoomsUser edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Wizard->navibar(UserAddController::WIZARD_USERS_ROLES_ROOMS); ?>

<?php echo $this->element('UserManager.UsersRolesRooms/edit_header'); ?>

<?php echo $this->NetCommonsForm->create('RolesRoomsUser'); ?>

	<?php echo $this->element('UserManager.UsersRolesRooms/edit_content'); ?>

	<div class="row">
		<div class="col-xs-8 text-right">
			<?php echo $this->Wizard->buttons(
					UserAddController::WIZARD_USERS_ROLES_ROOMS,
					array(),
					array(),
					array('url' => $this->Wizard->naviUrl(UserAddController::WIZARD_MAIL))
				); ?>
		</div>
		<div class="col-xs-4">
			<?php echo $this->NetCommonsForm->checkbox('_UserManager.notify', array(
					'label' => __d('user_manager', 'To notify the user'),
					'checked' => true,
				)); ?>
		</div>
	</div>

<?php echo $this->NetCommonsForm->end();