<template>
    <Modal  :show="showModal">
        <div class="m-5">
            <div>
                <h1 class="font-bold">Create User</h1>
                <div class="grid grid-cols-3 gap-6 mt-5">
                    <div class="col-span-3 sm:col-span-3">
                        <label
                            for="company-website"
                            class="block text-sm font-medium leading-6 text-gray-900"
                        >Name</label
                        >
                        <div class="mt-2 flex rounded-md shadow-sm">
                            <input
                                type="text"
                                name="file-name"
                                class="block w-full flex-1 rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder="File Name e.g Forest Adventures"
                                v-model="form.name"
                            />
                        </div>
                        <p class="text-red-500 mt-2" v-if="errorBag.name">
                            {{ errorBag.name[0] }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6 mt-5">
                    <div class="col-span-3 sm:col-span-3">
                        <label
                            for="company-website"
                            class="block text-sm font-medium leading-6 text-gray-900"
                        >Email</label
                        >
                        <div class="mt-2 flex rounded-md shadow-sm">
                            <input
                                type="email"
                                name="file-name"
                                required
                                class="block w-full flex-1 rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder="File Name e.g Forest Adventures"
                                v-model="form.email"
                            />
                        </div>
                        <p class="text-red-500 mt-2" v-if="errorBag.email">
                            {{ errorBag.email[0] }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6 mt-5" v-if="(editMode && generatePassword) || ! editMode">
                    <div class="col-span-3 sm:col-span-3">
                        <label
                            for="company-website"
                            class="block text-sm font-medium leading-6 text-gray-900"
                        >Password (please copy this password)</label
                        >
                        <div class="mt-2 flex rounded-md shadow-sm">
                            <input
                                disabled
                                type="text"
                                required
                                class="block w-full flex-1 rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder="File Name e.g Forest Adventures"
                                v-model="form.password"
                            />
                        </div>
                        <p class="text-red-500 mt-2" v-if="errorBag.password">
                            {{ errorBag.password[0] }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6 mt-5" v-if="editMode">
                    <div class="col-span-3 sm:col-span-3">
                        <label
                            class="flex items-center"
                        >
                            <input
                                type="checkbox"
                                class="form-checkbox"
                                v-model="generatePassword"
                            />
                            <span class="ml-2 text-sm font-medium leading-6 text-gray-900">Generate new password</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6 mt-5">
                    <div class="col-span-3 sm:col-span-3">
                        <label
                            for="company-website"
                            class="block text-sm font-medium leading-6 text-gray-900"
                        >Role</label
                        >
                        <div class="mt-2 flex rounded-md shadow-sm">
                            <select
                                name="file-name"
                                required
                                class="block w-full flex-1 rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="form.role"
                            >
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <p class="text-red-500 mt-2" v-if="errorBag.title">
                            {{ errorBag.email[0] }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 flex" v-if="editMode">
                    Block<Switch class="ml-2" :toggle-state="toggleVisibility" @update:visibility="updateVisibility"/>
                </div>

                <div class="mt-5 space-x-3 text-right">
                    <PrimaryButton :disabled="isLoading" @click="createUser">{{ isLoading ? 'Uploading - Please wait...' : editMode ? 'Edit user' : 'Add user' }}</PrimaryButton>
                    <SecondaryButton @click="emit('close:modal')"
                    >Cancel</SecondaryButton
                    >
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import {computed, reactive, ref} from "vue";
import { defineProps, withDefaults } from 'vue'
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Switch from "@/Components/Switch.vue";
const emit = defineEmits(["close:modal", "upload:success", 'close:modal']);
const props = defineProps(['editMode', 'user', 'showModal'])
const errorBag = ref({});
const isLoading = ref(false);
const generatePassword = ref(false);
const toggleVisibility = ref( props.user ? props.user.is_blocked : false)

const updateVisibility = (value) => {
    toggleVisibility.value = value
}

const form = reactive({
    name: props.user ? props.user.name : '',
    email: props.user ? props.user.email : '',
    password: genPassword(),
    role: props.user ? props.user.role : 'user',
});

const createUser = () => {
    isLoading.value = true;
    form.password_confirmation = form.password;

    if (props.editMode && ! generatePassword.value) {
        delete form.password
    }

    if (props.editMode) {
        form.is_blocked = toggleVisibility.value
    }

    const axiosMethod = props.editMode ? axios.put : axios.post;
    const endpoint = props.editMode
        ? `/api/admin/users/${props.user.id}`
        : "/api/admin/users";

    axiosMethod(endpoint, form)
        .then((response) => {
            emit("create-user:success");
            resetForm();
        })
        .catch((error) => {
            if (error.response.status === 422) {
                errorBag.value = error.response.data.errors;
            }
            console.log(error);
        })
        .finally(() => (isLoading.value = false));
};

const resetForm = () => {
    form.name = props.user ? props.user.name : "";
    form.email = props.user ? props.user.email : "";
    form.password = genPassword();
    form.role = props.user ? props.user.role : "user";
};

function genPassword() {
    const chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const passwordLength = 12;
    let password = "";
    for (let i = 0; i <= passwordLength; i++) {
        const randomNumber = Math.floor(Math.random() * chars.length);
        password += chars.substring(randomNumber, randomNumber +1);
    }
    return password
}

</script>

<style scoped>

</style>
