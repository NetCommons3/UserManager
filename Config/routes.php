<?php
/**
 * Users routes configuration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

Router::connect(
	'/user_manager/user_manager/download/:user_id/:field_name/:size',
	['plugin' => 'user_manager', 'controller' => 'user_manager', 'action' => 'download'],
	['user_id' => '[0-9]+', 'size' => 'big|medium|small|thumb']
);
Router::connect(
	'/user_manager/user_manager/download/:user_id/:field_name',
	[
		'plugin' => 'user_manager',
		'controller' => 'user_manager',
		'action' => 'download',
		'size' => 'medium'
	],
	['user_id' => '[0-9]+', 'size' => 'medium']
);

Router::connect(
	'/user_manager/:controller/:action/:user_id',
	['plugin' => 'user_manager'],
	['user_id' => '[0-9]+', 'controller' => 'user_manager|users_roles_rooms']
);

Router::connect(
	'/user_manager/:controller/:action/*',
	array('plugin' => 'user_manager')
);
