/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-24 12:21:18
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-12-02 03:46:24
*/

var app = angular.module('saci');
app.controller( 'HomeController', HomeController );

function HomeController( $resource, URL, $scope, $routeParams, AEROPORTO, $location, flash, $timeout ) {

	var self = this;
	self.search = search;
	self.form = {};
	self.editar = $routeParams.id ? true : false;
	self.data = {};
	//self.data.Aeroporto = [];
	//self.data.Companhia = [];
	self.field      = '';
	self.createData = {};
	self.size       = { Aeroporto:0,  };
	self.createData.escala = [];
	self.setVal    = setVal;
	self.setEscala = setEscala;
	self.remove    = remove;
	self.cadastrar = cadastrar;
	self.init      = init;
	self.validate  = validate;
	self.errors    = [];
	self.warning   = [];
	self.alert     = false;
	self.clear     = clear;
	self.filterSelect = filterSelect;

	function filterSelect(obj) {
		let exibe = true;
		if( self.createData.origem && obj.codigo == self.createData.origem.codigo ) return false;
		if( self.createData.destino && obj.codigo == self.createData.destino.codigo ) return false;
		for( let i=0; i<self.createData.escala.length; i++ )
			if( self.createData.escala[i].el.codigo == obj.codigo ) { exibe = false; break; }
		return exibe;
	}

	$scope.loading = false;
	$scope.URL 		 = URL;
	if( $routeParams.id ) {
		$scope.title = 'Edição de voos';
		$scope.color = 3;
		self.init();
	} else {
		$scope.title = 'Cadastro de voos';
		$scope.color = 1;		
	}

	function clear() {
		self.form.intervalo_replica = '';
		self.form.qtd_replica = '';
	}

	function validate( ) {

		self.warning = [];
		var alert = false;
		if( !self.createData.origem )
			self.warning = [ 'Você não inseriu uma origem para o voo' ];
		if( !self.createData.destino )
			self.warning.push( 'Você não inseriu um destino para o voo' );
		if( self.warning.length ) return false;
		
		if( self.createData.origem.codigo != AEROPORTO && self.createData.destino.codigo != AEROPORTO )		
			alert = true;
		if( alert )
			self.createData.escala.forEach( el=>{ console.log(AEROPORTO); if( el.el.codigo == AEROPORTO ) alert = false; } );
		if( alert ) {
			self.alert = true;
			return self.warning = [ 'Aeroporto de Congonhas não está no trajeto informado' ];
		}

		self.warning = [];
		self.alert = false;
	}

	function remove( type, ix ) { 
		self.size.Aeroporto++;
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

	function setVal( value, key ) { if(key) self.createData[ key ] = value; self.size.Aeroporto--; };
	function setEscala( value, index ) { self.createData.escala.push( { ix:index, el:value } ); self.size.Aeroporto--; };
	
	function search( data, destiny, promise ){

		$scope.loading = true;
		self.size[ destiny ] = 0;
		let $Service = $resource( `${URL}/Controller/${destiny}.php` );
		if( promise ) return $Service.get( data ).$promise;
		let $promise = $Service.get( data ).$promise;
		//Atribui o resultado da busca ao vetor aeroporto
		$promise.then( json=>{ $scope.loading = false; self.data[ destiny ] = json.data || []; self.size[ destiny ] = json.data.length; }, err=>{console.log(err);});
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
			self.data.Companhia = [ obj.companhia ];
			obj.escalas.forEach(escala=>{ self.createData.escala.push({el: escala}); });
		}, err=>{ console.log(err) });
	}

	function cadastrar() {

		let data = self.form;
		data.cod_origem = self.createData.origem.codigo;
		data.cod_destino = self.createData.destino.codigo;
		data.escalas = self.createData.escala;
		data.companhia = self.createData.companhia;
		$scope.loading = true;
		if( self.editar )
			data.edit = $routeParams.id

		let $promise   = $.post( `${URL}/Controller/Voo.php`, data );
		//Atribui o resultado da busca ao vetor aeroporto
		$promise.then( success, err=>{ $scope.loading = false; } );
	}

	function success( ret ) {
		$timeout(function () {
			$scope.loading = false;
			var json = JSON.parse( ret );
			if ( json.erro ) return self.errors = json.erro;
			flash.setMessage( json.mensagem );
			//console.log( json );
			if( self.editar )
		    $location.path( '/search' );
		  else
		  	$location.path( '/' );
    }, 0);
	}

}
HomeController['$inject'] = [ '$resource', 'URL', '$scope', '$routeParams', 'AEROPORTO', '$location', 'flash', '$timeout' ];