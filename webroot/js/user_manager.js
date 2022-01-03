/**
 * @fileoverview UserManager Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 */


/**
 * UserManager controller
 */
NetCommonsApp.controller('UserManagerController',
    ['$scope', 'NetCommonsModal', 'NC3_URL', function($scope, NetCommonsModal, NC3_URL) {

      /**
       * 選択したIDリスト
       */
      $scope.checkedIds = [];

      /**
       * チェックボックスの全選択・全解除
       */
      $scope.allCheck = function($event) {
        var elements = $('input[type="checkbox"]');

        for (var i = 0; i < elements.length; i++) {
          if (elements[i].name) {
            $scope._changeCheck(elements[i], $event.currentTarget.checked);
          }
        }
      };

      /**
       * チェックボックスクリック
       */
      $scope.check = function($event) {
        $scope._changeCheck($event.currentTarget, $event.currentTarget.checked);
      };

      /**
       * チェックボックス変更処理
       */
      $scope._changeCheck = function(element, checked) {
        var id = element.value;
        var domId = element.id;
        element.checked = checked;
        $scope.checkedIds[id] = checked;

        var trElement = $('#Tr' + domId);

        if (checked) {
          if (trElement.hasClass('warning')) {
            trElement.removeClass('warning');
            trElement.addClass('_warning');
          } else if (trElement.hasClass('danger')) {
            trElement.removeClass('danger');
            trElement.addClass('_danger');
          }

          if (! trElement.hasClass('success')) {
            trElement.addClass('success');
          }
        } else {
          if (trElement.hasClass('_warning')) {
            trElement.removeClass('_warning');
            trElement.addClass('warning');
          } else if (trElement.hasClass('_danger')) {
            trElement.removeClass('_danger');
            trElement.addClass('danger');
          }

          if (trElement.hasClass('success')) {
            trElement.removeClass('success');
          }
        }
      };

      /**
       * 一括登録処理
       */
      $scope.bulk = function($event, action, firstMessage, secondMessage, notSelectMessage) {
        var checkedIds = [];
        angular.forEach($scope.checkedIds, function(checked, id) {
          if (checked) {
            checkedIds.push(id);
          }
        }, checkedIds);
        if (! checkedIds.length) {
          alert(notSelectMessage);
          $event.preventDefault();
          return;
        }

        if (! confirm(firstMessage)) {
          $event.preventDefault();
          return;
        }

        if (secondMessage && ! confirm(secondMessage)) {
          $event.preventDefault();
          return;
        }

        var checkedElement = $('#UserManagerBulkCheckedIds');
        checkedElement[0].value = checkedIds.join(',');

        var submitElement = $('#UserManagerBulkSubmit');
        submitElement[0].value = action;

        $scope.sending = true;
        var formElement = $('#UserManagerBulkBulkForm');
        formElement.submit();
      };

      /**
       * 検索ダイアログ表示
       *
       * @param {array} conditions 条件配列
       * @param {string} callbackUrl callbackするURL
       * @return {void}
       */
      $scope.showUserSearch = function(conditions, callbackUrl) {
        console.log(callbackUrl);
        NetCommonsModal.show(
            $scope, 'UserManagerSearch',
            NC3_URL + '/user_manager/user_manager/search/conditions',
            {
              backdrop: 'static',
              size: 'lg',
              resolve: {
                options: {
                  conditions: conditions,
                  callbackUrl: callbackUrl
                }
              }
            }
        );
      };

      /**
       * Show user information method
       *
       * @param {number} users.id
       * @return {void}
       */
      $scope.showUser = function(id) {
        NetCommonsModal.show(
            $scope, 'UserManagerView',
            NC3_URL + '/user_manager/user_manager/view/' + id + ''
        );
      };
    }]);


/**
 * UserManager search condtion modal controller
 */
NetCommonsApp.controller('UserManagerSearch',
    ['$scope', '$http', '$uibModalInstance', '$window', 'options', 'NC3_URL',
      function($scope, $http, $uibModalInstance, $window, options, NC3_URL) {

        /**
         * 検索条件を保持する変数
         */
        $scope.conditions = options['conditions'];

        /**
         * 初期処理
         *
         * @return {void}
         */
        $scope.initialize = function(domId) {
          $scope.domId = domId;
        };

        /**
         * 検索処理
         *
         * @return {void}
         */
        $scope.search = function() {
          var element = angular.element('#' + $scope.domId);
          $scope.conditions = {};
          angular.forEach(element.serializeArray(), function(input) {
            if (input['value'] !== '') {
              this.conditions[input['name']] = input['value'];
            }
          }, $scope);

          $http.post(NC3_URL + '/user_manager/user_manager/search/result',
              $.param({_method: 'POST', data: $scope.conditions}),
              {cache: false,
                headers:
                    {'Content-Type': 'application/x-www-form-urlencoded'}
              }
          ).then(
          function(response) {
                //success condition
                $window.location.href = NC3_URL + options['callbackUrl'] + '?search';
                //$uibModalInstance.close('success');
          },
          function(response) {
                //error condition
                $uibModalInstance.dismiss('error');
          });
        };

        /**
         * キャンセル処理
         *
         * @return {void}
         */
        $scope.cancel = function() {
          $uibModalInstance.dismiss('cancel');
        };
      }]);


/**
 * User modal controller
 */
NetCommonsApp.controller('UserManagerView',
    ['$scope', '$uibModalInstance', function($scope, $uibModalInstance) {

      /**
       * dialog cancel
       *
       * @return {void}
       */
      $scope.cancel = function() {
        $uibModalInstance.dismiss('cancel');
      };
    }]);
