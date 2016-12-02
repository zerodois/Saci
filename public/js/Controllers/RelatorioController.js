/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-28 18:57:24
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-12-01 23:36:35
*/

var app = angular.module('saci');
app.controller( 'RelatorioController', RelatorioController );

function RelatorioController( $scope, URL, $resource ) {

	$scope.title = 'Gerar relatório';
	$scope.color = 4;
	$scope.URL   = URL;
	$scope.loading = false;

	var self = this;
	self.submit  = submit;
	self.types   = [ 'Voos semanais por país', 'Contagem de voos de uma companhia', 'Número de voos cancelados por companhia', 'Ranking de voos com mais escalas' ];
	self.resp    = undefined;

	load('Companhia');

	function submit() {
		self.errors = [];
		$scope.loading = true;
		$req  = $resource(`${URL}/Controller/Relatorio.php`);
		$prom = $req.get( self.form ).$promise;
		$prom.then( json => {
			if( json.error ) self.errors = json.error;
			$scope.loading = false;
			self.resp = json;
		}, err=>{$scope.loading = false;} );
	}

	function load( model ) {
		var $req    = $resource(`${URL}/Controller/${model}.php`);
		var promise = $req.get().$promise;
		promise.then( json => { self[ model ] = json; } );
	}


}
RelatorioController['$inject'] = [ '$scope', 'URL', '$resource' ];