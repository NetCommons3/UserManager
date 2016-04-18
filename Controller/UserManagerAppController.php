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
 * ウィザード定数(一般設定)
 *
 * @var string
 */
	const WIZARD_USERS = 'user_manager';

/**
 * ウィザード定数(参加ルームの選択)
 *
 * @var string
 */
	const WIZARD_USERS_ROLES_ROOMS = 'users_roles_rooms';

/**
 * ウィザード定数(メール通知)
 *
 * @var string
 */
	const WIZARD_MAIL = 'user_mail';

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		//アクセスの権限
		'NetCommons.Permission' => array(
			'type' => PermissionComponent::CHECK_TYEP_SYSTEM_PLUGIN,
			'allow' => array()
		),
		'Security',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Wizard' => array(
			'navibar' => array(
				self::WIZARD_USERS => array(
					'url' => array(
						'controller' => 'user_manager', 'action' => 'add',
					),
					'label' => array('user_manager', 'General setting'),
				),
				self::WIZARD_USERS_ROLES_ROOMS => array(
					'url' => array(
						'controller' => 'users_roles_rooms', 'action' => 'edit', 'key2' => Space::ROOM_SPACE_ID,
					),
					'label' => array('user_manager', 'Select the rooms to join'),
				),
				self::WIZARD_MAIL => array(
					'url' => array(
						'controller' => 'user_mail', 'action' => 'save_notify',
					),
					'label' => array('user_manager', 'Notify user by e-mail'),
				),
			),
			'cancelUrl' => array('controller' => 'user_manager', 'action' => 'index')
		),
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('index', 'view');

		//ウィザードの設定
		if (in_array($this->params['action'], ['edit', 'save_notify'])) {
			$navibar = $this->helpers['NetCommons.Wizard']['navibar'];
			$navibar = Hash::insert($navibar, '{s}.url.key', $this->params['pass'][0]);
			$this->helpers['NetCommons.Wizard']['navibar'] = $navibar;
			$this->helpers['NetCommons.Wizard']['navibar'][self::WIZARD_USERS]['url']['action'] = 'edit';
		}
	}

}
