<script>
import {DatespotCityFilterMixin} from "@/mixins/DatespotCityFilterMixin.js";
import {router} from "@inertiajs/vue3";

export default {
	name: "DatespotCityFilter",
	emits: ['saveFilter'],
	mixins: [DatespotCityFilterMixin],
	data() {
		return {
			isFilterVisible: false,
			selectedTypes: [],
			selectedCategories: [],
			selectedSubcategories: [],
			showCategories: [],
			showSubcategories: [],
		}
	},
	computed: {
		isLargeScreen() {
			const element = document.documentElement; // Use an appropriate element
			const width = element.clientWidth;
			return width >= 768;
		},
	},

	props: {
		city: String,
		types: Object,
		categories: Object,
		subcategories: Object,
	},

	methods: {
		saveAndCloseFilter() {
			const filterData = {
				selectedTypes: this.selectedTypes,
				selectedCategories: this.selectedCategories,
				selectedSubcategories: this.selectedSubcategories,
			};

			router.post(`/datespots/${this.city}`, filterData)
			this.toggleFilter();
		},
	},
}

</script>

<template>
	<!--Filter Mobile-->
	<div class="block md:hidden">
		<!-- Filter Button -->
		<button @click="toggleFilter"
		        class="w-full bg-roseGold text-white px-4 py-2 rounded flex items-center justify-center">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
			     stroke="currentColor" class="w-6 h-6 mr-2">
				<path stroke-linecap="round" stroke-linejoin="round"
				      d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
			</svg>
			Filter
		</button>
	</div>

	<!-- Filter Content (Hidden by default) -->
	<!-- Filter overlay -->
	<div v-show="isFilterVisible || isLargeScreen"
	     class="md:py-4 md:w-1/4 md:rounded-t-lg md:object-cover md:h-full md:block md:relative flex flex-col absolute h-screen w-screen"
	>

		<div class="bg-white rounded-lg shadow-md h-full w-full">
			<p class="pl-4 pt-4 text-lg font-bold">Filters</p>
			<div class="px-6">
				<hr class="w-full m-2">

				<!--Type filter options-->
				<div class="pb-4" v-for="type in types" :key="type.id">
					<div class="flex flex-row items-center">
						<input type="checkbox" v-model="selectedTypes" :value="type.id"
						       @change="selectAllCategories(type)">
						<div class="flex justify-between w-full hover:cursor-pointer" @click="showCategoriesInFilter(type.id)">
							<p class="pl-2 text-md font-semibold">{{ type.name }}</p>
							<svg v-if="isShowCategorySelected(type.id)" xmlns="http://www.w3.org/2000/svg" fill="none"
							     viewBox="0 0 24 24"
							     stroke-width="1.5"
							     stroke="currentColor" class="w-6 h-6">
								<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
							</svg>
							<svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
							     stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
								<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
							</svg>
						</div>
					</div>
					<hr v-if="isShowCategorySelected(type.id)" class="w-full m-1">

					<!-- Category filter options -->
					<div class="pl-4" v-if="isShowCategorySelected(type.id)"
					     v-for="category in type.categories"
					     :key="category.id">

						<div class="py-1 flex flex-row items-center">
							<input
									type="checkbox"
									v-model="selectedCategories"
									:value="`${type.id}-${category.id}`"
									@change="selectAllSubcategories(type, category)"
							/>
							<div class="flex justify-between w-full  hover:cursor-pointer"
							     @click="showSubcategoriesInFilter(category.id)">
								<p class="pl-2 text-md font-semibold">{{ category.name }}</p>
								<svg v-if="isShowSubcategorySelected(category.id)" xmlns="http://www.w3.org/2000/svg" fill="none"
								     viewBox="0 0 24 24"
								     stroke-width="1.5"
								     stroke="currentColor" class="w-6 h-6">
									<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
								</svg>
								<svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
								     stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
									<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
								</svg>
							</div>
						</div>
						<hr v-if="isCategorySelected(category) || isShowSubcategorySelected(category.id)" class="w-full m-1">

						<!--subcategory options-->
						<div class="pl-4" v-if="isCategorySelected(category) || isShowSubcategorySelected(category.id)"
						     v-for="subcategory in category.subcategories"
						     :key="subcategory.id">
							<div class="py-1 flex flex-row items-center">
								<input
										type="checkbox"
										v-model="selectedSubcategories"
										:value="`${category.id}-${subcategory.id}`"
										@change="checkIfAllSubcategoriesSelected(type, category, subcategory)"
								/>

								<div class="flex justify-between w-full">
									<p class="pl-2 text-md">{{ subcategory.name }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Save and Close button -->
				<div class="p-4 md:pb-4 md:pt-2 flex justify-center">
					<button @click="saveAndCloseFilter"
					        class="w-full bg-roseGold text-white px-3 py-1 rounded hover:bg-rose-700">
						Filter Datespots
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped>

</style>