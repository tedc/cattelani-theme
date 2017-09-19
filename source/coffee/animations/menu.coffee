module.exports = ($timeout, $rootScope)->
	TL = new TimelineMax
			paused : on
			ease: Linear.easeNone
	endStagger = ->
		#console.log TL.reversed()
		console.log on
		TweenMax.to ['.banner__nav', '.main'], .5, { clearProps : 'all' }
		return
	Close = TweenMax.to '.banner__btn--close', .5, 
		visibility : 'visible'
		opacity : 1
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
		.to '.banner__tools', .5,
			visibility : 'hidden'
			opacity: 0
		, "-=.5"
		.add [LeftStagger, RightStagger, Close], "+=.5"
	$rootScope.isRunning = off
	menu =
		addClass : (element, className, done)->
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
			return if className isnt 'menu-opened'
			TL
				.timeScale 1.25
				.pause on
				.reverse()
			TL.
				eventCallback 'onReverseComplete',  ->
					$timeout ->
						done()
						return
					return
			return