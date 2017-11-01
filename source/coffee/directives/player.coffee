launchIntoFullscreen = (element)->
	if element.requestFullscreen
		element.requestFullscreen() 
	else if element.mozRequestFullScreen 
		element.mozRequestFullScreen()
	else if element.webkitRequestFullscreen
		element.webkitRequestFullscreen()
	else if element.msRequestFullscreen
		element.msRequestFullscreen()
	return

module.exports = (angularLoad, $timeout, $rootScope)->
	player = 
		scope : on
		link : (scope, element, attrs)->
			scope.progress = "00:00:00"
			scope.duration = scope.progress
			scope.volume = 0
			id = attrs.id
			mask = element[0].querySelector('.main-video__mask')
			buffer = element[0].querySelector('.main-video__buffer')
			scope.isPaused = on 
			scope.isSkipped = off
			scope.isOpen = off
			$rootScope.isVideo = off
			$rootScope.open = (video_id)->
				if vars.main.mobile
					window.open scope.vimeoUrl, '_blank'
				else
					$rootScope.isVideo = video_id
					scope.isOpen = on
					$timeout ->
						scope.player.play()
					, 500
				return
			onProgress = (data)->
				return if scope.isSkipped
				TweenMax.to mask, .5,
					width: "#{data.percent * 100}%"
				$timeout ->
					scope.progress = secondsToTime data.seconds
					return
				, 0
				return
			onBuffer = (data)->
				TweenMax.to buffer, .5,
					width: "#{data.percent * 100}%"
				return
			timeToPercentage = (player)->
				total = player.getDuration()
				current = player.getCurrentTime()
				value = Math.round ( current / total ) * 100
				return value
			secondsToTime = (time)->
				sec_num = parseInt(time, 10)
				hours   = Math.floor(sec_num / 3600)
				minutes = Math.floor((sec_num - (hours * 3600)) / 60)
				seconds = sec_num - (hours * 3600) - (minutes * 60)
				hours   = "0#{hours}" if hours < 10
				minutes = "0#{minutes}" if minutes < 10
				seconds = "0#{seconds}" if seconds < 10
				return "#{hours}:#{minutes}:#{seconds}"
			iframe = element.find('iframe')[0]
			angularLoad.loadScript 'https://player.vimeo.com/api/player.js'
				.then ->
					scope.player = new Vimeo.Player iframe
					scope.player
						.getDuration()
						.then (duration)->
							scope.duration = duration
							scope.time = secondsToTime duration
							return
					scope.player.on 'timeupdate', onProgress
					scope.player.on 'timeupdate', onBuffer
					scope.player.on 'play', ->
						scope.player.getVolume().then (v)->
							$timeout ->
								scope.volume = v 
								return
							return
						return
					scope.player.on 'volumechange', (v)->
						$timeout ->
							scope.volume = v.volume
							return
						return
					scope.player.ready().then ->
						$timeout ->
							$rootScope.isReady = id
							return
						return
					scope.player.getVideoUrl().then (url)->
						$timeout ->
							scope.vimeoUrl = url
							return
						return
					return
			scope.close = ->
				scope.isOpen = off
				scope.player.pause()
				scope.player.setCurrentTime(0)
				$rootScope.isVideo = off
				return
			scope.play = (cond)->
				if cond then scope.player.play() else scope.player.pause()
				return
			scope.skipTo = (event)->
				scope.isSkipped = on
				w = event.target.offsetWidth
				x = event.offsetX
				point = x / w
				pos = Math.round ( scope.duration * point ) 
				scope.player.pause()
				scope.player.setCurrentTime pos
				TweenMax.to mask, .5,
					width : "#{point*100}%"
				scope.player.play() if scope.isPaused
				scope.isSkipped = off
				return
			return