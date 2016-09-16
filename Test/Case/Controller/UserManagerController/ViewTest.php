<?php
/**
 * UserManagerController::view()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');

/**
 * UserManagerController::view()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserManagerController
 */
class UserManagerControllerViewTest extends UserManagerControllerTestCase {

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
 * setStatusOnBasic()テストのDataProvider
 *
 * ### 戻り値
 *  - user ステータス
 *  - expected 期待値
 *
 * @return array データ
 */
	public function dataProvider() {
		$result = array();

		$result[0]['userId'] = '1';
		$result[1]['userId'] = '2';
		$result[2]['userId'] = '4';
		$result[3]['userId'] = '5';
		$result[4]['userId'] = '7';
		$result[5]['userId'] = '9999';

		return $result;
	}

/**
 * システム管理者がログインした場合のテスト
 *
 * @param int $userId ユーザID
 * @return void
 * @dataProvider dataProvider
 */
	public function testSystemAdmin($userId) {
		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		if ($userId === '7' || $userId === '9999') {
			$this->_testGetAction(
				array('action' => 'view', 'user_id' => $userId), null, 'BadRequestException', 'view'
			);
		} else {
			$this->_testGetAction(
				array('action' => 'view', 'user_id' => $userId), null, null, 'view'
			);

			//チェック
			$expected = $this->__getExpected($userId);
			$this->__assertUser($expected);

			$this->assertTextContains(
				'/user_manager/user_manager/edit/' . Hash::get($expected, 'User.id'), $this->view
			);
			$this->__assertUserView($expected);

			$this->assertTextContains(
				'/user_manager/users_roles_rooms/edit/' . Hash::get($expected, 'User.id'), $this->view
			);
			$this->__assertRoomsView($expected);

			if ($userId === '1') {
				$this->assertTextContains(
					__d('user_manager', 'All user attributes is displaying. ' .
									'If you want to set of the group, please setting from the handle of the header menu.'),
					$this->view
				);
			} else {
				$this->assertTextContains(
					__d('user_manager', 'All user attributes is displaying.'), $this->view
				);
			}
		}
	}

/**
 * システム管理者がログインした場合のテスト
 *
 * @param int $userId ユーザID
 * @return void
 * @dataProvider dataProvider
 */
	public function testSiteManager($userId) {
		//ログイン
		TestAuthGeneral::login($this, UserRole::USER_ROLE_KEY_ADMINISTRATOR);

		//テスト実行
		if ($userId === '7' || $userId === '9999') {
			$this->_testGetAction(
				array('action' => 'view', 'user_id' => $userId), null, 'BadRequestException', 'view'
			);
		} else {
			$this->_testGetAction(
				array('action' => 'view', 'user_id' => $userId), null, null, 'view'
			);

			//チェック
			$expected = $this->__getExpected($userId);
			$this->__assertUser($expected);

			if ($userId === '1') {
				$this->assertTextNotContains(
					'/user_manager/user_manager/edit/' . Hash::get($expected, 'User.id'), $this->view
				);
				$this->assertTextNotContains(
					'/user_manager/users_roles_rooms/edit/' . Hash::get($expected, 'User.id'), $this->view
				);
			} else {
				$this->assertTextContains(
					'/user_manager/user_manager/edit/' . Hash::get($expected, 'User.id'), $this->view
				);
				$this->assertTextContains(
					'/user_manager/users_roles_rooms/edit/' . Hash::get($expected, 'User.id'), $this->view
				);
			}
			$this->__assertUserView($expected);
			$this->__assertRoomsView($expected);

			$this->assertTextContains(
				__d('user_manager', 'All user attributes is displaying.'), $this->view
			);
		}
	}

/**
 * $this->viewVars['user']のチェック
 *
 * @param array $expected 期待値
 * @return void
 */
	private function __assertUser($expected) {
		//チェック
		$this->assertEquals($this->vars['user']['User']['id'], Hash::get($expected, 'User.id'));
		$this->assertEquals($this->vars['user']['UsersLanguage'][0]['id'], Hash::get($expected, 'UsersLanguage.0.id'));
		//$this->assertEquals($this->vars['user']['UsersLanguage'][1]['id'], Hash::get($expected, 'UsersLanguage.1.id'));
		$this->assertEquals($this->vars['user']['UsersLanguage'][0]['language_id'], '2');
		//$this->assertEquals($this->vars['user']['UsersLanguage'][1]['language_id'], '1');
		$this->assertEquals($this->vars['user']['UsersLanguage'][0]['user_id'], Hash::get($expected, 'User.id'));
		//$this->assertEquals($this->vars['user']['UsersLanguage'][1]['user_id'], Hash::get($expected, 'User.id'));
		$this->assertEquals($this->vars['user']['TrackableCreator']['id'], Hash::get($expected, 'TrackableCreator.id'));
		$this->assertEquals($this->vars['user']['TrackableUpdater']['id'], Hash::get($expected, 'TrackableUpdater.id'));
		$this->assertEquals($this->vars['user']['Role']['id'], Hash::get($expected, 'Role.id'));
		$this->assertEquals($this->vars['user']['UserRoleSetting']['id'], Hash::get($expected, 'UserRoleSetting.id'));

		if (isset($expected['UploadFile'])) {
			$this->assertEquals(
				$this->vars['user']['UploadFile']['avatar']['id'], Hash::get($expected, 'UploadFile.avatar.id')
			);
			$this->assertEquals(
				$this->vars['user']['UploadFile']['avatar']['content_key'], Hash::get($expected, 'UploadFile.avatar.content_key')
			);
		}
	}

/**
 * viewのチェック
 *
 * @param array $expected 期待値
 * @return void
 */
	private function __assertUserView($expected) {
		$this->assertTextContains(
			'/users/users/download/' . Hash::get($expected, 'User.id') . '/avatar/medium', $this->view
		);
		$this->assertTextContains(
			'>ログインID</div><div class="form-control nc-data-label">' . Hash::get($expected, 'User.username') . '<', $this->view
		);
		$this->assertTextContains(
			'>ハンドル</div><div class="form-control nc-data-label">' . Hash::get($expected, 'User.handlename') . '<', $this->view
		);
		$this->assertTextContains(
			'>' . Hash::get($expected, 'UsersLanguage.0.name') . '<', $this->view
		);
		$this->assertTextContains(
			'>' . Hash::get($expected, 'UsersLanguage.0.search_keywords') . '<', $this->view
		);
	}

/**
 * viewのチェック
 *
 * @param array $expected 期待値
 * @return void
 */
	private function __assertRoomsView($expected) {
		$view = preg_replace('/[>][\s]+([^a-z])/u', '>$1', $this->view);
		$view = preg_replace('/[\s]+</u', '<', $view);

		$this->assertTextContains('<td>Public</td>', $view);
		$this->assertTextContains('<td>Public room</td>', $view);

		if (Hash::get($expected, 'User.id') === '1' || Hash::get($expected, 'User.id') === '2') {
			$this->assertTextContains('<td>Community room 1</td>', $view);
			$this->assertTextContains('<td>Community room 2</td>', $view);

		} elseif (Hash::get($expected, 'User.id') === '4') {
			$this->assertTextNotContains('<td>Community room 1</td>', $view);
			$this->assertTextContains('<td>Community room 2</td>', $view);

		} elseif (Hash::get($expected, 'User.id') === '5') {
			$this->assertTextNotContains('<td>Community room 2</td>', $view);
			$this->assertTextContains('<td>' . __d('rooms', 'Not found.') . '</td>', $view);
		}
	}

/**
 * 期待値の取得
 *
 * @param int $userId 期待値
 * @return array
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	private function __getExpected($userId) {
		if ($userId === '1') {
			$expected = array(
				'User' => array(
					'id' => $userId,
					'username' => 'system_administrator',
					'handlename' => 'System Administrator',
				),
				'UsersLanguage' => array(
					0 => array(
						'id' => '1', 'name' => 'System Administrator Name', 'search_keywords' => 'default',
					)
				),
				'TrackableCreator' => array(
					'id' => '1',
				),
				'TrackableUpdater' => array(
					'id' => '1',
				),
				'Role' => array(
					'id' => '1',
				),
				'UserRoleSetting' => array(
					'id' => '1',
				),
			);
		} elseif ($userId === '2') {
			$expected = array(
				'User' => array(
					'id' => $userId,
					'username' => 'site_manager',
					'handlename' => 'Site Manager',
				),
				'UsersLanguage' => array(
					0 => array(
						'id' => '2', 'name' => 'Site Manager Name', 'search_keywords' => 'default',
					)
				),
				'TrackableCreator' => array(
					'id' => '1',
				),
				'TrackableUpdater' => array(
					'id' => '1',
				),
				'Role' => array(
					'id' => '2',
				),
				'UserRoleSetting' => array(
					'id' => '2',
				),
				'UploadFile' => array(
					'avatar' => array(
						'id' => '1',
						'content_key' => '2',
					),
				),
				'UploadFile' => array(
					'avatar' => array(
						'id' => '1',
						'content_key' => '2',
					),
				),
			);
		} elseif ($userId === '4') {
			$expected = array(
				'User' => array(
					'id' => $userId,
					'username' => 'editor',
					'handlename' => 'Editor',
				),
				'UsersLanguage' => array(
					0 => array(
						'id' => '4', 'name' => 'Editor Name', 'search_keywords' => 'default',
					)
				),
				'TrackableCreator' => array(
					'id' => '1',
				),
				'TrackableUpdater' => array(
					'id' => '1',
				),
				'Role' => array(
					'id' => '3',
				),
				'UserRoleSetting' => array(
					'id' => '3',
				),
			);
		} elseif ($userId === '5') {
			$expected = array(
				'User' => array(
					'id' => $userId,
					'username' => 'general_user',
					'handlename' => 'General User',
				),
				'UsersLanguage' => array(
					0 => array(
						'id' => '5', 'name' => 'General User Name', 'search_keywords' => 'default',
					)
				),
				'TrackableCreator' => array(
					'id' => '1',
				),
				'TrackableUpdater' => array(
					'id' => '1',
				),
				'Role' => array(
					'id' => '3',
				),
				'UserRoleSetting' => array(
					'id' => '3',
				),
			);
		}

		return $expected;
	}

}
