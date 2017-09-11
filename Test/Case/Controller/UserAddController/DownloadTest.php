<?php
/**
 * UserAddController::download()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');

/**
 * UserAddController::download()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserAddController
 */
class UserAddControllerDownloadTest extends UserManagerControllerTestCase {

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
 * download()アクションのアバターありテスト
 *
 * @return void
 */
	public function testAvatar() {
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array('Session' => array('read'))
		));

		//ログイン
		TestAuthGeneral::login($this);

		$path = App::pluginPath('UserManager') . 'Test' . DS . 'Fixture' . DS . 'logo.gif';
		$this->controller->Session
			->expects($this->once())->method('read')
			->will($this->returnValue($path));

		//テスト実行
		$this->_testGetAction(array('action' => 'download'), null, null, 'view');

		//チェック
		$this->assertEquals($this->controller->response->header()['Content-Length'], filesize($path));
	}

/**
 * download()アクションのアバターなし(noimageを表示)テスト
 *
 * @return void
 */
	public function testNoAvatar() {
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array('Session' => array('read'))
		));

		//ログイン
		TestAuthGeneral::login($this);

		$this->controller->Session
			->expects($this->once())->method('read')
			->will($this->returnValue(''));

		//テスト実行
		$this->_testGetAction(array('action' => 'download'), null, null, 'view');

		//チェック
		$path = App::pluginPath('Users') . 'webroot' . DS . 'img' . DS . 'noimage.gif';
		$this->assertEquals($this->controller->response->header()['Content-Length'], filesize($path));
	}

}
