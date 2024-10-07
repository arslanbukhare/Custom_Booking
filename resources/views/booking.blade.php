<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenStreetMap - Autocomplete</title>

    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    
    <!-- Leaflet.js JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Custom Styles -->
    <style>
        #map {
            height: 400px;
            margin-top: 20px;
        }

        .autocomplete-list {
            border: 1px solid #ddd;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            z-index: 1000;
            background-color: white;
            width: 100%;
        }

        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
        }

        .autocomplete-item:hover {
            background-color: #f0f0f0;
        }
        #map
        {
            display:none;
        }
    </style>
</head>
<body>

    <h1>Book a Trip</h1>

    <!-- Form with Pick-up and Drop-off location inputs -->
    <form>
        <label for="pickup-location">Pick-up Location:</label>
        <input type="text" id="pickup-location" name="pickup_location" placeholder="Enter pick-up location" autocomplete="off" required>
        <div id="pickup-suggestions" class="autocomplete-list"></div><br><br>

        <label for="dropoff-location">Drop-off Location:</label>
        <input type="text" id="dropoff-location" name="dropoff_location" placeholder="Enter drop-off location" autocomplete="off" required>
        <div id="dropoff-suggestions" class="autocomplete-list"></div><br><br>

        <div id="map"></div>

        <button type="submit">Submit</button>
    </form>

    <!-- JavaScript -->
    <script>
        // Initialize Leaflet map
        var map = L.map('map').setView([51.505, -0.09], 13); // Default view (can be changed)

        // Set up OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Function to add location markers
        function addMarker(lat, lng, label) {
            L.marker([lat, lng]).addTo(map).bindPopup(label).openPopup();
        }

        // Autocomplete function for location search
        function autocomplete(input, suggestionsContainer, type) {
            input.addEventListener('input', function() {
                var query = this.value;
                if (query.length < 3) return;

                // Use OpenCage Data API (replace with your own key)
                fetch(`https://api.opencagedata.com/geocode/v1/json?q=${query}&key=0dabac6786e24b1a8b3542a629725429&limit=5`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsContainer.innerHTML = ''; // Clear suggestions

                        data.results.forEach(result => {
                            var suggestionItem = document.createElement('div');
                            suggestionItem.className = 'autocomplete-item';
                            suggestionItem.textContent = result.formatted;

                            // When a suggestion is clicked
                            suggestionItem.addEventListener('click', function() {
                                input.value = result.formatted;
                                suggestionsContainer.innerHTML = ''; // Clear suggestions
                                
                                // Center the map on the selected location
                                var lat = result.geometry.lat;
                                var lng = result.geometry.lng;
                                map.setView([lat, lng], 13);

                                // Add marker for the location
                                addMarker(lat, lng, result.formatted);
                            });

                            suggestionsContainer.appendChild(suggestionItem);
                        });
                    });
            });
        }

        // Initialize autocomplete for pick-up and drop-off inputs
        autocomplete(document.getElementById('pickup-location'), document.getElementById('pickup-suggestions'), 'pickup');
        autocomplete(document.getElementById('dropoff-location'), document.getElementById('dropoff-suggestions'), 'dropoff');
    </script>

</body>
</html>
