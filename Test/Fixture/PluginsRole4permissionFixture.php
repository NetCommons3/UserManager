<?php
/**
 * アクセス権限(Permission)テスト用Fixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('PluginsRoleFixture', 'PluginManager.Test/Fixture');

/**
 * アクセス権限(Permission)テスト用Fixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Fixture
 */
class PluginsRole4permissionFixture extends PluginsRoleFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'PluginsRole';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'plugins_roles';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'role_key' => 'administrator',
			'plugin_key' => 'test_user_manager',
		),
		array(
			'role_key' => 'system_administrator',
			'plugin_key' => 'test_user_manager',
		),
	);

}
