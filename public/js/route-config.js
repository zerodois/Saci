/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-24 12:32:10
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-30 08:10:41
*/


angular.module('saci')
	.config(function( $routeProvider ){
    
    $routeProvider.when('/', {
			templateUrl: 'public/partials/home.html',
			controller: 'IndexController',
			controllerAs: 'Index'
		});

		$routeProvider.when('/new', {
			templateUrl: 'public/partials/cadastro.html',
			controller: 'HomeController',
			controllerAs: 'Home'
		});

		$routeProvider.when('/search', {
			templateUrl: 'public/partials/busca.html',
			controller: 'BuscaController',
			controllerAs: 'Busca'
		});

		$routeProvider.when('/edit/:id', {
			templateUrl: 'public/partials/cadastro.html',
			controller: 'HomeController',
			controllerAs: 'Home'
		});

		$routeProvider.when('/report', {
			templateUrl: 'public/partials/relatorio.html',
			controller: 'RelatorioController',
			controllerAs: 'Relatorio'
		});

  });