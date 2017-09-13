module.exports = ($q, $timeout, $rootScope)->
	deferred = $q.defer()
	if $rootScope.scrollFrom > 0
		TweenMax.to window, .3,
			scrollTo :
				y : 0
			onComplete : ->
				$timeout ->
					window.scrollTo 0, 0
					deferred.resolve on
					return
				return
	else
		deferred.resolve on
	deferred.promise