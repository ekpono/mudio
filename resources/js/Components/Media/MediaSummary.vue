<template>
    <div
        class="mt-2 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3"
    >
        <!-- Card -->
        <div
            v-for="card in mediaSummary"
            :key="card.name"
            class="overflow-hidden rounded-lg bg-white shadow"
        >
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <component
                            :is="card.icon"
                            class="h-6 w-6 text-gray-400"
                            aria-hidden="true"
                        />
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt
                                class="truncate text-sm font-medium text-gray-500"
                            >
                                {{ card.name }}
                            </dt>
                            <dd>
                                <div
                                    class="text-lg font-medium text-gray-900"
                                >
                                    {{ card.amount }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    mediaSummary: Object
})
</script>

<style scoped>

</style>
