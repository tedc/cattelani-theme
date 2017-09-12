module.exports = ($rootScope)->
	restrict : 'A'
	scope :
		click : '&clickedElement'
	link : (scope, element, attr)->
		scope.saveElement = ->
			$rootScope.fromElement = element
			return
		return