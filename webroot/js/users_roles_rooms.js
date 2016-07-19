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

  /**
   * All select
   *
   * @return {void}
   */
  $scope.selectAll = function(roomRoleKey, spaceId) {
    var elements = $('input[data-input-key=' + roomRoleKey + '_' + spaceId + ']');
    angular.forEach(elements, function(el) {
      el.checked = true;
      el = angular.element(el);

      var domId = el.attr('data-dom-id');
      var value = el.attr('value');
      $scope[domId] = value;
    });
  };

}]);
