<template>
  <div>
    <label>
      AutoComplete
      <GmapAutocomplete @place_changed="setPlace">
      </GmapAutocomplete>
      <button @click="usePlace">Add</button>
    </label>
    <br/>

    <GmapMap style="width: 600px; height: 300px;" :zoom="1" :center="{lat: 0, lng: 0}">
      <GmapMarker v-for="(marker, index) in markers"
        :key="index"
        :position="marker.position"
        />
      <GmapMarker
        v-if="this.place"
        label="★"
        :position="{
          lat: this.place.geometry.location.lat(),
          lng: this.place.geometry.location.lng(),
        }"
        />
    </GmapMap>
  </div>
</template>

<script>

export default {
  data() {
    return {
      markers: [],
      place: null,
    }
  },
  description: 'Autocomplete Example (#164)',
  methods: {
    setDescription(description) {
      this.description = description;
    },
    setPlace(place) {
      this.place = place
    },
    usePlace(place) {
      if (this.place) {
        this.markers.push({
          position: {
            lat: this.place.geometry.location.lat(),
            lng: this.place.geometry.location.lng(),
          }
        })
        this.place = null;
      }
    }
  },
  computed: {
    curvedPath () {
      /*
        FIXME: This formula will work for short distances away from
          the poles. It will not work once the curvature of the earth is
          too great
      */
     for (let i = 0; i < this.markers.length; i++) {
      //  const lat = this.markers[lat];
      //  const lng = this.markers[lng];
     }
     return lat
      // if (this.markers.lng && this.marker.lat) {
      //   return range(100)
      //     .map(i => {
      //       const tick = i / 99

      //       /* Bezier curve -- set up the control points */
      //       const dlat = this.marker.position.lat.latLng.lat() - this.marker.position.lng.latLng.lat()
      //       const dlng = this.marker.position.lat.latLng.lng() - this.marker.position.lng.latLng.lng()

      //       const cp1 = {
      //         lat: this.marker.position.lng.latLng.lat() + 0.33 * dlat + 0.33 * dlng,
      //         lng: this.marker.position.lng.latLng.lng() - 0.33 * dlat + 0.33 * dlng,
      //       }

      //       const cp2 = {
      //         lat: this.marker.position.lat.latLng.lat() - 0.33 * dlat + 0.33 * dlng,
      //         lng: this.marker.position.lat.latLng.lng() - 0.33 * dlat - 0.33 * dlng,
      //       }

      //       /* Bezier curve formula */
      //       return {
      //         lat:
      //           (tick * tick * tick) * this.marker.position.lng.latLng.lat() +
      //           3 * ((1 - tick) * tick * tick) * cp1.lat +
      //           3 * ((1 - tick) * (1 - tick) * tick) * cp2.lat +
      //           ((1 - tick) * (1 - tick) * (1 - tick)) * this.marker.position.lat.latLng.lat(),
      //         lng:
      //           (tick * tick * tick) * this.marker.position.lng.latLng.lng() +
      //           3 * ((1 - tick) * tick * tick) * cp1.lng +
      //           3 * ((1 - tick) * (1 - tick) * tick) * cp2.lng +
      //           ((1 - tick) * (1 - tick) * (1 - tick)) * this.marker.position.lat.latLng.lng(),
      //       }
      //     })
      // }
    }
  },

}
</script>