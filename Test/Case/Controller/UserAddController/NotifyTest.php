<?php
/**
 * UserAddController::notify()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserAddController::notify()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserMailController
 */
class UserAddControllerNotifyTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.mails.mail_setting_fixed_phrase',
		'plugin.user_attributes.user_attribute_layout',
	);

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
		$this->_testGetAction(array('action' => 'notify'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assert();
	}

/**
 * POSTリクエストデータ生成
 *
 * @return array リクエストデータ
 */
	private function __dataPost() {
		$data = array(
			'UserMail' => array(
				'title' => 'User mail title',
				'body' => 'User mail body',
				'user_id' => '2',
				'reply_to' => 'system_admin@exapmle.com'
			)
		);

		$this->_mockForReturnTrue('UserManager.UserMail', 'validates');

		$this->controller->mail = $this->getMock(
			'NetCommonsMail',
			array('sendMailDirect', 'setReplyTo', 'initPlugin', 'to', 'setFrom'),
			array(), '', false
		);
		$this->controller->mail->mailAssignTag = $this->getMock(
			'NetCommonsMail',
			array('setFixedPhraseSubject', 'setFixedPhraseBody', 'initPlugin'),
			array(), '', false
		);

		$this->controller->mail->mailAssignTag
			->expects($this->once())->method('setFixedPhraseSubject')
			->with($data['UserMail']['title']);
		$this->controller->mail->mailAssignTag
			->expects($this->once())->method('setFixedPhraseBody')
			->with($data['UserMail']['body']);
		$this->controller->mail->mailAssignTag
			->expects($this->once())->method('initPlugin')
			->with('2');
		$this->controller->mail
			->expects($this->once())->method('setReplyTo')
			->with($data['UserMail']['reply_to']);
		$this->controller->mail
			->expects($this->once())->method('initPlugin')
			->with('2');
		$this->controller->mail
			->expects($this->once())->method('to')
			->will($this->returnValue(true));
		$this->controller->mail
			->expects($this->once())->method('setFrom')
			->with('2');

		return $data;
	}

/**
 * POSTリクエストデータ生成
 *
 * @return array リクエストデータ
 */
	private function __dataOnValidationError() {
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
		//テストデータ
		$data = $this->__dataPost();

		$this->controller->mail
			->expects($this->once())->method('sendMailDirect')
			->will($this->returnValue(true));

		$this->controller->Flash->expects($this->once())
			->method('set')
			->with(__d('user_manager', 'Successfully mail send.'));

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'notify'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertNotEmpty($header['Location']);
	}

/**
 * notify()アクションのPOSTリクエストで入力値不正のテスト
 *
 * @return void
 */
	public function testNotifyPostOnBadRequest() {
		//テストデータ
		$data = $this->__dataPost();

		$this->controller->mail
			->expects($this->once())->method('sendMailDirect')
			->will($this->returnValue(false));

		$this->controller->Flash->expects($this->once())
			->method('set')
			->with(__d('net_commons', 'Failed on validation errors. Please check the input data.'));

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'notify'), null, 'view');
	}

/**
 * notify()アクションのPOSTリクエストで入力値不正のテスト
 *
 * @return void
 * @throws BadRequestException
 */
	public function testNotifyPostOnMailSettingError() {
		//テストデータ
		$data = $this->__dataPost();

		$this->controller->mail
			->expects($this->once())->method('sendMailDirect')
			->will($this->returnCallback(function () {
				throw new BadRequestException();
			}));

		$this->controller->Flash->expects($this->once())
			->method('set')
			->with(__d('mails', 'There is errors in the mail settings. It was not able to send mail.'));

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'notify'), null, 'view');
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
			__d('net_commons', 'Email')
		);

		$replyTo = 'aaaaa';
		$data = $this->__dataOnValidationError();
		$data = Hash::insert($data, 'UserMail.reply_to', $replyTo);

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'notify'), null, 'view');

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
	private function __assert($replyTo = null) {
		$this->assertInput('form', null, '/user_manager/user_add/notify', $this->view);
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
