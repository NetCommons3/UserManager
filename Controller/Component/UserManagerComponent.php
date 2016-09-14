<?php
/**
 * UserManager Component
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');
App::uses('UserRole', 'UserRoles.Model');
App::uses('UserAttributeChoice', 'UserAttributes.Model');
App::uses('Current', 'NetCommons.Utility');

/**
 * UserManager Component
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller\Component
 */
class UserManagerComponent extends Component {

/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param Controller $controller Controller with components to startup
 * @return void
 */
	public function startup(Controller $controller) {
		$this->controller = $controller;
	}

/**
 * 基本項目の登録処理の前準備
 *
 * @return void
 */
	public function prepareBasicSave() {
		$controller = $this->controller;

		$controller->User->userAttributeData = Hash::combine($controller->viewVars['userAttributes'],
			'{n}.{n}.{n}.UserAttribute.id', '{n}.{n}.{n}'
		);

		foreach ($controller->User->userAttributeData as $attribute) {
			if ($attribute['UserAttributeSetting']['is_multilingualization']) {
				$controller->SwitchLanguage->fields[] = 'UsersLanguage.' . $attribute['UserAttribute']['key'];
			}
		}

		//他言語が入力されていない場合、表示されている言語データをセット
		$controller->SwitchLanguage->setM17nRequestValue();
	}

/**
 * システム管理者以外は、選択肢からシステム管理者を除外
 *
 * @return void
 */
	public function setUserRoleAdminOnBasic() {
		$controller = $this->controller;

		//システム管理者以外は、選択肢からシステム管理者を除外
		if (UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR !== Current::read('User.role_key')) {
			$controller->viewVars['userAttributes'] = Hash::remove(
				$controller->viewVars['userAttributes'],
				'{n}.{n}.{n}.UserAttributeChoice.{n}[key=' . UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR . ']'
			);
		}
	}

/**
 * システム管理者以外は、選択肢からシステム管理者を除外
 *
 * @param array $user ユーザデータ
 * @return void
 */
	public function setStatusOnBasic($user) {
		$controller = $this->controller;

		//状態の選択肢から承認待ち、承認済みを除外
		if (Hash::get($user, 'User.status') !== UserAttributeChoice::STATUS_CODE_WAITING) {
			$controller->viewVars['userAttributes'] = Hash::remove(
				$controller->viewVars['userAttributes'],
				'{n}.{n}.{n}.UserAttributeChoice.{n}[key=' . UserAttributeChoice::STATUS_KEY_WAITING . ']'
			);
		}
		if (Hash::get($user, 'User.status') !== UserAttributeChoice::STATUS_CODE_APPROVED) {
			$controller->viewVars['userAttributes'] = Hash::remove(
				$controller->viewVars['userAttributes'],
				'{n}.{n}.{n}.UserAttributeChoice.{n}[key=' . UserAttributeChoice::STATUS_KEY_APPROVED . ']'
			);
		}
	}

/**
 * アップロードファイルの退避
 * ※Unitテストできるように別メソッドにしておく
 *
 * @param string $tmpName アップロードのtmp_nameパス
 * @param string $destPath TMPの一時退避しておくパス
 * @return void
 */
	public function moveUploadedFile($tmpName, $destPath) {
		return move_uploaded_file($tmpName, $destPath);
	}
}
