<script>
export default {
	name: "AddressForm",
	mounted() {
		let autocomplete;
		let location;
		let addressField = document.getElementById("addressSearchBar");
		const options = {
			types: ['(cities)'],
			componentRestrictions: {
				country: ['nl']
			}
		};

		autocomplete = new google.maps.places.Autocomplete(
				addressField,
				options,
		);

		autocomplete.addListener("place_changed", () => {
			const selectedPlace = autocomplete.getPlace();
			if (selectedPlace.name) {
				location = selectedPlace.name
				this.$emit("address-updated", location)
			} else {
				console.error("Invalid geometry information for the selected place:", selectedPlace);
			}
		});
	},
}
</script>

<template>
	<input
			id="addressSearchBar"
			type="text"
			class="w-2/3 text-center md:text-left md:w-1/2 px-4 py-2 border rounded-md mr-4 focus:outline-none"
			placeholder="Enter your location (city)"
	/>
</template>

<style scoped>

</style>
