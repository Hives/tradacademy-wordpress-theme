(function($){

	$(document).ready(function() {
		var
			initialiseGoogleMapsVibes,
			initialiseFinalDateCalculator,
			pad;

		initialiseGoogleMapsVibes = function () {
			var
				addressString, previousAddressString, lastSearch,
				address,
				$data,
				mapData,
				initialiseMarker = true,
				geocoder,
				map,
				marker,
				getAddressString,
				updateMap,
				updateDataField,
				updateMapInterval,
				initialise,
				s, i;

			getAddressString = function () {
				addressString = "";
				for (i = 0; i < address.length; i++) {
					s = address[i].val();
					if (s !== "") {
						if (addressString !== "") {
							addressString += ", ";
						}
						addressString += s;
					}
				};

			};

			updateMap = function () {

				if (addressString !== lastSearch && addressString === previousAddressString){

					geocoder.geocode( { 'address': addressString}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {

							map.setCenter(results[0].geometry.location);
							if (typeof marker !== 'undefined') {
								marker.setMap(null)
							};
							marker = new google.maps.Marker({
								map: map,
								position: results[0].geometry.location
							});
							updateDataField();
						} else {
							console.log('Geocode was not successful for the following reason: ' + status);
						}

						lastSearch = addressString;
					});

				}

				previousAddressString = addressString;

			};

			updateDataField = function () {
				mapData = {
					lat: map.center.lat(),
					lng: map.center.lng(),
					zoom: map.zoom
				};
				console.log(mapData);
				$data.val(JSON.stringify(mapData));
			}

			initialise = function () {
				var mapOptions, location, zoom;

				address = [
					$('#titlediv input'),
					$('#_cmb_address_1'),
					$('#_cmb_address_2'),
					$('#_cmb_address_3'),
					$('#_cmb_city'),
					$('#_cmb_post_code'),
				]
				$data = $('#_cmb_google_map_data');
				mapData = $.parseJSON($data.val());

				geocoder = new google.maps.Geocoder();

				try {
					location = new google.maps.LatLng(mapData.lat, mapData.lng);
				} catch (e) {
					location = false;
					initialiseMarker = false;
				}
				try {
					zoom = mapData.zoom;
				} catch (e) {
					zoom = 15;
				}

				mapOptions = {
					zoom: zoom,
					disableDefaultUI: true,
					zoomControl: true,
					scrollwheel: false,
					draggable: false,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				if (location !== false) {
					mapOptions.center = location;
				}

				map = new google.maps.Map(document.getElementById("_cmb_google_map"), mapOptions);

				google.maps.event.addListener(map, 'zoom_changed', function() {
					updateDataField();
				});

				if (initialiseMarker) {
					marker = new google.maps.Marker({
						map: map,
						position: location
					});
				}

				getAddressString();

				$(address).each(function(){
					$(this).on('keyup change', function(){
						getAddressString();
					});
				});

				updateMapInterval = setInterval(updateMap, 1000);

			};

			initialise();

		};

		initialiseFinalDateCalculator = function () {
			var
				$startDate = $('#_cmb_date_initial_date'),
				$startTime = $('#_cmb_date_initial_time'),
				$numWeeks = $('#_cmb_no_weeks'),
				$desc = $numWeeks.siblings('.cmb_metabox_description'),
				$events = $('#_cmb_events_json'),
				updateDesc, updateDescInterval,
				date,
				startDateOld, startTimeOld, numWeeksOld;

			updateDesc = function () {
				var
					startDateSplit, startDate,
					startTimeSplit, startTime,
					numWeeks,
					endDate,
					allDates,
					i;

				if (startDateOld === $startDate.val() && numWeeksOld === $numWeeks.val() && startTimeOld === $startTime.val()) {
					return;
				}
				startDateOld = $startDate.val();
				startTimeOld = $startTime.val();
				numWeeksOld = $numWeeks.val();

				startDateSplit = $startDate.val().split('/');
				if (startDateSplit[0] === "") {
					$desc.html("");
					return;
				} else {
					console.log(startDateSplit);
					startDate = new Date(startDateSplit[2], startDateSplit[0] - 1, startDateSplit[1]);
				}

				numWeeks = parseInt($numWeeks.val());

				if (numWeeks === 1) {
					$desc.html("");
				} else {
				endDate = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate() + (7 * (numWeeks - 1)));
					$desc.html("End date: " + pad(endDate.getMonth() + 1) + "/" + pad(endDate.getDate()) + "/" + endDate.getFullYear());
				}

				allDates = [];
				for (i = 0; i < numWeeks; i++) {
					date = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate() + (7 * i));
					allDates.push(
						date.getTime() / 1000
					);
				};

				$events.val(JSON.stringify(allDates));

				for (var i = 0; i < allDates.length; i++) {
					date = new Date(allDates[i] * 1000);
					console.log(date);
				};


			};

			setInterval(updateDesc, 500);

		};

		if ($('#course_location_metabox').length > 0) {
			initialiseGoogleMapsVibes();
		};

		// if ($('#date_metabox').length > 0) {
		// 	initialiseFinalDateCalculator();
		// };


		$('#ta_date_picker').each(function(){
			$( "#ta_initial_date_field_human" ).datepicker({
				dateFormat: 'dd/mm/yy',
				altField: "#ta_initial_date_field",
				altFormat: "yy-mm-dd",
			});
  			$("#ui-datepicker-div").wrap('<div class="cmb_element" />');
  		});



		pad = function(n) {
			if (parseInt(n) < 10) {
				return "0" + n;
			} else {
				return n;
			}
		}

	});

})(jQuery);