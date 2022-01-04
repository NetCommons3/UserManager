<?php
/**
 * 会員管理の一覧Helperに関するライブラリ
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

\App::uses('UserRole', 'UserRoles.Model');

/**
 * 会員管理の一覧に関するライブラリ
 *
 * @package NetCommons\UserManager\Lib
 */
class UserManagerSearchLib {

/**
 * 一括操作できるユーザかどうか
 *
 * @param array $user ユーザデータ
 * @return bool
 */
	public static function hasEditableBulkUser($user) {
		$roleKey = $user['UserRoleSetting']['origin_role_key'] ?? null;
		return ! in_array($roleKey, \UserRole::$systemRoles, true);
	}

}
