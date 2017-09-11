<?php
/**
 * UserAddController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');

/**
 * UserAddController::beforeFilter()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserAddController
 */
class UserAddControllerBeforeFilterTest extends UserManagerControllerTestCase {

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
 * basic()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testBasic() {
		//テスト実行
		$this->_testGetAction(array('action' => 'basic'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$expected = array(
			'navibar' => array(
				'user_manager' => array(
					'url' => array(
						'controller' => 'user_add',
						'action' => 'basic',
					),
					'label' => array(
						'user_manager', 'General setting',
					),
				),
				'users_roles_rooms' => array(
					'url' => array(
						'controller' => 'user_add',
						'action' => 'user_roles_rooms',
						'key2' => '4',
					),
					'label' => array(
						'user_manager', 'Select the rooms to join',
					),
				),
				'user_mail' => array(
					'url' => array(
						'controller' => 'user_add',
						'action' => 'notify',
					),
					'label' => array(
						'user_manager', 'Notify user by e-mail',
					),
				),
			),
			'cancelUrl' => array(
				'controller' => 'user_manager',
				'action' => 'index',
			),
		);
		$this->assertEquals($this->controller->helpers['NetCommons.Wizard'], $expected);
	}

/**
 * notify()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testNotify() {
		//テストコントローラ生成
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array('Session' => array('read'))
		));

		//ログイン
		TestAuthGeneral::login($this);

		$user = array(
			'id' => '',
			'email' => '',
			'handlename' => '',
			'username' => '',
		);
		if (Configure::read('debug')) {
			$exactly = 2;
		} else {
			$exactly = 1;
		}
		$this->controller->Session
			->expects($this->exactly($exactly))->method('read')
			->will($this->returnCallback(function ($key) use ($user) {
				if ($key === 'UserAdd') {
					return array('User' => $user);
				} else {
					return null;
				}
			}));

		//テスト実行
		$this->_testGetAction(array('action' => 'notify'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$expected = array(
			'navibar' => array(
				'user_manager' => array(
					'label' => array(
						'user_manager', 'General setting',
					),
				),
				'users_roles_rooms' => array(
					'label' => array(
						'user_manager', 'Select the rooms to join',
					),
				),
				'user_mail' => array(
					'url' => array(
						'controller' => 'user_add',
						'action' => 'notify',
					),
					'label' => array(
						'user_manager', 'Notify user by e-mail',
					),
				),
			),
			'cancelUrl' => array(
				'controller' => 'user_manager',
				'action' => 'index',
			),
		);
		$this->assertEquals($this->controller->helpers['NetCommons.Wizard'], $expected);
	}

}
