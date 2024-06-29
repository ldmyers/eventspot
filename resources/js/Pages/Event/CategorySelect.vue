<script setup lang="ts">
import {ref, defineProps, defineEmits, onMounted} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import {cleanRoute} from '@/routeUtils';

const emit = defineEmits(['change']);
const props = defineProps({
    categories: Array,
    categoryId: String,
    categoryName: String,
});
const route = useRoute();
const router = useRouter();
const selectedCategory = ref('');

// Category select change event.
const onChange = () => {
    if (selectedCategory.value === '') {
        // All categories.
        router.push({name: 'events', params: {categorySlug: '', categoryId: '', categoryName: ''}});
        emit('change', {categoryId: '', categoryName: ''});
    } else {
        // Specific category.
        const selectedCategoryObject = props.categories.find(category => category.id === selectedCategory.value);
        if (selectedCategoryObject) {
            let categorySlug = cleanRoute(selectedCategoryObject.name);
            router.push({name: 'events',
                params: {
                    categorySlug: categorySlug,
                    categoryId: selectedCategory.value,
                    categoryName: selectedCategoryObject.name
                }
            });
            emit('change', {categoryId: selectedCategory.value, categoryName: selectedCategoryObject.name});
        }
    }
};

// Set selected category on load.
onMounted(() => {
    if (props.categoryId) {
        selectedCategory.value = props.categoryId;
    }
});
</script>

<template>
    <select v-model="selectedCategory" @change="onChange"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 mt-5 mb-5">
        <option value="">All Categories</option>
        <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
        </option>
    </select>
</template>
