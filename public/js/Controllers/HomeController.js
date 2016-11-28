/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-24 12:21:18
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-27 21:53:00
*/

var app = angular.module('saci');
app.controller( 'HomeController', HomeController );

function HomeController( $resource, URL, $scope, $routeParams ) {

	var self = this;
	self.search = search;
	self.form = {};
	self.editar = $routeParams.id ? true : false;
	self.data = {};
	self.data.Aeroporto = [];
	self.data.Companhia = [];
	self.field      = '';
	self.createData = {};
	self.createData.escala = [];
	self.setVal    = setVal;
	self.setEscala = setEscala;
	self.remove    = remove;
	self.cadastrar = cadastrar;
	self.init      = init;

	if( $routeParams.id ) {
		$scope.title = 'Edição de voos';
		$scope.color = 3;
		self.init();
	} else {
		$scope.title = 'Cadastro de voos';
		$scope.color = 1;		
	}

	function remove( type, ix ) { 
		var el;
		if( ix===undefined ) { 
			el = document.getElementById( `list-${self.createData[ type ].codigo}` );
			self.createData[ type ] = null;
		} else {
			el = document.getElementById( `list-${self.createData.escala[ ix ].el.codigo}` );
			self.createData.escala.splice( ix, 1 );
		}
		if(el) el.style.display = 'block';
	};

	function setVal( value, key ) { if(key) self.createData[ key ] = value; };
	function setEscala( value, index ) { self.createData.escala.push( { ix:index, el:value } ); };
	
	function search( data, destiny, promise ){

		let $Service = $resource( `${URL}/Controller/${destiny}.php` );
		if( promise ) return $Service.get( data ).$promise;
		let $promise = $Service.get( data ).$promise;
		//Atribui o resultado da busca ao vetor aeroporto
		$promise.then( json=>{ self.data[ destiny ] = json.data; });
	};

	function init() {
		let $promise = self.search( { codigo: $routeParams.id }, 'Busca', true );
		$promise.then(json =>{
			var obj = json.data[0];
			console.log( obj );
			self.form.status = obj.status;
			self.form.data_partida = obj.data_partida;
			self.form.hora_partida = obj.hora_partida;
			self.form.data_chegada = obj.data_chegada;
			self.form.hora_chegada = obj.hora_chegada;
			self.form.modelo_aeronave = obj.a.modelo;
			self.createData.origem = { codigo: obj.o.codigo, nome: obj.o.nome, cidade: obj.o.cidade, estado: obj.o.estado };
			self.createData.destino = { codigo: obj.d.codigo, nome: obj.d.nome, cidade: obj.d.cidade, estado: obj.d.estado };
			self.createData.companhia = obj.companhia.codigo;
			obj.escalas.forEach(escala=>{ self.createData.escala.push({el: escala}); });
		}, err=>{ console.log(err) });
	}

	function cadastrar() {

		let data = self.form;
		data.cod_origem = self.createData.origem.codigo;
		data.cod_destino = self.createData.destino.codigo;
		data.escalas = self.createData.escala;
		data.companhia = self.createData.companhia;
		if( self.editar )
			data.edit = $routeParams.id

		let $promise   = $.post( `${URL}/Controller/Voo.php`, data );
		//Atribui o resultado da busca ao vetor aeroporto
		$promise.then( json => { console.log(json) }, err=>{console.log(err);} );
	}

}
HomeController['$inject'] = [ '$resource', 'URL', '$scope', '$routeParams' ];