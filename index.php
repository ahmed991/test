<!DOCTYPE html>
<html>
    <head>
		<title>Photo Map</title>
		<meta charset="utf-8" />
        <link rel="stylesheet" href="css/leaflet.css" />
        <link rel="stylesheet" href="css/Leaflet.Photo.css" />
        <link rel="stylesheet" href="css/MarkerCluster.css" />
		<link rel="stylesheet" href="css/map.css" />
		
        <script src="js/leaflet.js"></script>
        <script src="js/leaflet.markercluster.js"></script>
        <script src="js/Leaflet.Photo.js"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    </head>
    <body style="padding: 0; margin: 0;">
		
		<div id="map" style="width: 100%; height: 100%; padding: 0; margin: 0; background: #fff;"></div>
		
		<script>
			//Create a map
			var map = L.map('map', { maxZoom: 18});
			
			//Add Background Mapping
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 19,
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);
		
			//Create the photolayer
			var photoLayer = L.photo.cluster({ spiderfyDistanceMultiplier: 1.2 }).on('click', function (evt) {
				evt.layer.bindPopup(L.Util.template('<img src="{url}" height="auto" width="100%"/>', evt.layer.photo), {
					className: 'leaflet-popup-photo',
					minWidth: 400
				}).openPopup();
			});
			
			//Call the next function as soon as the page loads
			window.onload = callForImages()

            //Makes a request, loading the getimages.php file
            function callForImages() {

                //Create the request object
                var httpReq = (window.XMLHttpRequest)?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP");
                // console.log(httpReq);

                //When it loads,
                httpReq.onload = function() {
                    console.log("onloaded");
                    console.log(httpReq.responseText);

                    //Convert the result back into JSON
                    var result = JSON.parse(httpReq.responseText);
                    	console.log("result",result.length);

                    //Load the images
                    loadImages(result);
                }

                //Request the page
                try {
                    httpReq.open("GET", "getphotos.php", true);
                    // console.log("success")
                    httpReq.send();
                } catch(e) {
                    console.log("error");
                    console.log(e);
                }
            }


            //Generates the images and sticks them into the photolayer
            function loadImages(images) {
				var photos = [];
                //Loop over the images
                for(var i = 0; i < images.length; i++) {
					photos.push({
						lat: images[i].lat,
						lng: images[i].lng,
						url: images[i].filename,
						//If you have thumbnails, switch the comments on the following lines.
						thumbnail: images[i].filename

						//thumbnail: images[i].thumbnail
					});
                }
                // console.log(photos);
				photoLayer.add(photos).addTo(map);
				//Add the photos to the map
				map.fitBounds(photoLayer.getBounds());
				//Zoom the map to the photos
            }

        </script>
    </body>
</html>
