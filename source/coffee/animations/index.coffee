catellani = angular.module 'catellani'
catellani
	.animation '.banner', ["$timeout", "$rootScope", require './menu.coffee']
	.animation '.modal', ["$rootScope", "$timeout",  require './modal.coffee']
	.animation '.manifesto__item', ["$rootScope", "$timeout", require './manifesto.coffee']
	.animation '.storia', ["$rootScope", "$timeout", ($rootScope)->
		storia =
			addClass : (element, className, done)->
				return if className isnt 'storia--visible'
				$rootScope.$broadcast 'swiperChaged'
				return
			removeClass : (element, className, done)->
				return if className isnt 'storia--visible'
				$rootScope.$broadcast 'swiperChaged'
				return
	]
	.animation '.accordion', ["$rootScope", "$timeout", require './faq.coffee']
	.animation '.view', ["$rootScope", "$timeout", "$state", require './view.coffee']