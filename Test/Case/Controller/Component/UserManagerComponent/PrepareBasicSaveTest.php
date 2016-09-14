<?php
/**
 * UserManagerComponent::prepareBasicSave()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserManagerComponent::prepareBasicSave()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\Component\UserManagerComponent
 */
class UserManagerComponentPrepareBasicSaveTest extends NetCommonsControllerTestCase {

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
 * prepareBasicSave()のテスト
 *
 * @return void
 */
	public function testPrepareBasicSave() {
		//テストコントローラ生成
		$this->generateNc('TestUserManager.TestUserManagerComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テストデータ
		$data = array(
			'UsersLanguage' => array(
				0 => array(
					'language_id' => '2',
					'name' => 'Test name'
				),
				1 => array(
					'language_id' => '1',
					'name' => ''
				),
			),
		);

		//テストアクション実行
		$this->_testPostAction('post', $data, '/test_user_manager/test_user_manager_component/index', null, 'view');
		$pattern = '/' . preg_quote('Controller/Component/TestUserManagerComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//テスト実行
		$this->controller->UserManager->prepareBasicSave();

		//チェック
		$expected = array(
			'UsersLanguage' => array(
				0 => array(
					'language_id' => '2',
					'name' => 'Test name',
					'profile' => null,
					'search_keywords' => null,
				),
				1 => array(
					'language_id' => '1',
					'name' => 'Test name',
					'profile' => null,
					'search_keywords' => null,
				),
			),
		);
		$this->assertEquals($this->controller->data, $expected);

		$expected = array(
			0 => 'UsersLanguage.name',
			1 => 'UsersLanguage.profile',
			2 => 'UsersLanguage.search_keywords',
		);
		$this->assertEquals($this->controller->SwitchLanguage->fields, $expected);

		$this->assertNotEmpty($this->controller->User->userAttributeData);
	}

}
