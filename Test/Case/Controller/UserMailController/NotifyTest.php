<?php
/**
 * UserMailController::notify()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserMailController::notify()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserMailController
 */
class UserMailControllerNotifyTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_manager';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'user_mail';

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
 * notify()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testNotifyGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'notify', 'key' => '2'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assert();
	}

/**
 * POSTリクエストデータ生成
 *
 * @return array リクエストデータ
 */
	private function __data() {
		$data = array(
			'UserMail' => array(
				'title' => '',
				'body' => '',
				'user_id' => '2',
				'reply_to' => 'system_admin@exapmle.com'
			)
		);
		return $data;
	}

/**
 * notify()アクションのPOSTリクエストテスト
 *
 * @return void
 */
	public function testNotifyPost() {
		//テスト実行
		$this->_mockForReturnTrue('UserManager.UserMail', 'saveMail');

		$this->controller->Components->Session
			->expects($this->once())->method('setFlash')
			->with(__d('net_commons', 'Successfully saved.'));

		$this->_testPostAction('post', $this->__data(),
				array('action' => 'notify', 'key' => '2'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertNotEmpty($header['Location']);
	}

/**
 * notify()アクションのPOSTリクエストのValidationErrorテスト
 *
 * @return void
 */
	public function testNotifyPostOnValidationError() {
		//テストデータ
		$this->validationMessage['title'] = sprintf(
			__d('net_commons', 'Please input %s.'),
			__d('user_manager', 'Mail title')
		);
		$this->validationMessage['body'] = sprintf(
			__d('net_commons', 'Please input %s.'),
			__d('user_manager', 'Mail body')
		);
		$this->validationMessage['reply_to'] = sprintf(
			__d('net_commons', 'Unauthorized pattern for %s. Please input the data in %s format.'),
			__d('user_manager', 'Reply to mail address'),
			__d('net_commons', 'email')
		);

		$replyTo = 'aaaaa';
		$data = $this->__data();
		$data = Hash::insert($data, 'UserMail.reply_to', $replyTo);

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'notify', 'key' => '2'), null, 'view');

		//チェック
		$this->__assert($replyTo);
		$this->assertTextContains($this->validationMessage['title'], $this->view);
		$this->assertTextContains($this->validationMessage['body'], $this->view);
		$this->assertTextContains($this->validationMessage['reply_to'], $this->view);
	}

/**
 * notify()アクションのチェック
 *
 * @param string $replyTo 返信用メールアドレス
 * @return void
 */
	private function __assert($replyTo = 'system_admin@exapmle.com') {
		$this->assertInput('form', null, '/user_manager/user_mail/notify/2', $this->view);
		$this->assertInput('input', '_method', 'POST', $this->view);
		$this->assertInput('input', 'data[UserMail][user_id]', '2', $this->view);
		$this->assertInput('input', 'data[UserMail][to_address]', 'room_administrator@exapmle.com', $this->view);
		$this->assertInput('input', 'data[UserMail][reply_to]', $replyTo, $this->view);
		$this->assertInput('input', 'data[UserMail][title]', '', $this->view);
		$this->assertInput('input', 'data[UserMail][body]', '', $this->view);

		$expected = array(
			'UserMail' => array(
				'title' => '',
				'body' => '',
				'user_id' => '2',
				'reply_to' => $replyTo
			)
		);
		$this->assertEquals($expected, $this->controller->request->data);
	}

}
