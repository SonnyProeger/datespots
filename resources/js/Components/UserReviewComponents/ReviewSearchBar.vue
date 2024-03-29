<script>
import {Link} from "@inertiajs/vue3";

export default {
	name: "ReviewSearchBar",
	components: {Link},
	props: {
		datespots: Object,
	},
	data() {
		return {
			searchText: '',
			autocompleteResults: [],
			showSuggestions: false,
		};
	},
	methods: {
		async fetchAutocompleteResults() {
			const url = '/api/getDatespotsAutocomplete';

			if (this.searchText.length > 0) {
				try {
					const response = await axios.get(url, {
						params: {
							query: this.searchText
						}
					});

					this.autocompleteResults = response.data;
					this.showSuggestions = true;
				} catch (error) {
					console.error('Error fetching autocomplete results:', error);
				}
			} else {
				this.autocompleteResults = [];
				this.showSuggestions = false;
			}
		},
		handleDatespotClick(datespot) {
			this.$inertia.visit(route('user-review.create', {
				id: datespot.id,
				name: datespot.formatted_name,
			}))
		},
		handleMissingPlaceClick() {
			this.$inertia.visit(route('user-datespot.suggest'));
		},
		closeSuggestions() {
			this.wasSuggestionsVisible = this.showSuggestions;
			this.showSuggestions = false;
		},
		openSuggestions() {
			this.showSuggestions = this.wasSuggestionsVisible;
		},
	},
}
</script>

<template>
	<div class="relative w-full">
		<svg
				xmlns="http://www.w3.org/2000/svg"
				fill="none"
				viewBox="0 0 24 24"
				stroke-width="1.5"
				stroke="currentColor"
				class="w-6 h-6 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 z-10"
		>
			<path
					stroke-linecap="round"
					stroke-linejoin="round"
					d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
			/>
		</svg>
		<input
				class="w-full text-center md:text-left pl-10 md:pl-12 py-2 rounded-lg border-gray-300 focus:border-roseGold focus:ring-darkRoseGold rounded-md shadow-sm z-0"
				v-model="searchText"
				@input="fetchAutocompleteResults"
				@blur="closeSuggestions"
				@focus="openSuggestions"

				id="ReviewSearchBar"
				type="text"
				placeholder="What would you like to review?"
		/>

		<ul
				v-if="showSuggestions"
				class="absolute left-0 mt-2 w-full bg-white border rounded-md shadow-md z-10"
		>
			<li
					v-if="autocompleteResults.length"
					v-for="result in autocompleteResults"
					:key="result.id"
					@mousedown='handleDatespotClick(result)'
					class="cursor-pointer py-3 px-4 hover:bg-gray-100 border-b-2"
			>
				<div class="flex items-center space-x-4">
					<div>
						<img :src="result.photo_url" alt="datespot photo" class="w-12 h-12 rounded-md">
					</div>

					<div class="flex flex-col text-left">
						<div class="font-bold">{{ result.name }}</div>
						<div class="text-gray-500">{{ result.address }}</div>
					</div>
				</div>
			</li>
			<li
					class="cursor-pointer py-3 px-4 hover:bg-gray-100 border-b-2"
					@mousedown="handleMissingPlaceClick"
			>
				<div class="flex items-center space-x-4">
					<div>
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
						     stroke="currentColor" class="w-10 h-10">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
							<path stroke-linecap="round" stroke-linejoin="round"
							      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
						</svg>
					</div>
					<div class="text-left">
						<p class="font-bold">Add a missing Datespot</p>
					</div>
				</div>
			</li>
		</ul>
	</div>
</template>

<style scoped>

</style>