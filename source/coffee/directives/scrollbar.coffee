module.exports = ($rootScope)->
	scroll =
		scope : on
		link : (scope, element)->
			return if vars.main.mobile
			scrollbar = null
			#jqWindow = angular.element $window
			opts =
				continuousScrolling : true
			scope.$evalAsync ->
				scrollbar = Scrollbar.init element[0], opts if not Scrollbar.has element[0]
				return
			element.bind '$destroy', ()->
				Scrollbar.destroy element[0] if Scrollbar.has element[0]
				return
			$rootScope.$on 'scrollBarUpdate', (evt, data)->
				if data
					if data.zoom 
						if data.element is element[0]
							child = data.size
							size =
								height :
									el : element[0].offsetHeight
									child : child.height
								width :
									el : element[0].offsetWidth
									child : child.width
							x = if size.width.child > size.width.el then (size.width.child - size.width.el) / 2 else 0
							y = if size.height.child > size.height.el then (size.height.child - size.height.el) / 2 else 0
							scrollbar.setPosition x, y
					else
						scrollbar.setPosition 0, 0
				scrollbar.update() if scrollbar isnt null
				return
			return