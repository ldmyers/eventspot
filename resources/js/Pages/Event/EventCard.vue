<script setup lang="ts">
import {defineProps, computed} from 'vue';
import {formattedEventTime, formattedDate, isSameDay} from '@/timeUtils';
import {cleanRoute} from '@/routeUtils';

// Props.
const props = defineProps({
    event: Object as () => Event,
});

// Shorten the description if it's too long.
const shortDescription = computed(() => {
    if (props.event?.description && props.event.description.length > 150) {
        return `${props.event.description.slice(0, 150)}...`;
    }
    return props.event?.description || '';
});

// Format the event time and date.
const eventTime = computed(() => formattedEventTime(props.event.from_datetime, props.event.to_datetime));

// Generate the route to the event.
const eventRoute = computed(() => {
    let title = cleanRoute(props.event.name);
    return route('event.show', {title: title, id: props.event.id});
});
</script>

<template>
    <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <a :href="eventRoute">
            <img class="rounded-t-lg" :src="`https://picsum.photos/600/400?random=${Math.floor(Math.random() * 1000)}`"
                 alt=""/>
        </a>
        <div class="p-5">
            <span
                class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{
                    event.category.name
                }}</span>
            <a :href="eventRoute">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ event?.name }}</h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ event.location.name }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ eventTime }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ shortDescription }}</p>
            <a :href="eventRoute"
               class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Read more
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        </div>
    </div>
</template>
