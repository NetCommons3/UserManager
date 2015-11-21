<?php
/**
 * UserSearchForm Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FormHelper', 'View/Helper');

/**
 * UserSearchForm Helper
 *
 * @package NetCommons\Users\View\Helper
 */
class UserSearchFormHelper extends FormHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.NetCommonsForm',
	);

/**
 * Default Constructor
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
	//public function __construct(View $View, $settings = array()) {
	//	parent::__construct($View, $settings);
	//}

/**
 * Generates a form input element complete with label and wrapper div
 *
 * @param array $userAttribute user_attribute data
 * @param array $options Each type of input takes different options.
 * @return string Completed form widget.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#creating-form-elements
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
	public function userSearchInput($userAttribute, $options = array()) {
//		//後で@SuppressWarnings(PHPMD.CyclomaticComplexity)を消す
//
		$html = '';
//		$dataTypeKey = $userAttribute['DataTypeTemplate']['data_type_key'];
//		$dataTypeTemplateKey = $userAttribute['DataTypeTemplate']['key'];
//		$userAttributeKey = $userAttribute['UserAttribute']['key'];
//
//		//パスワードは項目表示しない
//		if ($dataTypeTemplateKey === 'password') {
//			return '';
//		}
//
//		if ($dataTypeKey === 'img') {
//			//あり、なし、指定なしのラジオボタン
//			$dataTypeKey = 'radio';
//			$options = array(
//				//未着手
//			);
//		} elseif ($dataTypeKey === 'radio' || $dataTypeKey === 'checkbox' || $dataTypeKey === 'select') {
//			//ラジオボタン、チェックボタン、セレクトボタン
//			$options = Hash::combine($userAttribute, 'UserAttributeChoice.{n}.key', 'UserAttributeChoice.{n}.name');
//		}
//
//		if ($userAttributeKey === 'password_modified' || $userAttributeKey === 'last_login') {
//			$dataTypeKey = 'datetime';
//		}
//
//		//$html .= '<ul class="user-attribute-edit">';
//		//$html .= '<li class="list-group-item form-group">';
//		$html .= '<div class="form-group">';
//
//		switch ($dataTypeKey) {
//			case 'radio':
//				$html .= '<strong>' . h($userAttribute['UserAttribute']['name']) . '</strong>';
//				break;
//
//			case 'checkbox':
//				$html .= '<strong>' . h($userAttribute['UserAttribute']['name']) . '</strong>';
//				break;
//
//			case 'select':
//				$html .= '<strong>' . h($userAttribute['UserAttribute']['name']) . '</strong>';
//				break;
//
//			case 'datetime':
//				if ($userAttributeKey === 'last_login') {
//					//最終ログイン日時の場合、ラベル変更(○日以上ログインしていない、○日以内ログインしている)
//				} else {
//					//○日以上前、○日以内
//				}
//				$html .= '<strong>' . h($userAttribute['UserAttribute']['name']) . '</strong>';
//				break;
//
//			default:
//				$html .= $this->Form->input($userAttribute['UserAttribute']['key'], array(
//					'type' => 'text',
//					'label' => $userAttribute['UserAttribute']['name'],
//					'class' => 'form-control',
//				));
//		}
//
//		//$html .= '</li>';
//		//$html .= '</ul>';
//		$html .= '</div>';

		return $html;
	}

}
