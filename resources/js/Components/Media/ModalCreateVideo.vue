<template>
    <Modal :show="props.toggleCreateModal" @close="emit('close:modal')">
        <div class="m-5">
            <div>
                <h1 class="font-bold">Upload Media File | Video or Audio</h1>
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
                                v-model="form.title"
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
                            <textarea v-model="form.description" rows="3" class="block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:py-1.5 sm:text-sm sm:leading-6" />
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Write a quick description about the file.</p>
                        <p class="text-red-500 mt-2" v-if="errorBag.description">
                            {{ errorBag.description[0] }}
                        </p>
                    </div>
                </div>
                <label
                    class="block mt-4 text-sm font-medium leading-6 text-gray-900"
                >Media File</label
                >
                <VideoUploadForm @video-updated="updateVideo" />
                <p class="text-red-500" v-if="errorBag.file">
                    {{ errorBag.file[0] }}
                </p>
                <div class="py-4">
                    <label
                        for="company-website"
                        class="block text-sm font-medium leading-6 text-gray-900"
                    >Add Tags</label
                    >
                    <vSelect
                        v-model="form.tags"
                        tag-placeholder="Add this as new tag"
                        placeholder="Search or add a tag"
                        label="name"
                        track-by="code"
                        :options="taggingOptions"
                        :multiple="true"
                        :taggable="true"
                    />
                </div>
                <div class="flex items-center mt-5 gap-3">
                    <div>
                        Visibility<span class="text-gray-500 text-sm"> ({{form.visibility}}) </span>:
                        <Switch class="ml-2" :toggle-state="toggleVisibility" @update:visibility="updateVisibility"
                        />
                    </div>
                    <div>
                        Enable Comments:
                        <Switch class="ml-2" :toggle-state="form.comments_enabled" @update:visibility="updateComment"
                        />
                    </div>

                </div>
                <div class="mt-5 space-x-3 text-right">
                    <PrimaryButton @click="uploadFile" :disabled="isLoading">{{ isLoading ? 'Uploading - Please wait...' : 'Save'}}</PrimaryButton>
                    <SecondaryButton @click="emit('close:modal')"
                    >Cancel</SecondaryButton
                    >
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import axios from "axios";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import VideoUploadForm from "@/Components/UploadForm.vue";
import Switch from "@/Components/Switch.vue";
import vSelect from "vue-select";
const props = defineProps(["toggleCreateModal"]);
const emit = defineEmits(["close:modal", "upload:success"]);
import 'vue-select/dist/vue-select.css'

//Form Data
const form = reactive({
    title: ref(""),
    description: ref(""),
    file: ref(""),
    visibility: ref('public'),
    tags: ref(""),
    comments_enabled: ref(true)
})
const isLoading = ref(true);
const errorBag = ref({});
const taggingOptions = ref([])
const tags = ref([]);
const toggleVisibility = ref( props.media ? props.media.visibility === 'public' : false)


const updateVideo = (video) => {
    form.file = video;
};

const updateVisibility = (value) => {
    form.visibility = value ? 'public' : 'private'
}

const updateComment = () => {
    form.comments_enabled = ! form.comments_enabled
}

const uploadFile = () => {
    isLoading.value = true;
    axios
        .post("/file", form, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            console.log(response.data);
            emit("upload:success");
        })
        .catch((error) => {
            if (error.response.status === 422) {
                errorBag.value = error.response.data.errors;
            }
            console.log(error);
        })
        .finally(() => isLoading.value = false)
};

const getTags = async () => {
    let response = await axios.get('/tags');
    taggingOptions.value = response.data.data;
}

onMounted(() => {
    getTags()
})
</script>
