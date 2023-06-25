<template>
    <div>
        <Menu as="div" class="relative inline-block text-left">
            <div>
                <MenuButton class="flex items-center rounded-full hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-100">
                    <span class="sr-only">Open options</span>
                    <EllipsisHorizontalIcon class="h-5 w-5" aria-hidden="true" />
                </MenuButton>
            </div>

            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <MenuItems class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1 flex items-center justify-center">
                        <MenuItem v-slot="{ active }">
                            <a @click.prevent="openReport" href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-7 py-2 text-sm flex items-center']">
                                <FlagIcon class="w-5 h-5 mr-2" />
                                <div>Report</div>
                            </a>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>

        <!--   MODAL     -->
        <div>
            <TransitionRoot as="template" :show="open">
                <Dialog as="div" class="relative z-10" @close="open = false">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                    </TransitionChild>

                    <div class="fixed inset-0 z-10 overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                                    <div>
                                        <label class="text-base font-semibold text-gray-900">Report Media</label>
                                        <fieldset class="mt-4">
                                            <legend class="sr-only">Notification method</legend>
                                            <div class="space-y-4">
                                                <div v-for="reportType in reportTypes" :key="reportType.id" class="flex items-center">
                                                    <input :id="reportType.id" name="notification-method" type="radio" :checked="reportType.id === 'email'" @input="updateSelectedOption(reportType.id)" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                                                    <label :for="reportType.id" class="ml-3 block text-sm font-medium leading-6 text-gray-900">{{ reportType.name }}</label>
                                                </div>
                                                <p class="text-red-500 mt-2" v-if="errorBag.flag_id">
                                                    {{ errorBag.flag_id[0] }}
                                                </p>
                                            </div>
                                        </fieldset>
                                        <div class="mt-2">
                                            <label for="comment" class="block text-sm font-medium leading-6 text-gray-900">Provide additional details</label>
                                            <div class="mt-2">
                                                <textarea rows="4" name="comment" v-model="additionalInfo" id="comment" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <p class="text-red-500 mt-2" v-if="errorBag.reason">
                                                    {{ errorBag.reason[0] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-6">
                                        <button :disabled="isLoading" @click.prevent="saveReport" type="button" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Report</button>
                                        <SecondaryButton
                                            class="mr-3 inline-flex w-full text-center mt-2 justify-center"
                                            @click="open = false"
                                        >Cancel</SecondaryButton
                                        >
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </div>
    </div>
    <Alert
        :show="showAuthModal"
        :title="unauthTitle"
        :description="unauthDescription"
    />
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisHorizontalIcon, FlagIcon } from '@heroicons/vue/20/solid'
import SecondaryButton from "@/Components/SecondaryButton.vue";

const open = ref(false)
const reportTypes = ref({})
const errorBag = ref([])
const props = defineProps(['media'])
const additionalInfo = ref('')
const selectedOption = ref('')
const isLoading = ref(false)
import Alert from "@/Components/Notifications/Alert.vue";
import {usePage} from "@inertiajs/vue3";

const isAuthenticated = !! usePage().props.auth.user;

const unauthTitle = ref('');
const unauthDescription = ref('')
const showAuthModal = ref(false)


const openReport = () => {
    if (! isAuthenticated ) {
        unauthTitle.value = 'Need to report the video?'
        unauthDescription.value = 'Sign in to report inappropriate content.'
        showAuthModal.value = true
        return;
    }
    open.value = ! open.value
}
const updateSelectedOption = (optionId) => {
    selectedOption.value = optionId;
}

const getFlagTypes = () => {
    if (! isAuthenticated ) return;
    axios.get('/media/flag/types/').then(res => {
        reportTypes.value = res.data.data
    })
}

const saveReport = () => {
    isLoading.value = true
    axios.post(`/media/${props.media.id}/flags`, {
        flag_id: selectedOption.value,
        reason: additionalInfo.value
    }).then(() => {
        open.value = false
        Toast.fire({
            icon: 'success',
            title: 'Report successfully received'
        })
    }).catch((error) => {
        if (error.response.status === 422) {
            errorBag.value = error.response.data.errors;
        }
        console.log(error);
    }).finally(() => {
        isLoading.value = false
    })
}

onMounted(() => {
    getFlagTypes()
})


</script>
