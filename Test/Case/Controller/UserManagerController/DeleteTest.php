<?php
/**
 * UserManagerController::delete()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');

/**
 * UserManagerController::delete()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserManagerController
 */
class UserManagerControllerDeleteTest extends UserManagerControllerTestCase {

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
 * delete()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testDeleteGet() {
		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testGetAction(array('action' => 'delete'), null, 'BadRequestException', 'view');
	}

/**
 * delete()アクションのPOSTリクエストテスト
 *
 * @return void
 */
	public function testDeletePost() {
		//ログイン
		TestAuthGeneral::login($this);

		//テストデータ
		$userId = '2';

		//テスト実行
		$this->_testPostAction('post', array('User' => array('id' => $userId)),
				array('action' => 'delete'), 'BadRequestException', 'view');
	}

/**
 * delete()アクションのテスト
 *
 * @return void
 */
	public function testDelete() {
		//テストデータ
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array(
				'NetCommons.NetCommons' => array('setFlashNotification'),
			)
		));

		//ログイン
		TestAuthGeneral::login($this);

		$userId = '2';
		$this->_mockForReturnTrue('Users.User', 'deleteUser');
		$this->controller->NetCommons
			->expects($this->once())->method('setFlashNotification')
			->with(__d('net_commons', 'Successfully deleted.'), array('class' => 'success'));

		//テスト実行
		$this->_testPostAction('delete', array('User' => array('id' => $userId)),
				array('action' => 'delete'), null, 'view');
	}

/**
 * 唯一のシステム管理者の削除テスト
 *
 * @return void
 */
	public function testOnlyAdminDeleteWOCanDelete() {
		//ログイン
		TestAuthGeneral::login($this);

		//テストデータ
		$userId = '1';

		//テスト実行
		$this->_testPostAction('delete', array('User' => array('id' => $userId)),
				array('action' => 'delete'), 'BadRequestException', 'view');
	}

/**
 * サイト管理者がシステム管理者の削除するテスト
 *
 * @return void
 */
	public function testAdminDeleteWOCanDelete() {
		//ログイン
		TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_CHIEF_EDITOR);

		//テストデータ
		$userId = '1';

		//テスト実行
		$this->_testPostAction('delete', array('User' => array('id' => $userId)),
				array('action' => 'delete'), 'BadRequestException', 'view');
	}

}
