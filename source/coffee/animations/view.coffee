module.exports = ($rootScope, $timeout, $state)->
	$rootScope.isTransitionerActive = off
	transitioner = (element, cond, done)->
		$rootScope.isTransitionerActive = on
		cover = element.getAttribute 'data-item-background'
		item = element.getAttribute 'data-carousel-item'
		total = element.getAttribute 'data-item-total'
		size = element.getAttribute 'data-item-size'
		animationDiv = angular.element document.querySelector '.transitioner'
		animationInner = angular.element document.querySelector '.transitioner__wrapper'
		animationCover = angular.element document.querySelector '.transitioner__cover'
		if not cond then animationInner.addClass "transitioner__wrapper--s#{size}" else animationInner.addClass "transitioner__wrapper--s12"
		TweenMax.set animationCover,
			backgroundImage : "url(#{cover})"
		animationDiv.addClass 'transitioner--flex'
		if item is "0"
			animationDiv.addClass 'transitioner--flex-start'
		if item is total
			animationDiv.addClass 'transitioner--flex-end'
		animationDiv.addClass 'transitioner--flex-dark' if cond
		rect = animationDiv[0].getBoundingClientRect()
		if not cond
			coverAnim = 
				to :
					width : rect.width
					onComplete : ->
						animationInner.removeClass "transitioner__wrapper--s#{size}"
						animationInner.addClass "transitioner__wrapper--s12"
						$timeout ->
							$rootScope.isTransitionerActive = off
							TweenMax.set animationCover,
								clearProps : 'width'
							$rootScope.$broadcast 'is_finish'
							done()
							animationDiv.removeClass 'transitioner--flex'
							animationDiv.removeClass 'transitioner--flex-start'
							animationDiv.removeClass 'transitioner--flex-end'
							animationInner.removeClass "transitioner__wrapper--s12"
							return
						, 500
						return
		else
			coverAnim = 
				from :
					width : rect.width
				to :
					width : "100%"
					height : "100%"
					delay : .5
					onComplete : ->
						$rootScope.$broadcast 'collection_change', { index : parseInt( item ) }
						$rootScope.$broadcast 'is_finish'
						done()
						animationDiv.removeClass 'transitioner--flex-dark'
						$rootScope.isTransitionerActive = off
						TweenMax.set animationCover,
							clearProps : 'width'
						$timeout ->
							animationDiv.removeClass 'transitioner--flex'
							animationDiv.removeClass 'transitioner--flex-start'
							animationDiv.removeClass 'transitioner--flex-end'
							animationInner.removeClass "transitioner__wrapper--s#{size}"
						, 500
						return
		if not cond
			TweenMax.to animationCover, .5, coverAnim.to
		else
			TweenMax.fromTo animationCover, .5, coverAnim.from, coverAnim.to
			animationInner.removeClass "transitioner__wrapper--s12"
			animationInner.addClass "transitioner__wrapper--s#{size}"
		return
	views =
		enter : (element, done)->
			prev = if $rootScope.PreviousState.Name is '' then $rootScope.fromState else $rootScope.PreviousState.Name.replace 'app.', ''
			current = $state.current.name.replace 'app.', ''
			if current is 'root' 
				fromY = -100
				toY = 0
			else if current is 'collection'
				if prev is 'root'
					fromY = 100
					toY = 0
				else
					fromY = -100
					toY = 0
			else
				fromY = 100
				toY = 0
			header = element[0].querySelector('.header--lampade')
			collection = element[0].querySelector('.collections__slider--archive')
			$timeout ->
				if not $rootScope.isTransitionerActive
					element.addClass 'view-enter'
					TweenMax.fromTo element, .49,
						{
							yPercent : fromY
						}
						{
							yPercent : toY
							onComplete : ->
								$timeout ->
									done()
									element.removeClass 'view-enter'
									TweenMax.set element,
										clearProps : 'all'
									return
								return
						}
				else
					$rootScope.$on 'is_finish', done
				return
			, 10
			return
		leave : (element, done)->
			header = element[0].querySelector('.header--lampade')
			collection = element[0].querySelector('.collections__slider--archive')
			prev = if $rootScope.PreviousState.Name is '' then $rootScope.fromState else $rootScope.PreviousState.Name.replace 'app.', ''
			current = $state.current.name.replace 'app.', ''
			collectionCondition = collection? and typeof collection isnt 'undefined' and current is 'page' and $rootScope.fromElement
			lightCondition = header? and typeof header isnt 'undefined' and current is 'collection'
			transitioner(header, on, done) if lightCondition
			transitioner($rootScope.fromElement[0], off, done) if collectionCondition
			fromY = if $state.current.name isnt 'app.root' then 100 else -100
			toY = if $state.current.name isnt 'app.root' then 0 else 0
			if current is 'root' 
				fromY = 0
				toY = 100
			else if current is 'collection'
				if prev is 'root'
					fromY = 0
					toY = -100
				else
					fromY = 0
					toY = 100
			else
				fromY = 0
				toY = -100
			if not $rootScope.isTransitionerActive
				TweenMax.fromTo element, .5,
					{
						yPercent : fromY
					}
					{
						yPercent : toY
						onComplete : ->
							$timeout ->
								done()
								element.removeClass 'view-leave'
								TweenMax.set element,
									clearProps : 'all'
								return
							return
					}
			return