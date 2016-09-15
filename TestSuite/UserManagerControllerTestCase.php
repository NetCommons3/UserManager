<?php
/**
 * UserAddController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

//@codeCoverageIgnoreStart
App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
//@codeCoverageIgnoreEnd

/**
 * UserAddController::beforeFilter()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserAddController
 * @codeCoverageIgnore
 */
class UserManagerControllerTestCase extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.mails.mail_setting_fixed_phrase',
		'plugin.user_attributes.plugin4test',
		'plugin.user_attributes.plugins_role4test',
		'plugin.user_attributes.user_attribute4test',
		'plugin.user_attributes.user_attribute_choice4test',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting4test',
		'plugin.user_attributes.user_attributes_role4test',
		'plugin.user_roles.user_role',
		'plugin.users.room4user',
		'plugin.users.rooms_language4user',
		'plugin.users.roles_room4user',
		'plugin.users.roles_rooms_user4user',
		'plugin.users.upload_file4user',
		'plugin.users.upload_files_content4user',
		'plugin.users.user4user',
		'plugin.users.users_language4user',
		'plugin.groups.group',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_manager';

}
