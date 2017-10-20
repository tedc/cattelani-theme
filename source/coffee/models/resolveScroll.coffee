module.exports = ($q, $timeout, $rootScope)->
	return if $rootScope.prevElement
	# cfpLoadingBar.start()
	deferred = $q.defer()
	if $rootScope.scrollFrom > 0
		TweenMax.set 'body',
			className : '-=white'
		TweenMax.to window, .75,
			scrollTo :
				y : 0
				autoKill : off
			onComplete : ->
				$timeout ->
					window.scrollTo 0, 0
					deferred.resolve on
					return
				return
	else
		deferred.resolve on
	$rootScope.cantStart = off
	deferred.promise