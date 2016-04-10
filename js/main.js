jQuery(document).ready(function($) {

	$('.google-map').each(function(){
		var
			$map = $(this), map, mapOptions, data, location, marker, $reset;

		data = $.parseJSON($map[0].dataset.googlemaps);
		location = new google.maps.LatLng(data.lat, data.lng);

		mapOptions = {
			scrollwheel: false,
			zoom: data.zoom,
			// disableDefaultUI: true,
			zoomControl: true,
			// draggable: false,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			center: location
		};

		map = new google.maps.Map($map[0], mapOptions);
		marker = new google.maps.Marker({
			map: map,
			position: location
		});

		$reset = $map.parent().find('a.reset-map');
		$reset.click(function(){
			// reset map
			map.setCenter(location);
			map.setZoom(data.zoom);
			map.getStreetView().setVisible(false);
			return false;
		});

	});

	// $('.fb-container').each(function(){
	// 	var
	// 		$this = $(this),
	// 		tafb = {};

	// 	FB.api("197522990346703", function(response){
	// 		tafb.url = response.link;
	// 		tafb.name = response.name;

	// 		console.log("response 1:");
	// 		console.log(response);

	// 		FB.api("197522990346703/picture", function(response){

	// 			console.log("response 2:");
	// 			console.log(response);

	// 			tafb.img = response.data.url;

	// 			$this.find('.fb-pic').append('<img src="' + tafb.img + '" />');
	// 			$this.find('.fb-link').attr('href', tafb.url).html(tafb.name);

	// 		});
	// 	});

	// });

	if ( $('.carousel-element').length > 1 ) {
		var Carousel;

		Carousel = function (carousel) {
			this.carousel = carousel;
		};

		Carousel.prototype = {
			init: function () {
				var
					that = this, i=0;

				this.width = this.carousel.width();

				this.items = this.carousel.find('.carousel-element');
				this.current = 0;

				// this.items.each(function(){
				// 	i += 1;
				// 	$(this).find('.carousel-text').append(
				// 		 '<div class="controls">'
				// 		+'<a href="#prev" class="prev"><</a>'
				// 		+ ' ' + i + '/' + that.items.length + ' '
				// 		+'<a href="#next" class="next">></a>'
				// 		+'</div>'
				// 	);
				// })

				this.controls = this.carousel.find('.controls');
				this.controls.next = this.controls.find('.next');
				this.controls.next.click(function(){
					that.slide("n");
					return false;
				});
				this.controls.prev = this.controls.find('.prev');
				this.controls.prev.click(function(){
					that.slide("p");
					return false;
				});

			},

			slide: function (dir) {
				var
					next, $next, $current, len = this.items.length, that = this;

				if (dir === "n") {
					next = (this.current + 1) % len;
				} else if (dir === "p") {
					next = (((this.current - 1) % len) + len) % len;
				}

				$next = $(this.items[next]);
				$current = $(this.items[this.current]);

				if (dir === "n") {
					$next.find('.carousel-text').fadeOut(0);
					$next.css({'left': that.width + 'px'});

					$current.find('.carousel-text').fadeOut('fast', function(){
						$next.animate({'left': '0'}, 'slow');
						$current.animate({'left': '-'+that.width+'px'}, 'slow', function(){
							$next.find('.carousel-text').fadeIn('fast');
						});
					});

				} else if (dir === "p") {
					$next.find('.carousel-text').fadeOut(0);
					$next.css({'left': '-' + that.width + 'px'});

					$current.find('.carousel-text').fadeOut('fast', function(){
						$next.animate({'left': '0'}, 'slow');
						$current.animate({'left': that.width+'px'}, 'slow', function(){
							$next.find('.carousel-text').fadeIn('fast');
						});
					});
				}

				this.current = next;

			}

		};

		$(window).load(function(){
			var carousel = new Carousel($('.carousel-full'));
			carousel.init();
		});

		// (function(){
		// 	var
		// 		iterate,
		// 		images = $('#carousel').find('img'),
		// 		delay = 5000,
		// 		current = 0;

		// 	iterate = function() {

		// 		images.filter('.previous').removeClass('previous');
		// 		images.filter('.current').addClass('previous').removeClass('current');

		// 		current = (current + 1) % images.length;
		// 		$(images[current]).addClass('current');

		// 		window.setTimeout(function(){
		// 			iterate();
		// 		}, delay)

		// 	}
		// 	window.setTimeout(function(){
		// 		iterate();
		// 	}, delay)

		// })();
	}

});