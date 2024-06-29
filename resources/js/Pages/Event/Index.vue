<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import {computed, defineProps, onBeforeUnmount, onMounted, ref, watch} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import axios from 'axios';
import EventCard from './EventCard.vue';
import CategorySelect from './CategorySelect.vue';

const events = ref([]);
const loading = ref(false);
const page = ref(1);
const hasMore = ref(true);
const props = defineProps({
    categories: Array,
    categoryId: String,
    categoryName: String,
});
const selectedCategory = ref(null);
const router = useRouter();
const route = useRoute();

// Load initial event data.
onMounted(async () => {
    if (route.params.categoryId) {
        selectedCategory.value = route.params.categoryId;
        await loadEventsByCategory(route.params.categoryId);
    } else {
        await loadMore();
    }
    window.addEventListener('scroll', onScroll);
});

// Infinite scroll to load more events on scroll.
const loadMore = async () => {
    if (loading.value || !hasMore.value) return;
    loading.value = true;
    try {
        const params = {page: page.value};
        if (props.categoryId) {
            params.category = props.categoryId;
        }
        const response = await axios.get('/eventsload', {params});
        if (response.data && response.data.data) {
            const newEvents = response.data.data;
            if (Array.isArray(newEvents)) {
                events.value = [...events.value, ...newEvents];
            } else {
                console.error('Unexpected data format:', response.data);
            }
            page.value += 1;
            if (!response.data.next_page_url) {
                hasMore.value = false;
            }
        } else {
            console.error('No data found in response:', response.data);
        }
    } catch (error) {
        console.error('Error fetching data:', error);
    } finally {
        loading.value = false;
    }
};

// Load more events when the user scrolls to the bottom of the page.
const onScroll = () => {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100) {
        loadMore();
    }
};

// Load more events when the route changes.
watch(route, async (to, from) => {
    if (to.path !== from.path) {
        categoryName.value = to.params.categoryName || '';
        await loadMore();
    }
});

// Cleanup.
onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll);
});

const loadEventsByCategory = async (categoryId) => {
    events.value = [];
    page.value = 1;
    hasMore.value = true;
    selectedCategory.value = categoryId;
    await loadMore();
};

const categoryName = ref(route.params.categoryName || '');

// Load events by category when the category changes.
const handleCategoryChange = (category) => {
    categoryName.value = category.categoryName || '';
    if (category.categoryId) {
        loadEventsByCategory(category.categoryId);
    } else {
        // Reset events and load all events when "All Categories" is selected.
        events.value = [];
        page.value = 1;
        hasMore.value = true;
        loadMore();
    }
};

// Set the page title.
const pageTitle = computed(() => {
    return categoryName.value ? `Events - ${categoryName.value}` : `Events - ${props.categoryName}`;
});
</script>

<template>
    <Head :title="pageTitle"/>
    <CategorySelect :categories="categories" :categoryId="categoryId" @change="handleCategoryChange"/>

    <div class="event-list grid grid-cols-3 gap-4">
        <EventCard v-for="event in events" :key="event.id" :event="event"/>
        <div v-if="loading">
            Loading...
        </div>
    </div>
</template>

