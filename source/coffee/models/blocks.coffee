animationDiv = angular.element document.querySelector '.transitioner'
animationInner = angular.element document.querySelector '.transitioner__wrapper'
animationCover = angular.element document.querySelector '.transitioner__cover'
speed = 0.5*2.25
closeBlocks = (size)->
	animationDiv.removeClass 'transitioner--flex'
	animationDiv.removeClass 'transitioner--flex-start'
	animationDiv.removeClass 'transitioner--flex-end'
	animationInner.removeClass "transitioner__wrapper--s12"
	TweenMax.set animationCover,
		clearProps : 'width'
	return
	
exports.single = ($rootScope, $stateParams, $timeout, $q, PreviousState, screenSize)->
	body = angular.element document.body
	deferred = $q.defer()
	screenSize.rules = {
		min : "screen and (max-width: #{(850/16)}em)"
	}
	if screenSize.is 'min'
		deferred.resolve on
		return deferred.promise
	$rootScope.cantStart = on
	prev = if $rootScope.PreviousState.Name is '' then $rootScope.fromState else $rootScope.PreviousState.Name.replace 'app.', ''
	if not document.querySelector("[data-item-slug='#{$stateParams.slug}']")?
		body.removeClass 'is-transitioner'	
		$rootScope.cantStart = off
		deferred.resolve on
		return deferred.promise
	else
		body.addClass 'is-transitioner'
	$timeout ->
		$rootScope.isTransitionerActive = on
		return
	element = angular.element document.querySelector "[data-item-slug='#{$stateParams.slug}']"
	cover = element.attr 'data-item-background'
	item = element.attr 'data-carousel-item'
	total = element.attr 'data-item-total'
	size = element.attr 'data-item-size'
	animationInner.addClass "transitioner__wrapper--s#{size}"
	# TweenMax.set animationCover,
	# 	backgroundImage : "url(#{cover})"
	TweenMax.set animationInner,
		backgroundImage : "url(#{cover})"
	animationDiv.addClass 'transitioner--flex'
	if item is "0"
		animationDiv.addClass 'transitioner--flex-start'
	if item is total
		animationDiv.addClass 'transitioner--flex-end'
	#rect = animationDiv[0].getBoundingClientRect()
	$rootScope.transitionerSize = 12
	$rootScope.carouselIndex = item
	tl = new TimelineMax()
	# coverAnim = 
	# 	to :
	# 		width : rect.width
	# 		onStart : ->
	# 			$timeout ->
	# 				cfpLoadingBar.complete()
	# 				return
	# 			return
	# 		onComplete : ->
	# 			animationInner.removeClass "transitioner__wrapper--s#{size}"
	# 			animationInner.addClass "transitioner__wrapper--s12"
	# 			return
	tl
		#.to animationCover, 1, coverAnim.to
		.to {val : 0}, speed,
			val : 1
			onStart : ->
				$timeout ->
					animationInner.removeClass "transitioner__wrapper--s#{size}"
					animationInner.addClass "transitioner__wrapper--s12"
					return
				return
			onCompleteParams : ['{self}']
			#delay : .05
			onComplete : ->
				#closeBlocks $rootScope.transitionerSize
				$timeout ->
					deferred.resolve on
					return
				return
	#TweenMax.to animationCover, .5, coverAnim.to
	return deferred.promise
