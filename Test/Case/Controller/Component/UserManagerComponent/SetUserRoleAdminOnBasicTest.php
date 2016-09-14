<?php
/**
 * UserManagerComponent::setUserRoleAdminOnBasic()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserManagerComponent::setUserRoleAdminOnBasic()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\Component\UserManagerComponent
 */
class UserManagerComponentSetUserRoleAdminOnBasicTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.plugin4test',
		'plugin.user_attributes.plugins_role4test',
		'plugin.user_attributes.user_attribute4test',
		'plugin.user_attributes.user_attribute_choice4test',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting4test',
		'plugin.user_attributes.user_attributes_role4test',
		'plugin.user_roles.user_role',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_manager';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'UserManager', 'TestUserManager');

		//テストコントローラ生成
		$this->generateNc('TestUserManager.TestUserManagerComponent');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * setUserRoleAdminOnBasic()のテスト
 *
 * @return void
 */
	public function testSetUserRoleAdminOnBasicWithSystemAdmin() {
		//ログイン
		TestAuthGeneral::login($this);

		//テストアクション実行
		$this->_testGetAction('/test_user_manager/test_user_manager_component/index',
				array('method' => 'assertNotEmpty'), null, 'view');
		$pattern = '/' . preg_quote('Controller/Component/TestUserManagerComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		$this->controller->UserManager->setUserRoleAdminOnBasic();

		$result = $this->controller->viewVars['userAttributes']['1']['1']['6'];
		$this->assertEquals($result['UserAttribute']['key'], 'role_key');

		$expected = array(
			1 => array(
				'id' => '1',
				'language_id' => '2',
				'key' => 'system_administrator',
				'name' => 'System administrator',
				'user_attribute_id' => '10',
			),
			2 => array(
				'id' => '2',
				'language_id' => '2',
				'key' => 'administrator',
				'name' => 'Site administrator',
				'user_attribute_id' => '10',
			),
			3 => array(
				'id' => '3',
				'language_id' => '2',
				'key' => 'common_user',
				'name' => 'Common user',
				'user_attribute_id' => '10',
			),
			4 => array(
				'id' => '4',
				'language_id' => '2',
				'key' => 'test_user',
				'name' => 'Test user',
				'user_attribute_id' => '10',
			),
		);
		$this->assertEquals($result['UserAttributeChoice'], $expected);
	}

/**
 * setUserRoleAdminOnBasic()のテスト
 *
 * @return void
 */
	public function testSetUserRoleAdminOnBasicWithSiteAdmin() {
		//ログイン
		TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_CHIEF_EDITOR);

		//テストアクション実行
		$this->_testGetAction('/test_user_manager/test_user_manager_component/index',
				array('method' => 'assertNotEmpty'), null, 'view');
		$pattern = '/' . preg_quote('Controller/Component/TestUserManagerComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		$this->controller->UserManager->setUserRoleAdminOnBasic();

		$result = $this->controller->viewVars['userAttributes']['1']['1']['6'];
		$this->assertEquals($result['UserAttribute']['key'], 'role_key');

		$expected = array(
			2 => array(
				'id' => '2',
				'language_id' => '2',
				'key' => 'administrator',
				'name' => 'Site administrator',
				'user_attribute_id' => '10',
			),
			3 => array(
				'id' => '3',
				'language_id' => '2',
				'key' => 'common_user',
				'name' => 'Common user',
				'user_attribute_id' => '10',
			),
			4 => array(
				'id' => '4',
				'language_id' => '2',
				'key' => 'test_user',
				'name' => 'Test user',
				'user_attribute_id' => '10',
			),
		);
		$this->assertEquals($result['UserAttributeChoice'], $expected);
	}

}
