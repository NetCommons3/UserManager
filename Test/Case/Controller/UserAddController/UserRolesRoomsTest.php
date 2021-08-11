<?php
/**
 * UserAddController::user_roles_rooms()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');

/**
 * UserAddController::user_roles_rooms()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserAddController
 */
class UserAddControllerUserRolesRoomsTest extends UserManagerControllerTestCase {

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'user_add';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->generateNc(Inflector::camelize($this->_controller), array('components' => array(
			'Flash' => array('set')
		)));

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
 * user_roles_rooms()アクションのGetリクエストテスト
 * - UserMail->isUserMailSend() = false, emailなし
 *
 * @return void
 */
	public function testGetWOIsUserMailSendWOEmail() {
		//テスト実行
		$this->_testGetAction(
			array('action' => 'user_roles_rooms'), array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$this->__assert();
		$this->assertEquals($this->vars['isNotify'], false);
	}

/**
 * user_roles_rooms()アクションのGetリクエストテスト
 * - UserMail->isUserMailSend() = true, emailなし
 *
 * @return void
 */
	public function testGetWithIsUserMailSendWOEmail() {
		//事前準備
		$this->_mockForReturnTrue('UserManager.UserMail', 'isUserMailSend');
		$user = array('User' => array(
			'id' => '2',
			'password' => 'aaaa',
			//'email' => 'room_administrator@exapmle.com',
			'handlename' => 'Room Administrator',
			'username' => 'room_administrator',
		));
		$this->controller->Components->Session
			->expects($this->any())->method('read')
			->will($this->returnValue($user));

		//テスト実行
		$this->_testGetAction(
			array('action' => 'user_roles_rooms'), array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$this->__assert();
		$this->assertEquals($this->vars['isNotify'], false);
	}

/**
 * user_roles_rooms()アクションのGetリクエストテスト
 * - UserMail->isUserMailSend() = false, emailあり
 *
 * @return void
 */
	public function testGetWOIsUserMailSendWithEmail() {
		//事前準備
		$this->_mockForReturnFalse('UserManager.UserMail', 'isUserMailSend');
		$user = array('User' => array(
			'id' => '2',
			'password' => 'aaaa',
			'email' => 'room_administrator@exapmle.com',
			'handlename' => 'Room Administrator',
			'username' => 'room_administrator',
		));
		$this->controller->Components->Session
			->expects($this->any())->method('read')
			->will($this->returnValue($user));

		//テスト実行
		$this->_testGetAction(
			array('action' => 'user_roles_rooms'), array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$this->__assert();
		$this->assertEquals($this->vars['isNotify'], false);
	}

/**
 * user_roles_rooms()アクションのGetリクエストテスト
 * - UserMail->isUserMailSend() = true, emailあり
 *
 * @return void
 */
	public function testGetWithIsUserMailSendWithEmail() {
		//事前準備
		$this->_mockForReturnTrue('UserManager.UserMail', 'isUserMailSend');
		$user = array('User' => array(
			'id' => '2',
			'password' => 'aaaa',
			'email' => 'room_administrator@exapmle.com',
			'handlename' => 'Room Administrator',
			'username' => 'room_administrator',
		));
		$this->controller->Components->Session
			->expects($this->any())->method('read')
			->will($this->returnValue($user));

		//テスト実行
		$this->_testGetAction(
			array('action' => 'user_roles_rooms'), array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		$this->__assert();
		$this->assertEquals($this->vars['isNotify'], true);
	}

/**
 * user_roles_rooms()アクションのチェック
 *
 * @return void
 */
	private function __assert() {
		$this->view = preg_replace('/[>][\s]+([^a-z])/u', '>$1', $this->view);
		$this->view = preg_replace('/[\s]+</u', '<', $this->view);

		$expected = array('2', '5', '11', '12');
		$actual = array_keys($this->vars['rolesRooms']);
		sort($actual);
		$this->assertEquals($actual, $expected);

		$expected = array('1', '2', '3', '4', '5');
		$this->assertEquals($expected, Hash::extract($this->vars['rolesRooms'], '2.{s}.RolesRoom.id'));

		$expected = array('12', '13', '14', '15', '16');
		$this->assertEquals($expected, Hash::extract($this->vars['rolesRooms'], '5.{s}.RolesRoom.id'));

		$expected = array('22', '23', '24', '25', '26');
		$this->assertEquals($expected, Hash::extract($this->vars['rolesRooms'], '11.{s}.RolesRoom.id'));

		$expected = array('27', '28', '29', '30', '31');
		$this->assertEquals($expected, Hash::extract($this->vars['rolesRooms'], '12.{s}.RolesRoom.id'));

		$this->assertEquals('5', Hash::get($this->vars['rolesRoomsUsers'], 'RolesRoomsUser.2.roles_room_id'));
		$this->assertEquals('6', Hash::get($this->vars['rolesRoomsUsers'], 'RolesRoomsUser.3.roles_room_id'));
		$this->assertEquals('10', Hash::get($this->vars['rolesRoomsUsers'], 'RolesRoomsUser.4.roles_room_id'));
		$this->assertEquals('16', Hash::get($this->vars['rolesRoomsUsers'], 'RolesRoomsUser.5.roles_room_id'));

		$this->assertInput('form', null, '/user_manager/user_add/user_roles_rooms', $this->view);
		$this->assertInput('input', '_method', 'POST', $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][2][id]', null, $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][2][room_id]', '2', $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][2][user_id]', null, $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][5][id]', null, $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][5][room_id]', '5', $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][5][user_id]', null, $this->view);

		$this->assertInput('input', 'data[RolesRoomsUser][11][id]', null, $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][11][room_id]', '11', $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][11][user_id]', null, $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][11][roles_room_id]', '0', $this->view);

		$this->assertInput('input', 'data[RolesRoomsUser][12][id]', null, $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][12][room_id]', '12', $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][12][user_id]', null, $this->view);
		$this->assertInput('input', 'data[RolesRoomsUser][12][roles_room_id]', '0', $this->view);
	}

