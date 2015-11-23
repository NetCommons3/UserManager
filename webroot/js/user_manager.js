/**
 * @fileoverview UserManager Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 */


/**
 * UserManager controller
 */
NetCommonsApp.controller('UserManager.controller', function(
    $scope, $http, $window, NetCommonsModal) {

      /**
       * Show condition form method
       *
       * @param {number} users.id
       * @return {void}
       */
      $scope.showUserSearch = function(condtions) {
        NetCommonsModal.show(
            $scope, 'UserManager.search',
            $scope.baseUrl + '/user_manager/user_manager/search/conditions',
            {
              backdrop: 'static',
              size: 'lg',
              resolve: {
                condtions: condtions
              }
            }
        ).result.then(
            function(result) {
              var searchUrl = $scope.baseUrl +
                                '/user_manager/user_manager/search/result';
              $http.get(searchUrl, {params: result, cache: false})
                .success(function() {
                    $window.location.reload();
                  });
            }
        );
      };
    });


/**
 * UserManager search condtion modal controller
 */
NetCommonsApp.controller('UserManager.search', function(
    $scope, $modalInstance, condtions) {

      /**
       * Search conditions
       */
      $scope.condtions = condtions;

      /**
       * Dialog search
       *
       * @return {void}
       */
      $scope.search = function() {
        console.log('search 1');
        if (! $scope.condtions) {
          $scope.condtions = {a: 'aaa'};
        }
        $modalInstance.close($scope.condtions);
      };

      /**
       * Dialog cancel
       *
       * @return {void}
       */
      $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
      };
    });
