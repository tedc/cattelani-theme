module.exports = (ScrollbarService)->
	scope.$on 'swiperChaged', ->
		scope.main.update()
		return