exports.collection = ($rootScope, $stateParams, $timeout, $q, ScrollBefore, PreviousState, screenSize)->
	body = angular.element document.body
	$rootScope.cantStart = off
	deferred = $q.defer()
	screenSize.rules = {
		min : "screen and (max-width: #{(850/16)}em)"
	}
	if screenSize.is 'min'	
		deferred.resolve on
		return deferred.promise
	prev = if $rootScope.PreviousState.Name is '' then $rootScope.fromState else $rootScope.PreviousState.Name.replace 'app.', ''
	if not document.querySelector(".header--lampade")?
		body.removeClass 'is-transitioner'	
		$rootScope.cantStart = off
		deferred.resolve on
		return deferred.promise 
	else
		body.addClass 'is-transitioner'
	$timeout ->
		$rootScope.isTransitionerActive = on
		return
	element = angular.element document.querySelector ".header--lampade"
	cover = element.attr 'data-item-background'
	item = element.attr 'data-carousel-item'
	total = element.attr 'data-item-total'
	size = element.attr 'data-item-size'
	animationInner.addClass "transitioner__wrapper--s12"
	# TweenMax.set animationCover,
	# 	backgroundImage : "url(#{cover})"
	TweenMax.set animationInner,
		backgroundImage : "url(#{cover})"
	animationDiv.addClass 'transitioner--flex'
	if item is "0"
		animationDiv.addClass 'transitioner--flex-start'
	if item is total
		animationDiv.addClass 'transitioner--flex-end'
	animationDiv.addClass 'transitioner--flex-dark'
	#rect = animationDiv[0].getBoundingClientRect()
	$rootScope.transitionerSize = size
	$rootScope.carouselIndex = item
	# TweenMax.set animationCover,
	# 	width : rect.width
	tl = new TimelineMax()
	# coverAnim = 
	# 	to :
	# 		width : "100%"
	# 		delay : .5
	# 		# onStart : ->
	# 		# 	$timeout ->
	# 		# 		deferred.resolve on
	# 		# 		return
	# 		# 	, 0
	# 		onComplete : ->
	# 			$timeout ->
	# 				deferred.resolve on
	# 				return
	# 			, 0
	# 			#animationDiv.removeClass 'transitioner--flex-dark'
	# 			return
	# tl
	# 	.to animationCover, 1, coverAnim.to
	coverAnim = 
		to :
			index : 10
			# onStart : ->
			# 	$timeout ->
			# 		deferred.resolve on
			# 		return
			# 	, 0
			onStart : ->
				$timeout ->
					animationInner.removeClass "transitioner__wrapper--s12"
					animationInner.addClass "transitioner__wrapper--s#{size}"
					#cfpLoadingBar.complete()
					return
				return
			onComplete : ->
				$timeout ->
					deferred.resolve on
					return
				, 0
				#animationDiv.removeClass 'transitioner--flex-dark'
				return
	tl
		.to {index:0}, speed, coverAnim.to
		# .to {val : 0}, .5,
		# 	val : 1
		# 	onCompleteParams : ['{self}']
		# 	delay : .05
		# 	onComplete : ->
		# 		closeBlocks $rootScope.transitionerSize
		# 		$timeout ->
		# 			deferred.resolve on
		# 			$rootScope.isAnim = off
		# 			return
		# 		return
	return deferred.promise
exports.prev = ($rootScope, $timeout, $q)->
	body = angular.element document.body
	deferred = $q.defer()
	if not $rootScope.prevElement
		deferred.resolve on 
		return deferred.promise
	tl = new TimelineMax()
	height = if body.hasClass 'admin-bar' then 32 else 0
	y = parseInt getComputedStyle($rootScope.prevElement[0])['top']
	expand = TweenMax.to $rootScope.prevElement, .75,
				top : height
	scroll = TweenMax.to window, .75,
				scrollTo :
					y : "#next-divider"
					autoKill : off
	tl
		.set 'body',
	 		className : '-=white'
		.add [expand, scroll], "+=.5"
		.to $rootScope.prevElement, .75,
				bottom : 0
				onComplete : ->
					$rootScope.prevElement.addClass 'next--fixed'
					body.removeClass 'is-to-next'
					$timeout ->
						window.scrollTo 0, 0
						deferred.resolve on
						return
					, 0
					return
		, '-=.25'

	#height = if body.hasClass 'admin-bar' then 'calc(100vh - 32px)' else '100vh'
	# controller.scrollTo (newPos)->
	# 	tl
	# 		.set 'body',
	# 			className : '-=white'
	# 		.to window, .5,
	# 			scrollTo :
	# 				y : newPos
	# 		.to $rootScope.prevElement, .5,
	# 			height : '100vh'
	# 			onComplete : ->
	# 				$rootScope.prevElement.addClass 'next--fixed'
	# 				$timeout ->
	# 					$rootScope.isLeaving = off
	# 					window.scrollTo 0, 0
	# 					deferred.resolve on
	# 					return
	# 				, 0
	# 	return

	# console.log $rootScope.prevElement[0].getBoundingClientRect(), document.body.clientHeight, window.innerHeight
	# controller.scrollTo $rootScope.prevElement[0]
	return deferred.promise