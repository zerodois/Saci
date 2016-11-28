/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-26 11:31:36
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-27 17:29:39
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
	self.editar = editar;

	load( 'Aeroporto' );
	load( 'Companhia' );


	function editar( id ) {
		console.log(`:/ ${id}`);
		//$location.path(`${URL}/edit/${id}`);
	}

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