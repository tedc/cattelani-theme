module.exports = ($rootScope)->
    video =
        link : (scope, element)->
            if vars.main.mobile
                scope.isLoading = off
                scope.isPaused = on
                canvas = null
                paintVideo = ->
                    if canvas is null
                        canvas = document.createElement "canvas"
                        canvas.width = element[0].videoWidth
                        canvas.height = element[0].videoHeight
                        element.after canvas
                    canvas.getContext('2d').drawImage element[0], 0, 0, canvas.width, canvas.height
                    scope.isLoading = off if scope.isLoading
                    requestAnimationFrame paintVideo if not element[0].paused
                    return
                element.on 'playing', paintVideo
            scope.play = ->
                scope.isLoading = on
                if element[0].paused then element[0].play() else element[0].pause()             
                scope.isPaused = element[0].paused
                return
            return if vars.main.mobile
            tween = TweenMax.to { index : 0}, 5,
                        index : 10
                        onUpdateParams : ['{self}']
                        onUpdate : (evt)->
                            if evt.target.index > 0 and evt.target.index <= 9.6
                                element[0].play()
                            else
                                element[0].pause()
                            return
            enterVideoScene = new ScrollMagic.Scene
                    triggerElement : element[0]
                    duration : "100%"
                .setTween tween
                .addTo controller
            element.on '$destroy', ->
                enterVideoScene.destroy()
                return
            return