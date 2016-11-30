/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-25 02:05:28
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-25 02:05:39
*/

angular.module('saci')
	.factory('WebService', ['$http', function($http){
		function request( method, path, data ) {
			return $http({
				method: method,
				url 	: path,
				data 	: data
			});
		}
		return { 
			get: function(path, data) {
				return request('GET', path, data);
			},
			post: function(path, data) {
				return request('POST', path, data);
			},
			put: function(path, data) {
				return request('PUT', path, data);
			},
			delete: function(path, data) {
				return request('DELETE', path, data);
			},
			err: function( err ) {
				console.log( err );
			}
		};
	}]);
