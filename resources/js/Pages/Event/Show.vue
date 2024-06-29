<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import {defineProps, computed} from 'vue';
import {formattedEventTime, formattedDate, isSameDay} from '@/timeUtils';

// Props.
const props = defineProps({
    event: Object as () => Event,
});

// Format the event time and date.
const eventTime = computed(() => formattedEventTime(props.event.from_datetime, props.event.to_datetime));

// Format the description with line breaks.
const formattedDescription = computed(() => {
    return props.event?.description?.replace(/\n/g, '<br>') || '';
});
</script>

<template>
    <Head :title="`${event.name} - ${event.location.name}`"/>
    <div class="bg-cover h-80 bg-center rounded-lg shadow mb-8"
         style="background-image: url(https://picsum.photos/1200/800)">
    </div>
    <span
        class="bg-blue-100 text-blue-800 text-sm font-medium me-4 px-3 py-2 rounded dark:bg-blue-900 dark:text-blue-300">{{
            event.category.name
        }}</span>
    <h1 class="text-4xl pt-6 pb-8">{{ event.name }}</h1>
    <p class="pb-2 text-2xl">{{ event.location.name }}</p>
    <p class="pb-8 text-lg text-gray-500">{{ eventTime }}</p>
    <p class="pb-4 text-lg" v-if="event.description" v-html="formattedDescription"></p>
</template>
