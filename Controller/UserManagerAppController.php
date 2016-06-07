<?php
/**
 * UserManagerApp Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');
App::uses('Space', 'Rooms.Model');

/**
 * UserManagerApp Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller
 */
class UserManagerAppController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
		'NetCommons.Permission' => array(
			'type' => PermissionComponent::CHECK_TYEP_SYSTEM_PLUGIN,
			'allow' => array()
		),
		'Security',
	);

/**
 * 登録処理の前準備
 *
 * @return void
 */
	protected function _prepareSave() {
		$this->User->userAttributeData = Hash::combine($this->viewVars['userAttributes'],
			'{n}.{n}.{n}.UserAttribute.id', '{n}.{n}.{n}'
		);

		foreach ($this->User->userAttributeData as $attribute) {
			if ($attribute['UserAttributeSetting']['is_multilingualization']) {
				$this->SwitchLanguage->fields[] = 'UsersLanguage.' . $attribute['UserAttribute']['key'];
			}
		}

		//他言語が入力されていない場合、表示されている言語データをセット
		$this->SwitchLanguage->setM17nRequestValue();
	}

}
