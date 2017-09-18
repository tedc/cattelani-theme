animationDiv = angular.element document.querySelector '.transitioner'
animationInner = angular.element document.querySelector '.transitioner__wrapper'
animationCover = angular.element document.querySelector '.transitioner__cover'
	
closeBlocks = (size)->
	animationDiv.removeClass 'transitioner--flex'
	animationDiv.removeClass 'transitioner--flex-start'
	animationDiv.removeClass 'transitioner--flex-end'
	animationInner.removeClass "transitioner__wrapper--s12"
	TweenMax.set animationCover,
		clearProps : 'width'
	return
	
exports.single = ($rootScope, $stateParams, $timeout, $q, ScrollBefore, PreviousState)->
	deferred = $q.defer()
	prev = if $rootScope.PreviousState.Name is '' then $rootScope.fromState else $rootScope.PreviousState.Name.replace 'app.', ''
	if $rootScope.PreviousState.Name isnt 'collection' and not document.querySelector("[data-item-slug='#{$stateParams.slug}']")?
		deferred.resolve on
		return deferred.promise 
	$timeout ->
		$rootScope.isTransitionerActive = on
		return
	element = angular.element document.querySelector "[data-item-slug='#{$stateParams.slug}']"
	cover = element.attr 'data-item-background'
	item = element.attr 'data-carousel-item'
	total = element.attr 'data-item-total'
	size = element.attr 'data-item-size'
	animationInner.addClass "transitioner__wrapper--s#{size}"
	TweenMax.set animationCover,
		backgroundImage : "url(#{cover})"
	animationDiv.addClass 'transitioner--flex'
	if item is "0"
		animationDiv.addClass 'transitioner--flex-start'
	if item is total
		animationDiv.addClass 'transitioner--flex-end'
	rect = animationDiv[0].getBoundingClientRect()
	$rootScope.transitionerSize = 12
	$rootScope.carouselIndex = item
	tl = new TimelineMax()
	coverAnim = 
		to :
			width : rect.width
			onComplete : ->
				animationInner.removeClass "transitioner__wrapper--s#{size}"
				animationInner.addClass "transitioner__wrapper--s12"
				return
	tl
		.to animationCover, .5, coverAnim.to
		.to {val : 0}, .5,
			val : 1
			onCompleteParams : ['{self}']
			delay : .05
			onComplete : ->
				#closeBlocks $rootScope.transitionerSize
				$timeout ->
					deferred.resolve on
					$rootScope.isAnim = off
					return
				return
	TweenMax.to animationCover, .5, coverAnim.to
	return deferred.promise
exports.collection = ($rootScope, $stateParams, $timeout, $q, ScrollBefore, PreviousState)->
	deferred = $q.defer()
	prev = if $rootScope.PreviousState.Name is '' then $rootScope.fromState else $rootScope.PreviousState.Name.replace 'app.', ''
	if $rootScope.PreviousState.Name isnt 'page' and not document.querySelector(".header--lampade")?
		deferred.resolve on
		return deferred.promise 
	$timeout ->
		$rootScope.isTransitionerActive = on
		return
	element = angular.element document.querySelector ".header--lampade"
	cover = element.attr 'data-item-background'
	item = element.attr 'data-carousel-item'
	total = element.attr 'data-item-total'
	size = element.attr 'data-item-size'
	animationInner.addClass "transitioner__wrapper--s12"
	TweenMax.set animationCover,
		backgroundImage : "url(#{cover})"
	animationDiv.addClass 'transitioner--flex'
	if item is "0"
		animationDiv.addClass 'transitioner--flex-start'
	if item is total
		animationDiv.addClass 'transitioner--flex-end'
	animationDiv.addClass 'transitioner--flex-dark'
	ratio = 12 / size
	perc = 100 * ratio
	rect = animationDiv[0].getBoundingClientRect()
	$rootScope.transitionerSize = size
	$rootScope.carouselIndex = item
	TweenMax.set animationCover,
		width : rect.width
	tl = new TimelineMax()
	coverAnim = 
		to :
			width : "100%"
			delay : .5
			# onStart : ->
			# 	$timeout ->
			# 		deferred.resolve on
			# 		return
			# 	, 0
			onComplete : ->
				$timeout ->
					deferred.resolve on
					return
				, 0
				#animationDiv.removeClass 'transitioner--flex-dark'
				return
	tl
		.to animationCover, .5, coverAnim.to
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
	animationInner.removeClass "transitioner__wrapper--s12"
	animationInner.addClass "transitioner__wrapper--s#{size}"
	return deferred.promise
exports.prev = ($rootScope, $timeout, $q)->
	body = angular.element document.body
	deferred = $q.defer()
	if not $rootScope.prevElement
		deferred.resolve on 
		return deferred.promise
	tl = new TimelineMax()
	height = if body.hasClass 'admin-bar' then 'calc(100vh - 32px)' else '100vh'
	controller.scrollTo (newPos)->
		tl.to window, .5,
			scrollTo :
				y : newPos
		return
	controller.scrollTo $rootScope.prevElement[0]
	tl
		.to $rootScope.prevElement, .5,
			height : height
			onComplete : ->
				$rootScope.prevElement.addClass 'next--fixed'
				$timeout ->
					window.scrollTo 0, 0
					deferred.resolve on
					return
				, 0
		return deferred.promise