/**
 * user_roles_rooms()アクションのPOSTリクエストテスト
 * - 通知しない場合
 *
 * @return void
 */
	public function testPostSuccess() {
		//事前準備
		$this->controller->Components->Session
			->expects($this->exactly(2))->method('delete')
			->will($this->returnValue(true));

		$this->controller->Flash->expects($this->once())
			->method('set')
			->with(__d('net_commons', 'Successfully saved.'));

		$this->_mockForReturnTrue('Users.User', 'saveUser');

		//テストデータ
		$data = array();

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'user_roles_rooms'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_manager/user_manager/index', $header['Location']);
	}

/**
 * user_roles_rooms()アクションのPOSTリクエストテスト
 * - 通知する場合
 *
 * @return void
 */
	public function testPostGotoNotify() {
		//事前準備
		$user = array('UserAdd' => array('User' => array(
			'id' => '2',
			'password' => 'aaaa',
			'email' => 'room_administrator@exapmle.com',
			'handlename' => 'Room Administrator',
			'username' => 'room_administrator',
		)));

		$this->controller->Components->Session
			->expects($this->any())->method('read')
			->will($this->returnCallback(function ($name) use ($user) {
				return Hash::get($user, $name);
			}));

		$this->controller->Flash->expects($this->once())
			->method('set')
			->with(__d('net_commons', 'Successfully saved.'));

		$this->controller->Components->Session
			->expects($this->once())->method('write')
			->with('UserAdd', $user['UserAdd']);

		$this->_mockForReturnCallback('Users.User', 'saveUser', function () use ($user) {
			$user['UserAdd']['User']['password'] = md5($user['UserAdd']['User']['password']);
			return $user['UserAdd'];
		});

		//テストデータ
		$data = array(
			'_UserManager' => array('notify' => true)
		);

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'user_roles_rooms'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_manager/user_add/notify', $header['Location']);
	}

/**
 * user_roles_rooms()アクションのPOSTリクエストテスト
 * - ごみデータ(Avatar)の削除テスト
 *
 * @return void
 */
	public function testPostTmpAvatarDelete() {
		//事前準備
		$avatarPath = TMP . 'logo.gif';

		$tmpName = App::pluginPath('UserManager') . 'Test' . DS . 'Fixture' . DS . 'logo.gif';
		(new File($tmpName))->copy($avatarPath);
		$this->assertTrue(file_exists($avatarPath));

		$user = array('UserAdd' => array('User' => array(
			'id' => '2',
			'password' => 'aaaa',
			'email' => 'room_administrator@exapmle.com',
			'handlename' => 'Room Administrator',
			'username' => 'room_administrator',
			'avatar' => array(
				'tmp_name' => $avatarPath
			)
		)));
		$this->controller->Components->Session
			->expects($this->any())->method('read')
			->will($this->returnCallback(function ($name) use ($user) {
				return Hash::get($user, $name);
			}));

		//テスト実行
		$this->testPostSuccess();
	}

/**
 * ValidateUserテスト
 *
 * @return void
 */
	public function testValidationError() {
		//事前準備
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array('NetCommons.NetCommons' => array('handleValidationError'))
		));

		//ログイン
		TestAuthGeneral::login($this);

		$this->_mockForReturnFalse('Users.User', 'saveUser');

		$this->controller->NetCommons
			->expects($this->once())->method('handleValidationError')
			->will($this->returnValue(true));

		//テストデータ
		$data = array();

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'user_roles_rooms'), null, 'view');

		//チェック
		$this->__assert();
		$this->assertEquals($this->vars['isNotify'], false);
	}

}
