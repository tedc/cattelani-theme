module.exports = ($q, $timeout, $rootScope, cfpLoadingBar)->
	return if $rootScope.prevElement
	cfpLoadingBar.start()
	deferred = $q.defer()
	if $rootScope.scrollFrom > 0
		TweenMax.set 'body',
			className : '-=white'
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
	$rootScope.cantStart = off
	deferred.promise