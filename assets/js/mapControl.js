document.addEventListener("DOMContentLoaded", function () {
  const locationButton = document.getElementById("locationButton");
  const mapPopup = document.getElementById("mapPopup");
  const saveLocation = document.getElementById("saveLocation");
  const closeMap = document.getElementById("closeMap");
  const locationInput = document.getElementById("location");
  const locationDisplay = document.getElementById("locationDisplay");
  let map;
  let marker;
  let lastPosition = { lat: 29.978736, lng: 31.134208 };
  let locationInfo = { lat: 29.978736, lng: 31.134208 };
  let selectedLatLng;
  let [WHlat, WHlng] = [29.978736, 31.134208];
  let geocodingError = false;

  locationButton.addEventListener("click", () => {
    mapPopup.style.display = "flex";

    if (!map) {
      // Initialize map with Giza coordinates
      const gizaLatLng = { lat: 29.978736, lng: 31.134208 };
      map = L.map("map").setView(gizaLatLng, 9); // Set initial view to Giza

      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
          '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(map);

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const userLatLng = position.coords;
            lastPosition = position.coords;
            map.setView([userLatLng.latitude, userLatLng.longitude], 13);
            selectedLatLng = userLatLng;
          },
          (error) => {
            console.error("Error getting location:", error);
          }
        );
      }

      map.on("click", function (e) {
        selectedLatLng = e.latlng;
        addMarker(selectedLatLng);
      });

      // Update marker on zoom
      map.on("zoomend", function () {
        if (marker && selectedLatLng) {
          marker.setLatLng(selectedLatLng);
        }
      });
    } else {
      map.invalidateSize();
    }
  });

  saveLocation.addEventListener("click", () => {
    if (selectedLatLng) {
      const lat = selectedLatLng.lat;
      const lng = selectedLatLng.lng;

      lastPosition = { lat, lng };

      geocodingError = false;

      fetch(
        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
      )
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          if (data.address) {
            const address = data.address;
            const street =
              address.road || address.residential || address.unclassified || "";
            const neighbourhood =
              address.neighbourhood || address.quarter || "";
            const city = address.city || address.town || address.village || "";
            const county = address.county || "";
            const state = address.state || "";
            const postcode = address.postcode || "";
            const country = address.country || "";

            const locationName = `${street ? street + ", " : ""}${
              neighbourhood ? neighbourhood + ", " : ""
            }${city ? city + ", " : ""}${county ? county + ", " : ""}${
              state ? state + ", " : ""
            }${country ? country + " " : ""}${postcode ? postcode : ""}`;

            locationInput.value = `{${lat},${lng}}`;
            locationDisplay.textContent = locationName;

            const userLocation = L.latLng(lat, lng);
            const WareHouseLocation = L.latLng(WHlat, WHlng);

            locationInfo = {
              lat,
              lng,
              address: locationName,
              distance: userLocation.distanceTo(WareHouseLocation) / 1000,
            };
            // console.log(JSON.stringify(locationInfo));
            locationInput.value = JSON.stringify(locationInfo);

            mapPopup.style.display = "none";
          } else {
            console.error("No address found for these coordinates.");
            geocodingError = true;
            handleGeocodingError(lat, lng);
          }
        })
        .catch((error) => {
          console.error("Error during reverse geocoding:", error);
          geocodingError = true;
          handleGeocodingError(lat, lng);
        });
    } else {
      alert("الرجاء تحديد موقع على الخريطة.");
    }
  });

  closeMap.addEventListener("click", () => {
    addMarker(lastPosition);
    mapPopup.style.display = "none";
  });

  function addMarker(latlng) {
    if (marker) {
      map.removeLayer(marker);
    }

    const redIcon = new L.Icon({
      iconUrl:
        "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png",
      shadowUrl:
        "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-shadow.png",
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41],
    });

    marker = L.marker(latlng, { icon: redIcon }).addTo(map);
  }

  function handleGeocodingError(lat, lng) {
    locationInput.value = `${lat},${lng}`;
    locationDisplay.textContent = `${lat},${lng}`;
    alert("An error occurred while trying to determine the location.");
  }
});
