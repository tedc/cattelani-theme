angular = require 'angular'
require 'angular-sanitize'
require '@uirouter/angularjs'
require 'angular-animate'
require 'angular-media-queries'
require 'ngmap'
require '@iamadamjowett/angular-click-outside'
require '../../bower_components/angular-bind-html-compile/angular-bind-html-compile'
require 'angular-smooth-scrollbar'
require 'angular-swiper'
require 'angular-load'
require 'angular-cookies'
#require 'angular-loading-bar'
window.controller = new ScrollMagic.Controller()

catellani = angular.module 'catellani', [
	'ngCookies', 'matchMedia', 'ui.router', 'ngMap', 'ngSanitize', 'ngAnimate', 'angularLoad', 'angular-click-outside', 'angular-bind-html-compile', 'ksSwiper', 'SmoothScrollbar']
require './models/index.coffee'
require './directives/index.coffee'
require './animations/index.coffee'
require './controllers/index.coffee'
require './resources/index.coffee'

angular.element(document).ready ->
	angular.bootstrap document, ['catellani']
	return