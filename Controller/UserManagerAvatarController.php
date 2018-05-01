<?php
/**
 * UsersAvatarController
 *
 * @copyright Copyright 2014, NetCommons Project
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('Controller', 'Controller');
App::uses('AuthComponent', 'Controller/Component');
App::uses('AppModel', 'Model');

/**
 * UsersAvatarController
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller
 */
class UserManagerAvatarController extends Controller {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommons',
		'Files.Download',
	);

/**
 * beforeRender
 *
 * @return void
 */
	public function beforeRender() {
		// WysiwygImageControllerDownloadTest::testDownloadGet 用の処理
		// @see https://github.com/NetCommons3/NetCommons/blob/3.1.2/Controller/NetCommonsAppController.php#L241
		// @see https://github.com/NetCommons3/NetCommons/blob/3.1.2/Controller/Component/NetCommonsComponent.php#L58
		App::uses('NetCommonsAppController', 'NetCommons.Controller');
		$this->NetCommons->renderJson();
	}

/**
 * download method
 *
 * @return void
 * @throws NotFoundException
 */
	public function download() {
		/* @var $User AppModel */
		/* @var $UserAttributeSetting AppModel */
		// シンプルにしたかったためAppModelを利用。インスタンス生成時少し速かった。
		$User = $this->_getSimpleModel('User');
		$User->Behaviors->load('Users.Avatar');
		// @see https://github.com/NetCommons3/Users/blob/3.1.2/Model/Behavior/AvatarBehavior.php#L42
		$User->plugin = 'Users';
		ClassRegistry::removeObject('User');
		ClassRegistry::removeObject('AvatarBehavior');

		$params = $this->_getBindParamsForUser();
		$User->bindModel($params);

		$query = $this->_getQueryForUser();
		$user = $User->find('first', $query);
		ClassRegistry::removeObject('UploadFile');

		if (!$user ||
				!$user['UploadFile']['id']) {
			return $this->_downloadNoImage($User, $user);
		}

		//会員管理が使えない場合、NoImageを出力する
		$PluginsRole = $this->_getSimpleModel('PluginsRole');
		$query = $this->_getQueryForPluginsRole();
		if (! $PluginsRole->find('count', $query)) {
			return $this->_downloadNoImage($User, $user);
		}

		$options = [
			'size' => $this->params['size'],
		];

		return $this->Download->doDownloadByUploadFileId($user['UploadFile']['id'], $options);
	}

/**
 * download method
 *
 * @param Model $User User model(AppModel)
 * @param array $user User data
 * @return void
 */
	protected function _downloadNoImage($User, $user) {
		$fieldName = $this->request->params['field_name'];
		$fieldSize = $this->request->params['size'];

		// @see https://github.com/NetCommons3/Users/blob/3.1.2/Model/Behavior/AvatarBehavior.php#L123-L125
		App::uses('User', 'Users.Model');

		$this->response->file(
			$User->temporaryAvatar($user, $fieldName, $fieldSize),
			array('name' => 'No Image')
		);

		return $this->response;
	}

/**
 * download method
 *
 * @param string $modelName Model name
 * @return void
 */
	protected function _getSimpleModel($modelName) {
		// TestでAvatarBehavior::temporaryAvatar をMock にしているため、removeObjectしない。
		// ClassRegistry::removeObject($modelName);
		$Model = ClassRegistry::init($modelName);
		$params = [
			'belongsTo' => [
				'TrackableCreator',
				'TrackableUpdater',
			]
		];
		$Model->unbindModel($params);
		$Model->Behaviors->unload('Trackable');

		return $Model;
	}

/**
 * get bind params for User
 *
 * @return void
 */
	protected function _getBindParamsForUser() {
		$params = [
			'hasOne' => [
				'UploadFile' => [
					'className' => 'UploadFile',
					'foreignKey' => false,
					'conditions' => [
						'UploadFile.plugin_key' => $this->plugin,
						'UploadFile.content_key = User.id',
						'UploadFile.field_name' => $this->request->params['field_name'],
					],
					'fields' => ['id']
				]
			],
		];

		return $params;
	}

/**
 * get query for User
 *
 * @return void
 */
	protected function _getQueryForUser() {
		$query = [
			'conditions' => [
				'User.id' => $this->request->params['user_id'],
				//@see https://github.com/NetCommons3/Users/blob/3.1.2/Controller/UsersController.php#L105-L111
				//@see https://github.com/NetCommons3/Users/blob/3.1.2/Model/Behavior/UserPermissionBehavior.php#L31-L33
				'User.is_deleted' => '0',
			],
			'recursive' => 0,
			'callbacks' => false,
		];

		return $query;
	}

/**
 * get query for PluginsRole
 *
 * @return void
 */
	protected function _getQueryForPluginsRole() {
		$query = [
			'conditions' => [
				'PluginsRole.role_key' => AuthComponent::user('role_key'),
				'PluginsRole.plugin_key' => $this->plugin,
			],
			'recursive' => -1,
			'callbacks' => false,
		];

		return $query;
	}

}
