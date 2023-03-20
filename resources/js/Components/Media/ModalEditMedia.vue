<script setup>
import {reactive, ref} from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import 'vue-select/dist/vue-select.css'
import vSelect from "vue-select";
import axios from "axios";
import Switch from "@/Components/Switch.vue";

const props = defineProps(['toggleModal', 'media'])
const emit = defineEmits(['close:modal', 'edit:success'])

const errorBag = ref({});
const taggingOptions = reactive([])

const updateVisibility = (value) => {
    props.media.visibility = value ? 'public' : 'private'
}
const updateMedia = () => {
    const payload = {
        title: props.media.title,
        description: props.media.description,
        visibility: props.media.visibility,
        tags: props.media.tags
    }
    axios
        .patch(`/file/${props.media.id}`, payload)
        .then((response) => {
            console.log(response.data);
            emit("edit:success");
        })
        .catch((error) => {
            if (error.response.status === 422) {
                errorBag.value = error.response.data.errors;
            }
            console.log(error);
        });
};
</script>
<template>
    <Modal :show="toggleModal" @close="emit('close:modal')">
        <div class="m-5">
            <div>
                <h1 class="font-bold">Upload Media File | Video or Audio {{media.name}}</h1>
                <div class="grid grid-cols-3 gap-6 mt-5">
                    <div class="col-span-3 sm:col-span-3">
                        <label
                            for="company-website"
                            class="block text-sm font-medium leading-6 text-gray-900"
                        >Title</label
                        >
                        <div class="mt-2 flex rounded-md shadow-sm">
                            <input
                                type="text"
                                name="file-name"
                                class="block w-full flex-1 rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder="File Name e.g Forest Adventures"
                                v-model="media.title"
                            />
                        </div>
                        <p class="text-red-500 mt-2" v-if="errorBag.title">
                            {{ errorBag.title[0] }}
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-6 mt-5">
                    <div class="col-span-3 sm:col-span-3">
                        <label
                            for="company-website"
                            class="block text-sm font-medium leading-6 text-gray-900"
                        >Description</label
                        >
                        <div class="mt-2 flex rounded-md shadow-sm">
                            <textarea v-model="media.description" rows="3" class="block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:py-1.5 sm:text-sm sm:leading-6" />
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Write a quick description about the file.</p>
                        <p class="text-red-500 mt-2" v-if="errorBag.description">
                            {{ errorBag.description[0] }}
                        </p>
                    </div>
                </div>
                <div class="py-4">
                    <label
                        for="company-website"
                        class="block text-sm font-medium leading-6 text-gray-900"
                    >Add Tags</label
                    >
                    <vSelect
                        v-model="media.tags"
                        tag-placeholder="Add this as new tag"
                        placeholder="Search or add a tag"
                        label="name"
                        track-by="code"
                        :options="taggingOptions"
                        :multiple="true"
                        :taggable="true"
                    />
                </div>
                <div class="flex items-center mt-5">
                    Visibility<span class="text-gray-500 text-sm"> ({{media.visibility}}) </span>:
                    <Switch class="ml-2"
                        @update:visibility="updateVisibility"
                        :media="media"
                    />
                </div>
                <div class="mt-5 space-x-3 text-right">
                    <PrimaryButton @click="updateMedia">Update</PrimaryButton>
                    <SecondaryButton @click="emit('close:modal')"
                    >Cancel</SecondaryButton
                    >
                </div>
            </div>
        </div>
    </Modal>
</template>

