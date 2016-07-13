/**
 * @fileoverview Links Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 */


/**
 * UsersRolesRooms Javascript
 *
 * @param {string} Controller name
 * @param {function($scope, $http, $window)} Controller
 */
NetCommonsApp.controller('UsersRolesRooms', ['$scope', '$http', function($scope, $http) {

  /**
   * initialize
   *
   * @return {void}
   */
  $scope.initValue = function(domId, value) {
    $scope[domId] = value;
  };

}]);
