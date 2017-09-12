catellani = angular.module 'catellani'
catellani
	.controller 'HomeController', ["$rootScope", "data", "$stateParams", "$scope", require './home.coffee']