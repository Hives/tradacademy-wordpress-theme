(function($){

	$(document).ready(function() {
		var
			initialiseGoogleMapsVibes,
			initialiseFinalDateCalculator,
            initialiseCourseDateMeta,
            addNewDateRangeBox,
            dateRangeBoxHTML,
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


        /*

        // set up old datepicker box
        // if #ta_course_date_meta exists
        if(true) {

            dateRangeBoxHTML = $('#ta_course_date_meta .daterange').first();

            $('#ta_course_date_meta .add-daterange').click(function(){
                dateRangeBoxHTML.clone().appendTo($('#ta_course_date_meta .dateranges'));
                return false;
            });

            initialiseCourseDateMeta = function($box){
                var
                    dateStartRobot,
                    dateEndRobot,
                    finalDate;

                dateStartRobot = $box.find('.robot.start');
                dateEndRobot = $box.find('.robot.end');
                finalDate = $box.find('.final-date');

                // initialize input widgets first
                $box.find('.human.start').datepicker({
                    dateFormat: 'DD, d MM, yy',
                    altField: dateStartRobot,
                    altFormat: "@",
                });

                $box.find('.human.end').datepicker({
                    dateFormat: 'DD, d MM, yy',
                    altField: dateEndRobot,
                    altFormat: "@",
                });

                $box.find('.time').timepicker({
                    'showDuration': true,
                    'timeFormat': 'g:ia',
                    'scrollDefault': "19:00",
                });

                // initialize datepair
                $box.find('.date-range-initial').datepair({

                    defaultTimeDelta: null,

                    // overwrite default functions to use jQuery UI datepicker
                    parseDate: function (el) {
                        var utc = new Date($(el).datepicker('getDate'));
                        return utc && new Date(utc.getTime() + (utc.getTimezoneOffset() * 60000));
                    },
                    updateDate: function (el, v) {
                        $(el).datepicker('setDate', new Date(v.getTime() - (v.getTimezoneOffset() * 60000)));
                    }

                });

                // hide time if "all day" checked
                $box.find('.all-day').click(function(){
                    if ($(this).is(":checked")) {
                        $box.find('.time').hide('200');
                    } else {
                        $box.find('.time').show('200');
                    }
                })

                // show "weeks" dropdown if "repeats" checked
                $box.find('.repeats').click(function(){
                    if ($(this).is(":checked")) {
                        $box.find('.weeks').show('200');
                    } else {
                        $box.find('.weeks').hide('200');
                    }
                })

                // calculate and show the final date when the number of weeks is changed
                $box.find('.weeks select').change(function(){
                    var start, end;
                    
                    end=new Date();
                    
                    initial = $.datepicker.parseDate("@", $box.find('.robot.start').val());
                    console.log(initial);
                    end.setDate(initial.getDate() + (($(this).val() - 1) * 7));
                    
                    finalDate.text("Final date: " + $.datepicker.formatDate('DD, d MM, yy', end));
                });
                // trigger the change function to initialise final date
                $box.find('.weeks select').change();

            };

            initialiseCourseDateMeta($('#ta_course_date_meta .daterange'));


        }

        */

        // set up new datepicker box        
        if(true) {

            initialiseCourseDateMeta = function($box){
                var
                    formFields,
                    dateStartRobot,
                    dateEndRobot,
                    finalDate,
                    showOrHideWeeks,
                    showOrHideTime,
                    showFinalDate;

                fields = {
                    dateTime: {
                        start: {
                            date: {
                                human: $box.find('.human.start'),
                                robot: $box.find('.robot.start')
                            },
                            time: $box.find('.time.start')
                        },
                        end: {
                            date: {
                                human: $box.find('.human.end'),
                                robot: $box.find('.robot.end')
                            },
                            time: $box.find('.time.end')
                        },
                        time: $box.find('.time')
                    },
                    allDay: $box.find('.all-day input'),
                    repeats: $box.find('.repeats input'),
                    weeks: {
                        row: $box.find('.weeks'),
                        num: $box.find('.weeks select'),
                        caption: $box.find('.weeks .cmb2-metabox-description'),
                    }
                }


                dateStartRobot = $box.find('.robot.start');
                dateEndRobot = $box.find('.robot.end');
                finalDate = $box.find('.weeks .cmb2-metabox-description');


                // FIRST INITIALISE WIDGETS

                // initialize input widgets first
                $box.find('.human.start').datepicker({
                    dateFormat: 'DD, d MM, yy',
                    altField: dateStartRobot,
                    altFormat: "@",
                });

                $box.find('.human.end').datepicker({
                    dateFormat: 'DD, d MM, yy',
                    altField: dateEndRobot,
                    altFormat: "@",
                });

                $box.find('.time').timepicker({
                    'showDuration': true,
                    'timeFormat': 'g:ia',
                    'scrollDefault': "19:00",
                });

                // initialize datepair
                $box.find('.date-range-initial').datepair({

                    defaultTimeDelta: null,

                    // overwrite default functions to use jQuery UI datepicker
                    parseDate: function (el) {
                        var utc = new Date($(el).datepicker('getDate'));
                        return utc && new Date(utc.getTime() + (utc.getTimezoneOffset() * 60000));
                    },
                    updateDate: function (el, v) {
                        $(el).datepicker('setDate', new Date(v.getTime() - (v.getTimezoneOffset() * 60000)));
                    }

                });


                // DYNAMIC BITS


                // 01
                // show or hide time if $allDay is checked
                showOrHideTime = function() {
                    if (fields.allDay.is(":checked")) {
                        fields.dateTime.time.hide('200');
                    } else {
                        fields.dateTime.time.show('200');
                    }
                }
                // show/hide according to initial state
                showOrHideTime();
                // show/hide every time checkbox is clicked
                fields.allDay.click(function(){
                    showOrHideTime();
                });


                // 02
                // show or hide weeks if $repeats is checked
                showOrHideWeeks = function() {
                    if (fields.repeats.is(":checked")) {
                        fields.weeks.row.slideDown('200');
                    } else {
                        fields.weeks.row.slideUp('200');
                    }
                }
                // show/hide according to initial state
                showOrHideWeeks();
                // show/hide every time checkbox is clicked
                fields.repeats.click(function(){
                    showOrHideWeeks();
                });


                // 03
                // calculate and show the final date given the start date and number of repeats
                showFinalDate = function(){
                    var
                        $start,
                        start,
                        end,
                        days = (fields.weeks.num.val() - 1) * 7; // number of days between first and last date
                    
                    $start = fields.dateTime.start.date.robot;

                    // check that $start is not empty
                    if ( $start.val() ) {

                        start = $.datepicker.parseDate("@", $start.val());

                        // console.log("--");

                        // console.log("year: "+start.getFullYear());
                        // console.log("month: "+start.getMonth());
                        // console.log("day: "+start.getDate());

                        end = new Date(start.getFullYear(), start.getMonth(), start.getDate()+days);

                        // console.log(start);
                        // console.log(end);

                        // console.log("--");
                        
                        fields.weeks.caption.html("Final date: " + $.datepicker.formatDate('DD, d MM, yy', end));

                    }
                };

                // we need to recalculate the final date when these elements are changed:
                // 1. the 'weeks' dropdown,
                fields.weeks.num.change(function(){
                    console.log("'weeks' changed");
                    showFinalDate();
                });
                // 2. the initial date
                fields.dateTime.start.date.human.change(function(){
                    // console.log(fields.dateTime.start.date.robot.val());
                    showFinalDate();
                })

            };

            initialiseCourseDateMeta($('#ta_daterange_metabox .cmb-repeatable-grouping'));


        }



		pad = function(n) {
			if (parseInt(n) < 10) {
				return "0" + n;
			} else {
				return n;
			}
		}

	});

})(jQuery);