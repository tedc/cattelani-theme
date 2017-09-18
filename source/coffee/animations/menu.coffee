module.exports = ($timeout)->
	TL = new TimelineMax
			paused : on
			ease: Linear.easeNone
	endStagger = ->
		#console.log TL.reversed()
		TweenMax.to ['.banner__nav', '.main'], .5, { clearProps : 'all' } 
		return
	LeftStagger = TweenMax.staggerTo ['.banner__footer', '.banner__quote'], .5, { y : 0, opacity : 1}, .05
	RightStagger = TweenMax.staggerTo '.menu__item', .5, { y : 0, opacity : 1}, .05
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
	menu =
		addClass : (element, className, done)->
			return if className isnt 'menu-opened'
			TweenMax.set '.banner__nav',
				visibility : 'visible'
			TL
				.play()
			TL.
				eventCallback 'onComplete', ->
					$timeout ->
						done()
						return
					return
			return
		removeClass : (element, className, done)->
			return if className isnt 'menu-opened'
			TL
				.timeScale 1.8
				.reverse()
			TL.
				eventCallback 'onReverseComplete',  ->
					$timeout ->
						done()
						return
					return
			return