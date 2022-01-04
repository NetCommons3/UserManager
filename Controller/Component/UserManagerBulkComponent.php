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

\App::uses('Component', 'Controller');
\App::uses('UserSearchCompComponent', 'Users.Controller/Component');
\App::uses('UserManagerSearchLib', 'UserManager.Lib');
\App::uses('RoomsLibCommandExec', 'Rooms.Lib');
\App::uses('UserAttributeChoice', 'UserAttributes.Model');
\App::uses('RoomsLibCommandExec', 'Rooms.Lib');

/**
 * UserManager Component
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller\Component
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UserManagerBulkComponent extends Component {

/**
 * コントローラ
 *
 * @var UserManagerController|Controller
 */
	public $controller;

/**
 * Userモデル
 *
 * @var User
 */
	public $User;

/**
 * UserRoleSettingモデル
 *
 * @var UserRoleSetting
 */
	public $UserRoleSetting;

/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param Controller $controller Controller with components to startup
 * @return void
 */
	public function startup(Controller $controller) {
		$this->controller = $controller;
		$this->User = ClassRegistry::init('Users.User');
		$this->UserRoleSetting = ClassRegistry::init('UserRoles.UserRoleSetting');
	}

/**
 * 選択した会員を一括で利用不可に設定する
 *
 * @return CakeResponse|null
 * @throws BadRequestException
 */
	public function bulkNonactive() {
		$controller = $this->controller;
		$data = $controller->request->data['UserManagerBulk'];

		$displayIds = explode(',', $data['displayIds']);
		$checkedIds = explode(',', $data['checkedIds']);

		//バリデーション
		try {
			if (! $this->__validateBulk($checkedIds, $displayIds)) {
				//通常この条件に来ない。
				//ただし、jsonで処理された場合、当処理に入ってくるため、念のため抜けておく。
				return;
			}
		} catch (Exception $ex) {
			throw $ex;
		}

		//登録処理
		$users = $this->__findBulkUsers($checkedIds);
		try {
			//トランザクションBegin
			$this->User->begin();

			foreach ($users as $user) {
				if (! UserManagerSearchLib::hasEditableBulkUser($user)) {
					throw new BadRequestException(__d('net_commons', 'Bad Request'));
				}

				//ステータス更新処理
				$this->User->updateStatus($user['User']['id'], \UserAttributeChoice::STATUS_CODE_NONACTIVE);
			}

			//トランザクションCommit
			$this->User->commit();
		} catch (Exception $ex) {
			//トランザクションRollback
			$this->User->rollback($ex);
		}

		//リダイレクト
		$controller->NetCommons->setFlashNotification(
			__d('net_commons', 'Successfully saved.'), array('class' => 'success')
		);
		return $controller->redirect($controller->referer('/user_manager/user_manager/index/'));
	}

