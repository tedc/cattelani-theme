module.exports = ->
	s = 
		templateUrl : "#{vars.main.assets}/tpl/store.tpl.html"
		bindToController : on
		controllerAs : "store"
		controller : ['NgMap', "$timeout", "$rootScope", "$element", "wpApi", "GeoCoder", "NavigatorGeolocation", "$http", "$attrs", (NgMap, $timeout, $rootScope, $element, wpApi, GeoCoder, NavigatorGeolocation, $http, $attrs)->
			# wp = WPAPI
			# wp.locations = wp.registerRoute 'wp/v2', 'locations/',
			# 	params : ['order_location', 'stores']
			# wp.stores = wp.registerRoute 'wp/v2', 'stores/'	
			store = @
			store.lang = 
				current : $attrs.currentLang
				default : $attrs.defaultLang
			store.isSelected = false
			store.buttonString = vars.strings.btn_stores
			store.any = vars.strings.select_any
			store.isStore = off
			store.empty = vars.strings.empty_store
			#$rootScope.address = 'Via Giuseppe Tomaino, Lamezia Terme, CZ, Italia'
			store.isStoreLoading = off
			store.$onInit = ->
				store.map = {}
				store.address = ''
				store.api = "https://maps.googleapis.com/maps/api/js?key=#{vars.api.google_api_key}&libraries=visualization,drawing,geometry,places"
				store.styles = [{'featureType': 'all','elementType': 'labels.text.fill','stylers': [{'color': '#ffffff'}]},{'featureType': 'all','elementType': 'labels.text.stroke','stylers': [{'visibility': 'on'},{'color': '#3e606f'},{'weight': 2},{'gamma': 0.84}]},{'featureType': 'all','elementType': 'labels.icon','stylers': [{'visibility': 'off'}]},{'featureType': 'administrative','elementType': 'geometry','stylers': [{'weight': 0.6},{'color': '#1a3541'}]},{'featureType': 'administrative.locality','elementType': 'all','stylers': [{'visibility': 'simplified'}]},{'featureType': 'administrative.neighborhood','elementType': 'all','stylers': [{'visibility': 'off'}]},{'featureType': 'administrative.land_parcel','elementType': 'all','stylers': [{'visibility': 'off'}]},{'featureType': 'landscape','elementType': 'geometry','stylers': [{'color': '#2c5a71'}]},{'featureType': 'landscape.natural','elementType': 'geometry.fill','stylers': [{'color': '#0b1e2d'}]},{'featureType': 'landscape.natural.landcover','elementType': 'geometry.fill','stylers': [{'color': '#0b1e2d'}]},{'featureType': 'landscape.natural.terrain','elementType': 'geometry.fill','stylers': [{'color': '#0b1e2d'},{'lightness': '-38'}]},{'featureType': 'poi','elementType': 'all','stylers': [{'visibility': 'off'}]},{'featureType': 'poi','elementType': 'geometry','stylers': [{'color': '#406d80'}]},{'featureType': 'poi.park','elementType': 'geometry','stylers': [{'color': '#2c5a71'}]},{'featureType': 'road','elementType': 'geometry','stylers': [{'color': '#29768a'},{'lightness': -37}]},{'featureType': 'transit','elementType': 'geometry','stylers': [{'color': '#406d80'}]},{'featureType': 'water','elementType': 'geometry','stylers': [{'color': '#193341'},{'visibility': 'simplified'}]}]
				store.start = vars.api.start_latlng
				store.address = store.start
				$timeout ->
					getMap()
					return
				return
			# store.placeChanged = ->
			# 	store.place = @getPlace()
			# 	center = store.place.geometry.location
			# 	store.coords = "#{center.lat()}, #{center.lng()}"
			# 	return
			# store.onInputChange = ->
			# 	delete store.coords if store.address is ''
			# 	google.maps.event.trigger store.autoComplete, 'place_changed'
			# 	return
			store.storeCallback = ->
				LatLng = new google.maps.LatLng vars.api.start_latlng
				store.map.setCenter LatLng
				return

			store.content = (content)->
				content = content.replace('T. ', '<br/>T. ')
				content = content.replace('M. ', '<br/>M. ')
				content = content.replace('F. ', '<br/>F. ')
				content = content.replace('T ', '<br/>T. ')
				content = content.replace('M ', '<br/>M. ')
				content = content.replace('F ', '<br/>M. ')
				return content

			$rootScope.$on 'markers_changed', ->
				store.isStoreLoading = off
				zoomChange()
				return
			$rootScope.$on 'location_changed', (event, data)->
				store.coords = data.join()
				params = { order_location : store.coords, per_page : vars.api.store_limit } 
				params = angular.extend {}, params, {stores : store.store} if store.store
				# $http.get("https://www.catellanismith.com/wp-json/wp/v2/locations", {order_location : store.coords})
				# 	.then (res)->
				# 		console.log res
				# 		return
				wpApi
					endpoint : 'locations'
					params : params
				.then (res)->
					$timeout ->
						store.items = res.data
						$rootScope.$broadcast 'markers_changed'
						return
					, 10
					return
				# getLocations()
				# 	.then (response)->
				# 		console.log response
				# 		$timeout ->
				# 			store.items = response.data
				# 			$rootScope.$broadcast 'markers_changed'
				# 			return
				# 		, 10
				# 		return
				return
			zoomChange = ()->
				bounds = new google.maps.LatLngBounds()
				if store.items and store.items.length > 0
					for item in store.items
						latLng = new google.maps.LatLng item.lat, item.lng
						bounds.extend latLng
					store.map.fitBounds bounds
				else
					coords = store.coords.replace(' ', '').split(',')
					latLng = new google.maps.LatLng coords[0], coords[1]
					store.map.setCenter latLng
				delete store.coords
				return

			getLocations = ()->
				storeValue = if store.store then store.store else 0
				storeParam = if storeValue is 0 then 'stores_exclude' else 'stores'
				options = 
					endpoint : 'locations'
					params :
						order_location : "43.7418083,11.291265599999974"
						"#{storeParam}" : storeValue
						per_page : vars.api.store_limit
				wpApi options
			store.onSubmit = ->
				store.isStoreLoading = on
				GeoCoder.geocode { address : store.address}
					.then (res)->
						$timeout ->
							$rootScope.$broadcast 'location_changed', [res[0].geometry.location.lat(), res[0].geometry.location.lng() ]
							return
						return
				window.dataLayer.push 
					'event' : 'GAEvent'
					'eventCategory' : 'Cerca rivenditori'
					'eventAction' : 'storeSubmit'
					'eventLabel' : 'Cerca rivenditore'
					'eventValue' : store.address
				# if store.coords
				# 	if store.store
				# 		wp.locations()
				# 			.stores store.store
				# 			.param 'order_location', store.coords
				# 			.perPage vars.api.store_limit
				# 			.then (res)->
				# 				$timeout ->
				# 					store.items = res
				# 					$rootScope.$broadcast 'markers_changed'
				# 					return
				# 				, 10
				# 				return
				# 	else
				# 		wp.locations()
				# 			.param 'order_location', store.coords
				# 			.perPage vars.api.store_limit
				# 			.then (res)->
				# 				$timeout ->
				# 					store.items = res
				# 					$rootScope.$broadcast 'markers_changed'
				# 					return
				# 				, 10
				# 				return
				# else
				# 	if store.store
				# 		wp.locations()
				# 			.stores store.store
				# 			.perPage vars.api.store_limit
				# 			.then (res)->
				# 				console.log res
				# 				$timeout ->
				# 					store.items = res
				# 					$rootScope.$broadcast 'markers_changed'
				# 					return
				# 				, 10
				# 				return
				# 	else
				# 		wp.locations()
				# 			.perPage vars.api.store_limit
				# 			.then (res)->
				# 				$timeout ->
				# 					store.items = res
				# 					$rootScope.$broadcast 'markers_changed'
				# 					return
				# 				, 10
				# 				return
				return

			wpApi { endpoint : 'stores' }
				.then (res)->
					store.stores = res.data
					return
			store.infoWindow = (id, lat, lng)->
				store.isStore = if store.isStore is id then off else id
				if store.isStore isnt false
					pos = new google.maps.LatLng lat, lng
					store.map.setCenter pos
				return
			getMap = ->
				NgMap
					.getMap()
					.then (map)->
						store.isStoreLoading = on	
						store.map = map
						if navigator.geolocation
								NavigatorGeolocation
									.getCurrentPosition()
										.then (position)->
											latLng = new google.maps.LatLng( position.coords.latitude, position.coords.longitude )
											GeoCoder.geocode { 'latLng': latLng }
												.then (res)->
													store.address = res[0].formatted_address
													store.onSubmit()
													return
											return
						else
							store.onSubmit()
						return
				return
			store.zoom = (cond)->
				val = if cond then 1 else -1;
				store.map.setZoom(store.map.getZoom() + val)
				return
			return
		]