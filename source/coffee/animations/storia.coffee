module.exports = ($rootScope)->
	storia =
		addClass : (element, className, done)->
			return if className isnt 'storia--years-visible'
			$rootScope.$broadcast 'swiperChaged'
			return
		removeClass : (element, className, done)->
			return if className isnt 'storia--years-visible'
			$rootScope.$broadcast 'swiperChaged'
			return