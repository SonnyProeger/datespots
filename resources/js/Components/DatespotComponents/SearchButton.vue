<script>
export default {
	name: "SearchButton",
	props: {
		location: String,
	},
	methods: {
		findDatespots() {
			if (!this.location) {
				console.error("Please provide a valid location.");
				return;
			}

			this.$inertia.visit(`/datespots/${this.location}`);
		},
	}
}
</script>

<template>
	<button
			class="px-6 py-2 text-white bg-roseGold rounded-md hover:bg-rose-700 focus:outline-none"
			@click="findDatespots()"
	>
		Find Datespots
	</button>
</template>

<style scoped>

</style>
