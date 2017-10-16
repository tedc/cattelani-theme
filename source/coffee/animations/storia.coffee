module.exports = ($rootScope)->
	storia =
		addClass : (element, className, done)->
			return if className isnt 'storia--visible'
			$rootScope.$broadcast 'swiperChaged'
			console.log on
			return
		removeClass : (element, className, done)->
			return if className isnt 'storia--visible'
			$rootScope.$broadcast 'swiperChaged'
			console.log on
			return