<script setup>
import {router} from "@inertiajs/vue3";

defineProps({
    trendings: {
        type: Array,
        required: true
    }
})

const goToDetails = (movieId) => {
    router.visit(`/movies/${movieId}`)
}
</script>

<template>
    <v-sheet
            class="mx-auto"
        >
            <v-slide-group
                show-arrows
                class="d-flex justify-space-evenly"
            >
                <v-row >
                    <v-col v-for="trending in trendings"
                           :key="trending.id"
                    >
                        <v-slide-group-item
                            v-slot="{ isSelected, toggle }"
                        >
                            <v-card class="mx-auto p-2" max-width="400" color="indigo" height="100%" variant="tonal" >
                                <v-img
                                    width="500"
                                    :src="trending.poster_url"
                                    height="300"
                                    @click="toggle"
                                ></v-img>
                                <v-card-title>
                                    <span>{{ trending.title }}</span>
                                </v-card-title>
                                <v-card-subtitle>

                                    <span>{{ trending.release_date }}</span>
                                    <span class="d-flex flex-row items-center">
                                    <v-icon>mdi-star</v-icon>
                                         {{ trending.vote_average.toFixed(1) }}
                                    </span>
                                    <div class="d-flex flex-row ga-2">
                                            <span>
                                                <v-icon>mdi-clock-time-four-outline</v-icon>
                                                {{  trending.extra_info.runtime }} mins
                                            </span>
                                        <span>
                                                <v-icon>mdi-eye-outline</v-icon>
                                                {{ trending.popularity }}
                                            </span>
                                        <span>
                                                <v-icon>mdi-comment-text-outline</v-icon>
                                                {{ trending.vote_count }}
                                            </span>
                                    </div>
                                    <div>
                                        <v-chip
                                            v-for="genre in trending.genders"
                                            :key="genre.id"
                                            class="ma-1"
                                            outlined
                                            color="primary"
                                        >
                                            {{ genre.name }}
                                        </v-chip>
                                    </div>
                                </v-card-subtitle>
                                <v-card-actions>
                                    <v-btn
                                        color="teal-accent-4"
                                        text="Overview"
                                        variant="text"
                                        @click="trending.reveal = true"
                                    ></v-btn>
                                    <v-btn
                                        color="teal-accent-4"
                                        text="See more"
                                        variant="text"
                                        @click="goToDetails(trending.id)"
                                    ></v-btn>
                                </v-card-actions>
                                <v-expand-transition>
                                    <v-card
                                        v-if="trending.reveal"
                                        class="position-absolute w-100"
                                        height="100%"
                                        style="bottom: 0;"
                                    >
                                        <v-card-text class="pb-0">
                                            <p class="text-h4">Overview</p>

                                            <p class="text-medium-emphasis">
                                                {{ trending.overview}}
                                            </p>
                                        </v-card-text>
                                        <v-card-actions class="pt-0">
                                            <v-btn
                                                color="teal-accent-4"
                                                text="Close"
                                                variant="text"
                                                @click="trending.reveal = false"
                                            ></v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-expand-transition>

                            </v-card>
                        </v-slide-group-item>
                    </v-col>
                </v-row>

            </v-slide-group>
        </v-sheet>
</template>

<style scoped>

</style>
