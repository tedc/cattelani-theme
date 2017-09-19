module.exports = ($timeout, $rootScope)->
	TL = new TimelineMax
			paused : on
			ease: Linear.easeNone
	Close = TweenMax.to '.banner__btn--close', .5, 
		opacity : 1
	LeftStagger = TweenMax.staggerTo ['.banner__footer', '.banner__quote'], .5, { y : 0, opacity : 1}, .05
	RightStagger = TweenMax.staggerTo '.menu__item', .5, 
		y : 0
		opacity : 1
	, .05

	TL
		.set ['.main', '.banner__nav'],
			clearProps : 'all'
		.set '.banner__nav',
			visibility : 'visible'
		.to '.main', .5,
			opacity : 0.8
		.to ['.banner__aside', '.banner__menu'], .5,
			x : '0%'
		, "-=.15"
		.add [LeftStagger, RightStagger, Close], "+=.5"
	menu =
		addClass : (element, className, done)->
			return if className isnt 'menu-opened'
			TL
				.timeScale 1
				.play()
			TL.
				eventCallback 'onComplete', ->
					$timeout ->
						done()
						$rootScope.isRunning = off
						return
					return
			return
		removeClass : (element, className, done)->
			return if className isnt 'menu-opened'
			TL
				.timeScale 1.25
				.reverse()
			TL.
				eventCallback 'onReverseComplete',  ->
					$timeout ->
						done()
						$rootScope.isRunning = off
						return
					return
			return