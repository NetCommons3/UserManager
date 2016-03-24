<?php
/**
 * UserMailController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserMailController::beforeFilter()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserMailController
 */
class UserMailControllerBeforeFilterTest extends NetCommonsControllerTestCase {

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
	public function testBeforeFilterGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'notify', 'key' => '1'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assert();
	}

/**
 * index()アクションのGetリクエストのExceptionErrorテスト
 *
 * @return void
 */
	public function testBeforeFilterGetOnExceptionError() {
		//テスト実行
		$this->_testGetAction(array('action' => 'notify', 'key' => '99999'), null, 'BadRequestException', 'view');
	}

/**
 * notify()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testBeforeFilterPost() {
		//テスト実行
		$this->_testPostAction('post', array('UserMail' => ['user_id' => '1']), array('action' => 'notify', 'key' => '1'), null, 'view');

		//チェック
		$this->__assert();
	}

/**
 * notify()アクションのチェック
 *
 * @return void
 */
	public function __assert() {
		$this->assertInternalType('array', $this->vars['user']);

		$this->assertEquals('1', $this->vars['user']['id']);
		$this->assertEquals('system_administrator', $this->vars['user']['username']);
		$this->assertEquals('system_admin', $this->vars['user']['key']);
		$this->assertEquals('system_administrator', $this->vars['user']['role_key']);
		$this->assertEquals('System Administrator', $this->vars['user']['handlename']);

		$this->assertEquals('System Administrator', $this->vars['userName']);
		$this->assertEquals('1', $this->vars['activeUserId']);
	}

}
