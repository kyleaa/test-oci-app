'use strict';

app.factory('User',['$http','$q', function($http, $q) {
  
  var User = {
    getCurrent: function() {
      var defer = $q.defer();
      $http.get('/api/user/$self.json').success(function(data) {
        defer.resolve(data);
      });
      
      return defer.promise;
    }
  };
  
  return User;
}]);