/**
 * 選択した会員を一括で削除する
 *
 * @return CakeResponse|null
 * @throws BadRequestException
 * @throws InternalErrorException
 */
	public function bulkDelete() {
		$controller = $this->controller;
		$data = $controller->request->data['UserManagerBulk'];

		$displayIds = explode(',', $data['displayIds']);
		$checkedIds = explode(',', $data['checkedIds']);

		//バリデーション
		if (! $this->__validateBulk($checkedIds, $displayIds)) {
			//通常この条件に来ない。
			//ただし、jsonで処理された場合、当処理に入ってくるため、念のため抜けておく。
			return;
		}

		//削除処理
		$users = $this->__findBulkUsers($checkedIds);
		try {
			//トランザクションBegin
			$this->User->begin();

			foreach ($users as $user) {
				if (! UserManagerSearchLib::hasEditableBulkUser($user)) {
					throw new BadRequestException(__d('net_commons', 'Bad Request'));
				}

				//削除処理
				if (! $this->User->deleteUser($user, false)) {
					//本来あり得ないが、この処理に入ってきたら、エラーログに出力して、
					//throwを投げる
					$error = [
						'user' => $user,
						'validationErrors' => $this->User->validationErrors,
					];
					\CakeLog::error(__METHOD__ . '(' . __LINE__ . ') ' . var_export($error, true));
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}

			//トランザクションCommit
			$this->User->commit();

			//全てが削除されたら、シェルを起動
			RoomsLibCommandExec::deleteRelatedRooms();
		} catch (Exception $ex) {
			//トランザクションRollback
			$this->User->rollback($ex);
		}

		//リダイレクト
		$controller->NetCommons->setFlashNotification(
			__d('net_commons', 'Successfully deleted.'), array('class' => 'success')
		);

		return $controller->redirect($this->__makeDeleteRedirectUrl($data));
	}

/**
 * 一括削除のリダイレクトURLを生成する
 *
 * @param array $data リクエストデータ
 * @return string
 */
	private function __makeDeleteRedirectUrl($data) {
		$controller = $this->controller;

		$referer = $controller->referer('/user_manager/user_manager/index/');
		$hasPrev = $data['hasPrev'] ?? false;
		$hasNext = $data['hasNext'] ?? false;
		$hasAdminUser = $data['hasAdminUser'] ?? false;
		if (! $hasPrev ||
				$hasNext ||
				$hasAdminUser ||
				$data['displayIds'] !== $data['checkedIds']) {
			//先頭ページか、最終ページではない、全選択されていない場合
			return $referer;
		} else {
			//先頭ページではなく、最終ページで、全選択されている場合
			$urlPath = parse_url($referer, PHP_URL_PATH);
			if (! $urlPath) {
				$urlPath = $referer;
			}
			$match = [];
			if (preg_match('#/page:([0-9]+)#iu', $urlPath, $match)) {
				$urlPath = preg_replace('#/page:([0-9]+)#iu', '', $urlPath);
				if (substr($urlPath, -1) !== '/') {
					$urlPath .= '/';
				}
				$urlPath .= 'page:' . ((int)$match[1] - 1);
			}

			$urlQuery = parse_url($referer, PHP_URL_QUERY);
			return $urlPath . ($urlQuery ? '?' . $urlQuery : '');
		}
	}

/**
 * 選択した会員を一括処理できるかどうかチェックする
 *
 * @param array $checkedIds チェックしているユーザIDリスト
 * @param array $displayIds 画面に表示しているユーザIDリスト
 *
 * @return bool
 */
	private function __validateBulk($checkedIds, $displayIds) {
		if (count(array_diff($checkedIds, $displayIds)) > 0) {
			$this->controller->throwBadRequest();
			return false;
		}

		$count = $this->User->find('count', [
			'recursive' => -1,
			'conditions' => [
				'id' => $checkedIds
			],
		]);
		if ($count !== count($checkedIds)) {
			$this->controller->throwBadRequest();
			return false;
		}

		return true;
	}

/**
 * 一括処理するユーザを取得
 *
 * @param array $checkedIds チェックしているユーザIDリスト
 *
 * @return array
 */
	private function __findBulkUsers($checkedIds) {
		$users = $this->User->find('all', [
			'recursive' => -1,
			'fields' => [
				$this->User->alias . '.id',
				$this->User->alias . '.handlename',
				$this->User->alias . '.role_key',
				$this->UserRoleSetting->alias . '.origin_role_key',
			],
			'conditions' => [
				$this->User->alias . '.id' => $checkedIds,
			],
			'joins' => [
				[
					'table' => $this->UserRoleSetting->table,
					'alias' => $this->UserRoleSetting->alias,
					'type' => 'INNER',
					'conditions' => [
						$this->User->alias . '.role_key' . ' = ' . $this->UserRoleSetting->alias . '.role_key'
					],
				]
			]
		]);
		if (empty($users)) {
			$this->controller->throwBadRequest();
			return false;
		}

		return $users;
	}

}
