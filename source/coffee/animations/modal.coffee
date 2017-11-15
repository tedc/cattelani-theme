module.exports = ($rootScope, $timeout)->
	speed = if vars.main.mobile then .25 else .5
	mTL = new TimelineMax
		paused : on
		ease: Linear.easeNone
	end = ->
		TweenMax.to '.modal', speed, { clearProps : 'visibility' } 
		return
	start = TweenMax.to '.modal', speed, 
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
			first = TweenMax.to id, speed, { autoAlpha : on }
			elements = TweenMax.to "#{id} > *", speed,  
				y : 0
				opacity : 1
			contact = TweenMax.to ".contact__cell", speed,
				x : 0
				opacity : 1
			
			TweenMax.to id, speed, 
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