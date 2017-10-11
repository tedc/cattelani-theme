WPAPI = require 'wpapi'
wp = new WPAPI
	endpoint :
		"#{vars.main.base}/wp-json/"
wp.locations = wp.registerRoute 'wp/v2', 'locations/',
	params : ['order_location', 'stores']
wp.stores = wp.registerRoute 'wp/v2', 'stores/'
module.exports = ->
	s = 
		templateUrl : "#{vars.main.assets}/tpl/store.tpl.html"
		bindToController : on
		controllerAs : "store"
		controller : ['NgMap', "$timeout", "$rootScope", "$element", (NgMap, $timeout, $rootScope, $element)->
			store = @
			store.isSelected = false
			store.buttonString = vars.strings.btn_stores
			store.any = vars.strings.select_any
			store.isStore = off
			store.empty = vars.strings.empty_store
			$rootScope.address = 'Via Giuseppe Tomaino, Lamezia Terme, CZ, Italia'
			store.$onInit = ->
				store.map = {}
				store.address = ''
				store.api = "https://maps.googleapis.com/maps/api/js?key=#{vars.api.google_api_key}&libraries=visualization,drawing,geometry,places"
				store.styles = [{'featureType': 'all','elementType': 'labels.text.fill','stylers': [{'color': '#ffffff'}]},{'featureType': 'all','elementType': 'labels.text.stroke','stylers': [{'visibility': 'on'},{'color': '#3e606f'},{'weight': 2},{'gamma': 0.84}]},{'featureType': 'all','elementType': 'labels.icon','stylers': [{'visibility': 'off'}]},{'featureType': 'administrative','elementType': 'geometry','stylers': [{'weight': 0.6},{'color': '#1a3541'}]},{'featureType': 'administrative.locality','elementType': 'all','stylers': [{'visibility': 'simplified'}]},{'featureType': 'administrative.neighborhood','elementType': 'all','stylers': [{'visibility': 'off'}]},{'featureType': 'administrative.land_parcel','elementType': 'all','stylers': [{'visibility': 'off'}]},{'featureType': 'landscape','elementType': 'geometry','stylers': [{'color': '#2c5a71'}]},{'featureType': 'landscape.natural','elementType': 'geometry.fill','stylers': [{'color': '#0b1e2d'}]},{'featureType': 'landscape.natural.landcover','elementType': 'geometry.fill','stylers': [{'color': '#0b1e2d'}]},{'featureType': 'landscape.natural.terrain','elementType': 'geometry.fill','stylers': [{'color': '#0b1e2d'},{'lightness': '-38'}]},{'featureType': 'poi','elementType': 'all','stylers': [{'visibility': 'off'}]},{'featureType': 'poi','elementType': 'geometry','stylers': [{'color': '#406d80'}]},{'featureType': 'poi.park','elementType': 'geometry','stylers': [{'color': '#2c5a71'}]},{'featureType': 'road','elementType': 'geometry','stylers': [{'color': '#29768a'},{'lightness': -37}]},{'featureType': 'transit','elementType': 'geometry','stylers': [{'color': '#406d80'}]},{'featureType': 'water','elementType': 'geometry','stylers': [{'color': '#193341'},{'visibility': 'simplified'}]}]
				store.start = if $rootScope.address then $rootScope.address else vars.api.start_latlng
				getMap()
				return
			store.placeChanged = ->
				store.place = @getPlace()
				center = store.place.geometry.location
				store.coords = "#{center.lat()}, #{center.lng()}"
				return
			store.onInputChange = ->
				delete store.coords if store.address is ''
				google.maps.event.trigger store.autoComplete, 'place_changed'
				return
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
				zoomChange()
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
			store.onSubmit = ->
				if store.coords
					if store.store
						wp.locations()
							.stores store.store
							.param 'order_location', store.coords
							.then (res)->
								$timeout ->
									store.items = res
									$rootScope.$broadcast 'markers_changed'
									return
								, 10
								return
					else
						wp.locations()
							.param 'order_location', store.coords
							.then (res)->
								$timeout ->
									store.items = res
									$rootScope.$broadcast 'markers_changed'
									return
								, 10
								return
				else
					if store.store
						wp.locations()
							.stores store.store
							.then (res)->
								console.log res
								$timeout ->
									store.items = res
									$rootScope.$broadcast 'markers_changed'
									return
								, 10
								return
					else
						wp.locations()
							.then (res)->
								$timeout ->
									store.items = res
									$rootScope.$broadcast 'markers_changed'
									return
								, 10
								return
				return
			wp
				.stores()
				.then (res)->
					store.stores = res
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
						store.map = map
						store.coords = "#{store.map.getCenter().lat()}, #{store.map.getCenter().lng()}"
						store.onSubmit()
						return
				return
			store.zoom = (cond)->
				val = if cond then 1 else -1;
				store.map.setZoom(store.map.getZoom() + val)
				return
			return
		]