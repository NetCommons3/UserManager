<?php
/**
 * UserManagerController::edit()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');

/**
 * UserManagerController::edit()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserManagerController
 */
class UserManagerControllerEditTest extends UserManagerControllerTestCase {

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
 * edit()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testEditGet() {
		//ログイン
		TestAuthGeneral::login($this);

		//テストデータ
		$userId = '2';

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'user_id' => $userId),
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$expected = array(
			'User' => array(
				'id' => $userId,
				'handlename' => 'Site Manager',
			),
			'UsersLanguage' => array(
				0 => array(
					'id' => '2'
				)
			)
		);
		$this->__assertInputs($expected);
		$this->__assertRequestData($expected);
		$this->assertTrue($this->vars['canUserDelete']);
		$this->assertInput('form', null, '/user_manager/user_manager/delete', $this->view);
	}

/**
 * 編集不可テスト
 *
 * @return void
 */
	public function testEditGetOnWOCanEdit() {
		//ログイン
		TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_CHIEF_EDITOR);

		//テストデータ
		$userId = '1';

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'user_id' => $userId),
				null, 'BadRequestException', 'view');
	}

/**
 * 削除不可ユーザテスト
 *
 * @return void
 */
	public function testEditGetWOCanDelete() {
		//ログイン
		TestAuthGeneral::login($this);

		//テストデータ
		$userId = '1';

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'user_id' => $userId),
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$expected = array(
			'User' => array(
				'id' => $userId,
				'handlename' => 'System Administrator',
			),
			'UsersLanguage' => array(
				0 => array(
					'id' => '1'
				)
			)
		);
		$this->__assertInputs($expected);
		$this->__assertRequestData($expected);
		$this->assertTextNotContains('/user_manager/user_manager/delete', $this->view);
		$this->assertFalse($this->vars['canUserDelete']);
	}

/**
 * $this->request->dataのチェック
 *
 * @param array $expected 期待値
 * @return void
 */
	private function __assertRequestData($expected) {
		//チェック
		$this->assertEquals($this->controller->data['User']['id'], Hash::get($expected, 'User.id'));
		$this->assertEquals($this->controller->data['UsersLanguage'][0]['id'], Hash::get($expected, 'UsersLanguage.0.id'));
		//$this->assertEquals($this->controller->data['UsersLanguage'][1]['id'], Hash::get($expected, 'UsersLanguage.1.id'));
		$this->assertEquals($this->controller->data['UsersLanguage'][0]['language_id'], '2');
		//$this->assertEquals($this->controller->data['UsersLanguage'][1]['language_id'], '1');
		$this->assertEquals($this->controller->data['UsersLanguage'][0]['user_id'], Hash::get($expected, 'User.id'));
		//$this->assertEquals($this->controller->data['UsersLanguage'][1]['user_id'], Hash::get($expected, 'User.id'));
	}

/**
 * edit()のチェック
 *
 * @param array $expected 期待値
 * @return void
 */
	private function __assertInputs($expected) {
		$this->assertInput('form', null, '/user_manager/user_manager/edit', $this->view);
		$this->assertInput('input', '_method', 'PUT', $this->view);
		$this->assertTextContains(
			'<a class="nc-switch-language" href="#users-1" role="tab" data-toggle="tab"',
			$this->view
		);
		$this->assertTextContains(
			'<a class="nc-switch-language" href="#users-2" role="tab" data-toggle="tab"',
			$this->view
		);
		$this->assertInput('input', 'data[User][id]', Hash::get($expected, 'User.id'), $this->view);
		$this->assertInput('input', 'data[UsersLanguage][0][id]', Hash::get($expected, 'UsersLanguage.0.id'), $this->view);
		$this->assertInput('input', 'data[UsersLanguage][1][id]', Hash::get($expected, 'UsersLanguage.1.id'), $this->view);
		$this->assertInput('input', 'data[UsersLanguage][0][language_id]', '1', $this->view);
		$this->assertInput('input', 'data[UsersLanguage][1][language_id]', '2', $this->view);
		$this->assertInput('input', 'data[UsersLanguage][0][user_id]', Hash::get($expected, 'User.id'), $this->view);
		$this->assertInput('input', 'data[UsersLanguage][1][user_id]', Hash::get($expected, 'User.id'), $this->view);
		$this->assertInput('input', 'data[User][handlename]', Hash::get($expected, 'User.handlename'), $this->view);
	}

/**
 * POSTリクエストデータ生成
 * テスト自体は、Mockに差し替えるため、当内容はあまり意味ない
 *
 * @param int $userId ユーザID
 * @param array $merge マージするデータ
 * @return array リクエストデータ
 */
	private function __data($userId, $merge = array()) {
		$data = array(
			'save' => null,
			'active_lang_id' => '2',
			'UsersLanguage' => array(
				0 => array(
					'user_id' => $userId,
					'language_id' => 2,
					'name' => null,
					'profile' => null,
					'search_keywords' => null,
					'id' => null,
				),
				1 => array(
					'user_id' => $userId,
					'language_id' => 1,
					'name' => null,
					'profile' => null,
					'search_keywords' => null,
					'id' => null,
				),
			),
			'User' => array(
				'username' => '',
				'password' => null,
				'key' => null,
				'activate_key' => null,
				'activated' => null,
				'handlename' => '',
				'email' => null,
				'moblie_mail' => null,
				'sex' => null,
				'language' => 'auto',
				'timezone' => 'Asia/Tokyo',
				'role_key' => 'common_user',
				'status' => null,
				'created' => null,
				'modified' => null,
				'password_modified' => null,
				'last_login' => null,
				'previous_login' => null,
				'id' => $userId,
			),
		);
		return Hash::merge($data, $merge);
	}

/**
 * edit()アクションのPOSTリクエストテスト
 *
 * @return void
 */
	public function testEditPost() {
		//ログイン
		TestAuthGeneral::login($this);

		//テストデータ
		$userId = '2';
		$this->_mockForReturnTrue('Users.User', 'saveUser');

		//テスト実行
		$this->_testPostAction('put', $this->__data($userId),
				array('action' => 'edit'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$pattern = '/user_manager/user_manager/index';
		$this->assertTextContains($pattern, $header['Location']);
	}

/**
 * ValidationErrorテスト
 *
 * @return void
 */
	public function testEditPostValidationError() {
		//ログイン
		TestAuthGeneral::login($this);

		//テストデータ
		$userId = '2';

		//テスト実行
		$merge = array(
			'UsersLanguage' => array(
				0 => array(
					'id' => '2',
				),
			),
		);
		$this->_testPostAction('put', $this->__data($userId, $merge),
				array('action' => 'edit'), null, 'view');

		$expected = array(
			'User' => array(
				'id' => $userId,
				'handlename' => '',
			),
			'UsersLanguage' => array(
				0 => array(
					'id' => '2',
				)
			)
		);

		$this->__assertInputs($expected);
		$this->__assertRequestData($expected);
		$this->assertTextContains(
			sprintf(__d('net_commons', 'Please input %s.'), __d('users', 'username')),
			$this->view
		);
	}

}
