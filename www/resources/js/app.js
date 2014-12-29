(function(){
	var app = angular.module('queryTerminal',['ngRoute','validation.match']);
	
	
	app.config(['$routeProvider', function($routeProvider) {
	  $routeProvider
	    .when("/settings", { templateUrl: 'resources/views/settings.html', controller: 'SettingsController' })
	    .otherwise( {templateUrl: 'resources/views/dashboard.php' } );
	}]);
	
	app.controller('SidebarController',function() {
		this.items = [ { id:1, name:'connection1' }, { id:2, name:'connection2' } ];
	});
	app.controller('TopNavController',function() {
		this.items = [  { label: 'Settings', href: '#/settings' }, { label: 'Sign Out', href: '/?signout=y' }];
	});
	
	app.controller('SettingsController',function() {
	
	});
	
	app.controller('PasswordController',function() {
	  this.setPassword = function(user) {
	  
	  }
	});
	
})();