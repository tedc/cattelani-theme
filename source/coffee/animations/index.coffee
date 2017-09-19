catellani = angular.module 'catellani'
catellani
	# .animation '.search__cell', ->
	# 	search =
	# 		leave : (element, done)->
	# 			tl = new TimelineMax()
	# 			w = element[0].offsetWidth
	# 			content = element[0].querySelector '.search__content'
	# 			mask = element[0].querySelector '.search__mask'
	# 			tl
	# 				.set content,
	# 					width : w
	# 				.to mask, .5,
	# 					scaleY : 1
	# 				.to element, .5,
	# 					width : 0
	# 					onComplete : ->
	# 						TweenMax.set [mask, element],
	# 							clearProps : 'all'
	# 						done()
	# 						return
	# 			return
	# 		enter : (element, done)->
	# 			tl = new TimelineMax()
	# 			w = element[0].offsetWidth
	# 			content = element[0].querySelector '.search__content'
	# 			mask = element[0].querySelector '.search__mask'
	# 			tl
	# 				.set content,
	# 					width : w
	# 				.set mask,
	# 					scaleY : 1
	# 				.fromTo element, .5,
	# 					{	
	# 						width : 0
	# 					}
	# 					{
	# 						width : w
	# 					}
	# 				.to mask, .5,
	# 					scaleY : 0
	# 					onComplete : ->
	# 						TweenMax.set [mask, element, content],
	# 							clearProps : 'all'
	# 						done()
	# 						return
	# 			return
	.animation '.banner', ["$timeout", "$rootScope", require './menu.coffee']
	.animation '.modal', ["$rootScope", "$timeout",  require './modal.coffee']
	.animation '.manifesto__item', ["$rootScope", "$timeout", require './manifesto.coffee']
	.animation '.storia', ["$rootScope", "$timeout", ($rootScope)->
		storia =
			addClass : (element, className, done)->
				return if className isnt 'storia__slider--years-visible'
				$rootScope.$broadcast 'swiperChaged'
				return
			removeClass : (element, className, done)->
				return if className isnt 'storia__slider--years-visible'
				$rootScope.$broadcast 'swiperChaged'
				return
	]
	.animation '.accordion', ["$rootScope", "$timeout", require './faq.coffee']
	.animation '.view', ["$rootScope", "$timeout", "$state", require './view.coffee']