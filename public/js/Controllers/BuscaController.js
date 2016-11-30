/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-26 11:31:36
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-30 16:00:23
*/

angular.module( 'saci' ).controller( 'BuscaController', BuscaController );

function BuscaController( $scope, $resource, URL, $location, flash ) {

	$scope.title = 'Buscar voos';
	$scope.color = 2;
	$scope.URL   = URL;
	$scope.flash = flash;
	$scope.loading = false;

	var self     = this;
	self.submit  = submit;
	self.results = 0;
	self.arr  = {};
	self.changeStatus = changeStatus;

	load( 'Aeroporto' );
	load( 'Companhia' );

	function changeStatus( id ) {
		var promise = $.post( `${URL}/Controller/Edicao.php`, { codigo: id, status: self.arr[ id ].status });
	}

	function submit() {
		$scope.loading = true;
		console.log(self.data);
		var $Search  = $resource(`${URL}/Controller/Busca.php`);
		var $promise = $Search.get( self.data ).$promise;
		$promise.then( json => {
			$scope.loading = false;
			if( !json.data ) {
				self.arr = {};
				self.results = 0;
			}
			self.results = json.data.length;
			json.data.forEach( el => { self.arr[ el.codigo ] = el; self.arr[ el.codigo ].status = el.status });
		}, err=>{ $scope.loading = false; });
	}

	function load( model ) {
		var $req    = $resource(`${URL}/Controller/${model}.php`);
		var promise = $req.get().$promise;
		promise.then( json => { self[ model ] = json; } );
	}


}
BuscaController['$inject'] = [ '$scope', '$resource', 'URL', '$location', 'flash' ];