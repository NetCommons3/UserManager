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
