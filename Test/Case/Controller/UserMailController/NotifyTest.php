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
		$this->_testGetAction(array('action' => 'notify', 'key' => '1'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assert();
	}

/**
 * notify()アクションのチェック
 *
 * @return void
 */
	private function __assert() {
		debug($this->view);
		$this->assertInput('form', null, '/user_manager/user_mail/notify/1', $this->view);
		$this->assertInput('input', '_method', 'POST', $this->view);

		debug($this->controller->request->data);
	}

}
