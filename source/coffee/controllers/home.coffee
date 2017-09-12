module.exports = ($rootScope, data, $stateParams, $scope)->
	return
	home = @
	home.content = data[0]
	home.items = data[1]
	home.main = new Swiper '.collections__slider',		
		nextButton : '.collections__nav .swiper-next'
		prevButton : '.collections__nav .swiper-prev'
		#loop : on
	home.nav = new Swiper '.collections__nav .swiper-container',
		slideToClickedSlide : on
		slidesPerView : 5
		simulateTouch : off
		centeredSlides : on
		loop : on
	home.main.params.control = home.nav
	home.nav.params.control = home.main
	$scope.$on 'finishRepeat', ->
		home.main.update()
		return
	$scope.$on 'navFinishRepeat', ->
		home.nav.update()
		return
	return