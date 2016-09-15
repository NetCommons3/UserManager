<?php
/**
 * UserManagerController::index()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');

/**
 * UserManagerController::index()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserManagerController
 */
class UserManagerControllerIndexTest extends UserManagerControllerTestCase {

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'user_manager';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);
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
 * index()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testIndexGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'index'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->assertTextContains('/> System Administrator</a>', $this->view);
		$this->assertTextContains('/> Site Manager</a>', $this->view);
		$this->assertTextContains('/> Chief Editor</a>', $this->view);
		$this->assertTextContains('/> Editor</a>', $this->view);
		$this->assertTextContains('/> General User</a>', $this->view);
		$this->assertTextContains('/> Visitor</a>', $this->view);

		$expected = array(
			'handlename' => 'handlename',
			'name' => 'name',
			'role_key' => 'role_key',
			'status' => 'status',
			'modified' => 'modified',
			'last_login' => 'last_login',
		);
		$this->assertEquals($this->vars['displayFields'], $expected);

		$expected = array(
			0 => array(
				'User' => array(
					'id' => '1',
					'handlename' => 'System Administrator',
					'role_key' => 'system_administrator',
					'status' => '1',
					'modified' => '2015-08-15 06:12:30',
					'last_login' => '2016-09-14 12:18:45',
				),
				'UsersLanguage' => array(
					'name' => 'System Administrator Name',
				),
			),
			1 => array(
				'User' => array(
					'id' => '2',
					'handlename' => 'Site Manager',
					'role_key' => 'administrator',
					'status' => '1',
					'modified' => '2015-08-15 06:12:30',
					'last_login' => null,
				),
				'UsersLanguage' => array(
					'name' => 'Site Manager Name',
				),
			),
			2 => array(
				'User' => array(
					'id' => '3',
					'handlename' => 'Chief Editor',
					'role_key' => 'common_user',
					'status' => '1',
					'modified' => '2015-08-15 06:12:30',
					'last_login' => null,
				),
				'UsersLanguage' => array(
					'name' => 'Chief Editor Name',
				),
			),
			3 => array(
				'User' => array(
					'id' => '4',
					'handlename' => 'Editor',
					'role_key' => 'common_user',
					'status' => '1',
					'modified' => '2015-08-15 06:12:30',
					'last_login' => null,
				),
				'UsersLanguage' => array(
					'name' => 'Editor Name',
				),
			),
			4 => array(
				'User' => array(
					'id' => '5',
					'handlename' => 'General User',
					'role_key' => 'common_user',
					'status' => '1',
					'modified' => '2015-08-15 06:12:30',
					'last_login' => null,
				),
				'UsersLanguage' => array(
					'name' => 'General User Name',
				),
			),
			5 => array(
				'User' => array(
					'id' => '6',
					'handlename' => 'Visitor',
					'role_key' => 'common_user',
					'status' => '1',
					'modified' => '2015-08-15 06:12:30',
					'last_login' => null,
				),
				'UsersLanguage' => array(
					'name' => 'Visitor Name',
				),
			),
		);
		$this->assertEquals($this->vars['users'], $expected);

		$this->assertEquals($this->controller->request->query, array());
	}

}
