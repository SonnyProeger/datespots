<template>
	<div>
		<Head :title="`${form.name}`"/>
		<div class="flex justify-start mb-8 max-w-3xl">
			<h1 class="text-3xl font-bold">
				<Link class="text-indigo-400 hover:text-indigo-600" :href="route('users.index')">Users</Link>
				<span class="text-indigo-400 font-medium">/</span>
				{{ form.name }}
			</h1>
			<img v-if="user.photo" class="block ml-4 w-8 h-8 rounded-full" :src="user.photo"/>
		</div>
		<trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore"> This user has been deleted.
		</trashed-message>
		<div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
			<form @submit.prevent="update">
				<div class="flex flex-wrap -mb-8 -mr-6 p-8">
					<text-input v-model="form.name" :error="form.errors.name"
					            class="pb-8 pr-6 w-full lg:w-1/2"
					            label="Name"/>
					<text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email"/>
					<text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2"
					            type="password" autocomplete="new-password" label="Password"/>
					<select-input v-model="form.role_id" :error="form.errors.role_id" class="pb-8 pr-6 w-full lg:w-1/2"
					              label="Role">
						<option v-if="isSuperAdmin" value="1">SuperAdmin</option>
						<option v-if="isSuperAdmin" value="2">Admin</option>
						<option value="3">Company</option>
						<option value="4">User</option>
					</select-input>
				</div>
				<div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
					<button v-if="!user.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button"
					        @click="destroy">Delete User
					</button>
					<loading-button :loading="form.processing" class="btn-roseGold ml-auto" type="submit">Update User
					</loading-button>
				</div>
			</form>
		</div>
	</div>
</template>

<script>
import {Head, Link} from '@inertiajs/vue3'
import TextInput from '@/Pages/Admin/Shared/TextInput.vue'
import FileInput from '@/Pages/Admin/Shared/FileInput.vue'
import SelectInput from '@/Pages/Admin/Shared/SelectInput.vue'
import LoadingButton from '@/Pages/Admin/Shared/LoadingButton.vue'
import TrashedMessage from '@/Pages/Admin/Shared/TrashedMessage.vue'
import AdminAppLayout from "@/Layouts/AdminAppLayout.vue";
import {AdminUsersMixin} from "@/mixins/AdminUsersMixin.js";

export default {
	components: {
		FileInput,
		Head,
		Link,
		LoadingButton,
		SelectInput,
		TextInput,
		TrashedMessage,
	},
	layout: AdminAppLayout,
	mixins: [AdminUsersMixin],
	props: {
		user: Object,
	},
	remember: 'form',
	data() {
		return {
			form: this.$inertia.form({
				_method: 'put',
				name: this.user.name,
				email: this.user.email,
				password: '',
				role_id: this.user.role_id,
			}),
		}
	},
	methods: {
		update() {
			this.form.post(route('users.update', this.user.id), {
				onSuccess: () => this.form.reset('password', 'photo'),
			})
		},
		destroy() {
			if (confirm('Are you sure you want to delete this user?')) {
				this.$inertia.delete(route('users.destroy', this.user.id))
			}
		},
		restore() {
			if (confirm('Are you sure you want to restore this user?')) {
				this.$inertia.put(route('users.restore', this.user.id))
			}
		},
	},
}
</script>
