/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-24 12:09:23
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-30 11:02:16
*/

var app = angular.module('saci', ['ngRoute', 'ngResource', 'ngLoadScript', 'ui.mask']);
app.constant( 'URL', 'http://localhost/saci' );
app.constant( 'AEROPORTO', 1 );

app.factory( 'flash', function($rootScope) {
  var queue = [];
  var currentMessage = '';

  $rootScope.$on( '$routeChangeSuccess', function() {
    currentMessage = queue.shift() || '';
  });

  return {
    setMessage: function(message) {
      queue.push(message);
    },
    getMessage: function() {
      return currentMessage;
    }
  };
});