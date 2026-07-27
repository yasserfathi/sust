<script setup>
import { computed } from 'vue';

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    // The key identifying the current page (e.g., 'about', 'dean_message')
    pageType: {
        type: String,
        required: true,
    },
});

// Access the specific page content from the data object using the pageType key
const pageContent = computed(() => {
    return props.data[props.pageType] || {};
});

// Generate a readable title from the pageType
const title = computed(() => {
    const labels = {
        'about': 'About Us',
        'dean_message': 'Dean\'s Message',
        'activities': 'Activities',
        'vision_mission_objectives': 'Vision, Mission & Objectives',
    };

    if (labels[props.pageType]) {
        return labels[props.pageType];
    }

    // Fallback: convert snake_case to Title Case
    return props.pageType
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
});
</script>

<template>
    <v-container>
        <v-row>
            <!-- Main Content Area -->
            <v-col cols="12" md="8">
                <v-card class="mb-6">
                    <!-- Page Image / Banner -->
                    <v-img
                        v-if="pageContent.img"
                        :src="pageContent.img"
                        height="300"
                        cover
                        class="align-end"
                    >
                        <v-card-title class="text-white bg-black bg-opacity-50 text-h4">
                            {{ title }}
                        </v-card-title>
                    </v-img>
                    <v-card-title v-else class="text-h4">
                        {{ title }}
                    </v-card-title>

                    <!-- Page Detail Content -->
                    <v-card-text class="pt-4">
                        <div v-html="pageContent.detail" class="text-body-1"></div>
                    </v-card-text>
                </v-card>

                <!-- Main News Section (if available) -->
                <div v-if="data.news && data.news.length" class="mt-6">
                    <h2 class="text-h5 mb-4">Latest News</h2>
                    <v-row>
                        <v-col v-for="item in data.news" :key="item.id" cols="12" md="6">
                            <v-card :to="`/news/${item.id}`">
                                <v-img
                                    v-if="item.photos && item.photos.length"
                                    :src="item.photos[0].img"
                                    height="200"
                                    cover
                                ></v-img>
                                <v-card-title>{{ item.title }}</v-card-title>
                                <v-card-subtitle>{{ item.news_date }}</v-card-subtitle>
                                <v-card-text>
                                    {{ item.detail_portion }}
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </div>
            </v-col>

            <!-- Sidebar -->
            <v-col cols="12" md="4">
                <!-- Sidebar News -->
                <v-card class="mb-4" v-if="data.recent_news && data.recent_news.length">
                    <v-card-title class="bg-primary text-white">Recent News</v-card-title>
                    <v-list lines="two">
                        <v-list-item
                            v-for="(news, i) in data.recent_news"
                            :key="i"
                            :title="news.title"
                            prepend-icon="mdi-newspaper"
                        ></v-list-item>
                    </v-list>
                </v-card>

                <!-- Departments List -->
                <v-card v-if="data.departments && data.departments.length">
                    <v-card-title class="bg-secondary text-white">Departments</v-card-title>
                    <v-list density="compact">
                        <v-list-item
                            v-for="(dept, i) in data.departments"
                            :key="i"
                            :title="dept.name"
                            prepend-icon="mdi-domain"
                        ></v-list-item>
                    </v-list>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>