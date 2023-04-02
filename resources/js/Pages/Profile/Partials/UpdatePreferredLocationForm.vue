<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css'
import {onMounted, ref} from "vue";
import axios from "axios";

const countries = ref([]);
const isLoading = ref(false)
const successfullyUpdated = ref(false)

onMounted(() => {
    axios.get('/countries').then(res => {
        countries.value = res.data.data
    })
})

const preferred_location = usePage().props.preferred_location;

const form = useForm({
    preferred_location: preferred_location,
});

const submit = () => {
    isLoading.value = true;
    axios.post(route('settings.store'), {
        key: 'preferred_location',
        value: form.preferred_location.id
    }).then(() => {
        successfullyUpdated.value = true
    }).finally(() => {
        isLoading.value = false
    })
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Preferred Location</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your preferred location
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name" />

                <vSelect
                    v-model="form.preferred_location"
                    tag-placeholder="Add this as new tag"
                    placeholder="Search or add a tag"
                    label="name"
                    track-by="code"
                    :options="countries"
                />

                <InputError class="mt-2" :message="form.errors.preferred_location" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="isLoading">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="successfullyUpdated" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
