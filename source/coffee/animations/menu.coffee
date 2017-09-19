module.exports = ($timeout, $rootScope)->
	$rootScope.isRunning = off
	TL = new TimelineMax
			paused : on
			ease: Linear.easeNone
	endStagger = ->
		#console.log TL.reversed()
		TweenMax.to ['.banner__nav', '.main'], .5, { clearProps : 'all' }
		$rootScope.isRunning = off
		return
	LeftStagger = TweenMax.staggerTo ['.banner__footer', '.banner__quote'], .5, { y : 0, opacity : 1}, .05
	RightStagger = TweenMax.staggerTo '.menu__item', .5, 
		y : 0
		opacity : 1
	, .05
	TL
		.to '.main', .5,
			opacity : 0.8
			onReverseComplete : endStagger	
		.to ['.banner__aside', '.banner__menu'], .5,
			x : '0%'
		, "-=.15"
		.to '.banner__btn--search', .5,
			autoAlpha : off
		, "-=.5"
		.add [LeftStagger, RightStagger], "+=.5"
	$rootScope.isRunning = off
	menu =
		addClass : (element, className, done)->
			return if $rootScope.isRunning
			$rootScope.isRunning = on
			return if className isnt 'menu-opened'
			TweenMax.set '.banner__nav',
				visibility : 'visible'
			TL
				.timeScale 1
				.play()
			TL.
				eventCallback 'onComplete', ->
					$timeout ->
						$rootScope.isRunning = off
						done()
						return
					return
			return
		removeClass : (element, className, done)->
			return if $rootScope.isRunning
			$rootScope.isRunning = on
			return if className isnt 'menu-opened'
			TL
				.timeScale 1.8
				.pause on
				.reverse()
			TL.
				eventCallback 'onReverseComplete',  ->
					$timeout ->
						$rootScope.isRunning = off
						done()
						return
					return
			return