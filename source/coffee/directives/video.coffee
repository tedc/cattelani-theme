module.exports = ($rootScope)->
    video =
        link : (scope, element)->
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