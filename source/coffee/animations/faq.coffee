module.exports = ->
	addClass : (element, className, done)->
		return if className isnt 'accordion--active'
		content = element[0].querySelector '.accordion-content'
		height = element[0].offsetHeight
		TweenMax.set element,
			height : height
		TweenMax.set content,
			display : 'block'
		height += content.offsetHeight
		TweenMax.to element, .5,
			height : height
			onComplete : ->
				TweenMax.set element,
					clearProps : 'all'
				done()
				return
		return
	removeClass : (element, className, done)->
		return if className isnt 'accordion--active'
		content = element[0].querySelector '.accordion-content'
		height = content.offsetHeight
		TweenMax.to element, .5,
			height : "-=#{height}"
			onComplete : ->
				TweenMax.set [element, content],
					clearProps : 'all'
				done()
				return
		return