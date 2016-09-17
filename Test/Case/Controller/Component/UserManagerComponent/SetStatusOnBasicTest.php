<?php
/**
 * UserManagerComponent::setStatusOnBasic()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');

/**
 * UserManagerComponent::setStatusOnBasic()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\Component\UserManagerComponent
 */
class UserManagerComponentSetStatusOnBasicTest extends UserManagerControllerTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'UserManager', 'TestUserManager');

		//テストコントローラ生成
		$this->generateNc('TestUserManager.TestUserManagerComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テストアクション実行
		$this->_testGetAction('/test_user_manager/test_user_manager_component/index',
				array('method' => 'assertNotEmpty'), null, 'view');
		$pattern = '/' . preg_quote('Controller/Component/TestUserManagerComponent', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
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

		//新規
		$result[0]['user'] = array();
		$result[0]['expected'] = array(
			7 => array(
				'id' => '7',
				'language_id' => '2',
				'user_attribute_id' => '11',
				'key' => 'status_1',
				'name' => '利用可能',
				'code' => '1',
				'weight' => '1',
			),
			8 => array(
				'id' => '8',
				'language_id' => '2',
				'user_attribute_id' => '11',
				'key' => 'status_0',
				'name' => '利用不可',
				'code' => '0',
				'weight' => '2',
			),
		);

		//利用可を編集
		$result[1]['user'] = array('User' => array('status' => '1'));
		$result[1]['expected'] = $result[0]['expected'];

		//利用不可を編集
		$result[2]['user'] = array('User' => array('status' => '0'));
		$result[2]['expected'] = $result[0]['expected'];

		//承認待ちを編集
		$result[3]['user'] = array('User' => array('status' => '2'));
		$result[3]['expected'] = $result[0]['expected'];
		$result[3]['expected'][9] = array(
			'id' => '9',
			'language_id' => '2',
			'user_attribute_id' => '11',
			'key' => 'status_2',
			'name' => '承認待ち',
			'code' => '2',
			'weight' => '3',
		);

		//本人の確認待ちを編集
		$result[4]['user'] = array('User' => array('status' => '3'));
		$result[4]['expected'] = $result[0]['expected'];
		$result[4]['expected'][10] = array(
			'id' => '10',
			'language_id' => '2',
			'user_attribute_id' => '11',
			'key' => 'status_3',
			'name' => '承認済み',
			'code' => '3',
			'weight' => '4',
		);

		return $result;
	}

/**
 * setStatusOnBasic()のテスト
 *
 * @param array $user ユーザデータ
 * @param array $expected 期待値
 * @dataProvider dataProvider
 * @return void
 */
	public function testSetStatusOnBasic($user, $expected) {
		//テスト実行
		$this->controller->UserManager->setStatusOnBasic($user);

		$result = $this->controller->viewVars['userAttributes']['1']['1']['7'];
		$this->assertEquals($result['UserAttribute']['key'], 'status');

		$result['UserAttributeChoice'] = Hash::remove($result['UserAttributeChoice'], '{n}.created');
		$result['UserAttributeChoice'] = Hash::remove($result['UserAttributeChoice'], '{n}.created_user');
		$result['UserAttributeChoice'] = Hash::remove($result['UserAttributeChoice'], '{n}.modified');
		$result['UserAttributeChoice'] = Hash::remove($result['UserAttributeChoice'], '{n}.modified_user');

		$this->assertEquals($result['UserAttributeChoice'], $expected);
	}

}
