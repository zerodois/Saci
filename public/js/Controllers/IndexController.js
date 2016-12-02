/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-30 08:09:12
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-30 10:25:57
*/

var app = angular.module('saci');
app.controller( 'IndexController', IndexController );

function IndexController( $scope, URL, flash ) {

	$scope.title = 'Sistema de gerenciamento de voos'
	$scope.color = 5;
	$scope.URL   = URL;
	$scope.flash = flash;

}
IndexController['$inject'] = [ '$scope', 'URL', 'flash' ];