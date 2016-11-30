/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-26 11:31:36
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-28 18:56:41
*/

angular.module( 'saci' ).controller( 'BuscaController', BuscaController );

function BuscaController( $scope, $resource, URL, $location ) {

	$scope.title = 'Buscar voos';
	$scope.color = 2;
	$scope.URL   = URL;

	var self     = this;
	self.submit  = submit;
	self.results = [];
	self.arr  = {};
	self.changeStatus = changeStatus;

	load( 'Aeroporto' );
	load( 'Companhia' );

	function changeStatus( id ) {
		var promise = $.post( `${URL}/Controller/Edicao.php`, { codigo: id, status: self.arr[ id ].status });
	}

	function submit() {
		var $Search  = $resource(`${URL}/Controller/Busca.php`);
		var $promise = $Search.get().$promise;
		$promise.then( json => {
			json.data.forEach( el => { self.arr[ el.codigo ] = el; self.arr[ el.codigo ].status = el.status });
		});
	}

	function load( model ) {
		var $req    = $resource(`${URL}/Controller/${model}.php`);
		var promise = $req.get().$promise;
		promise.then( json => { self[ model ] = json; } );
	}


}
BuscaController['$inject'] = [ '$scope', '$resource', 'URL', '$location' ];