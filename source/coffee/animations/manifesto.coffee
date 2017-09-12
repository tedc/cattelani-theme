module.exports = ->
	manifesto =
		addClass : (element, className, done)->
			return if className isnt 'manifesto__item--active'
			desc = element[0].querySelector '.manifesto__desc'
			content = element[0].querySelector '.manifesto__content'
			line = element[0].querySelector '.manifesto__line'
			name = element[0].querySelector '.manifesto__name'
			descHeight = desc.offsetHeight / 1379 * 100
			height = "#{( descHeight )}vw"
			offset = 14 / 1379 * 100
			lineHeight = (line.offsetHeight / 1379 * 100) + descHeight
			attr = parseInt element[0].getAttribute 'data-manifesto-item'
			item = if attr == 3 then name else content
			TweenMax.to desc, .5,
				autoAlpha : on
			TweenMax.to item, .5,
				marginBottom : height
			if attr isnt 3
				TweenMax.to line, .5,
					height : "#{lineHeight - offset}vw"
			done()
			return
		removeClass : (element, className, done)->
			return if className isnt 'manifesto__item--active'
			desc = element[0].querySelector '.manifesto__desc'
			TweenMax.set element[0].querySelectorAll("*"),
				clearProps : 'all'
			done()
			return