module.exports = ($rootScope, $timeout, $state)->
	transitioner = (element, cond)->
		cover = element.getAttribute 'data-item-background'
		item = element.getAttribute 'data-carousel-item'
		total = element.getAttribute 'data-item-total'
		size = element.getAttribute 'data-item-size'
		animationDiv = angular.element document.querySelector '.transitioner'
		animationInner = angular.element document.querySelector '.transitioner__wrapper'
		animationCover = angular.element document.querySelector '.transitioner__cover'
		animationDiv.addClass 'transitioner--flex'
		if parseInt item is 0
			animationDiv.addClass 'transitioner--flex-start'
		else if parseInt item is parseInt total
			animationDiv.addClass 'transitioner--flex-end'
		rect = animationDiv.getBoundingClientRect()
		if cond then animationInner.addClass "transitioner__wrapper--s#{size}" else animationInner.addClass "transitioner__wrapper--s12"
		TweenMax.set animationCover,
			backgroundImage : "url(#{cover})"
		if cond
			coverAnim = 
				from :
					width : "100%"
					height : "100%"
				to :
					width : rect.width
					height : rect.height
					onUpdate : ->
						if @time > .35
							animationInner.removeClass "transitioner__wrapper--s#{size}"
							animationInner.addClass "transitioner__wrapper--s12"
						return
					onComplete : ->
						$timeout ->
							animationDiv.removeClass 'transitioner--flex'
							return
						, 150
						return
		else
			coverAnim = 
				from :
					width : rect.width
					height : rect.height
				to :
					width : "100%"
					height : "100%"
					delay : .35
					onComplete : ->
						animationDiv.removeClass 'transitioner--flex'
						TweenMax.set animationCover,
							clearProps : 'all'
						return
		TweenMax.fromTo animationCover, .5, coverAnim.from, coverAnim.to
		if not cond
			animationInner.removeClass "transitioner__wrapper--s12"
			animationInner.addClass "transitioner__wrapper--s#{size}"
		return
	views =
		enter : (element, done)->
			prev = $rootScope.PreviousState
			current = $state.current.name
			fromY = if $state.current.name isnt 'app.root' then 0 else -100
			toY = if $state.current.name isnt 'app.root' then -100 else 0
			element.addClass 'view-enter'
			element.addClass 'view-enter-home' if $state.current.name is 'app.root'
			TweenMax.fromTo element, .5,
				{
					yPercent : fromY
				}
				{
					yPercent : toY
					onComplete : ->
						$timeout ->
							element.removeClass 'view-enter'
							TweenMax.set element,
								clearProps : 'all'
							done()
							return
						return
				}
			return
		leave : (element, done)->
			header = element[0].querySelector('.header')
			collection = element[0].querySelector('.collections__slider--archive')
			prev = $rootScope.PreviousState
			current = $state.current.name
			transitioner(header, off) if header? and typeof header isnt 'undefined' and current is 'app.collection'
			transitioner($rootScope.element, off) if collection? and typeof collection isnt 'undefined' and current is 'app.page'
			fromY = if $state.current.name isnt 'app.root' then 0 else -100
			toY = if $state.current.name isnt 'app.root' then -100 else 0
			element.addClass 'view-leave'
			
			TweenMax.fromTo element, .5,
				{
					yPercent : fromY
				}
				{
					yPercent : toY
					onComplete : ->
						$timeout ->
							element.removeClass 'view-leave'
							TweenMax.set element,
								clearProps : 'all'
							done()
							return
						return
				}
			return