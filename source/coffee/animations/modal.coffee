module.exports = ($rootScope, $timeout)->
	mTL = new TimelineMax
		paused : on
		ease: Linear.easeNone
	end = ->
		TweenMax.to '.modal', .5, { clearProps : 'visibility' } 
		return
	start = TweenMax.to '.modal', .5, 
		autoAlpha : on
	mTL
		.addLabel 'start'
		.add start, 'start' 
		.addLabel 'elements'
		.addLabel 'contacts'
	modal =
		addClass : (element, className, done)->
			return if className isnt 'modal--visible'
			id = "#modal-#{$rootScope.modalId}"
			first = TweenMax.to id, .5, { autoAlpha : on }
			elements = TweenMax.to "#{id} > *", .5,  
				y : 0
				opacity : 1
			contact = TweenMax.to ".contact__cell", .5,
				x : 0
				opacity : 1
			
			TweenMax.to id, .5, 
				autoAlpha : on
			mTL.add elements, 'elements'
			mTL.add contact, 'contacts' if $rootScope.modalId is 'contact'
			mTL
				.play()
			mTL.
				eventCallback 'onComplete', ->
					$timeout ->
						done()
						$rootScope.$broadcast 'scrollBarUpdate'
						return
					return
			return
		removeClass : (element, className, done)->
			return if className isnt 'modal--visible'
			$timeout ->
				$rootScope.isMenu = true if $rootScope.oldMenu
			, 0
			mTL
				.timeScale 1.8
				.reverse()
			mTL.
				eventCallback 'onReverseComplete', ->
					$timeout ->
						done()
						$rootScope.modalId = off
						TweenMax.set '.modal__container',
							clearProps : 'all'
						return
					return
			return