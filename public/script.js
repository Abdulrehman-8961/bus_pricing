const map = L.map("map").setView([0, 0], 13);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

document.getElementById("getCoordinates").addEventListener("click", () => {
  const areaName = document.getElementById("areaName").value;

  fetch(
    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
      areaName
    )}`
  )
    .then((response) => response.json())
    .then((data) => {
        console.log(data);
      if (data.length > 0) {
        const latitude = parseFloat(data[0].lat);
        const longitude = parseFloat(data[0].lon);

        document.getElementById(
          "coordinates"
        ).textContent = `Latitude: ${latitude}, Longitude: ${longitude}`;

        // Set the map view to the coordinates
        map.setView([latitude, longitude], 13);
        L.marker([latitude, longitude]).addTo(map);
      } else {
        document.getElementById("coordinates").textContent =
          "Location not found.";
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      document.getElementById("coordinates").textContent = "An error occurred.";
    });
});
