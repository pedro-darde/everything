<script setup>
    import {router} from "@inertiajs/vue3";
    import Trendings from "../../components/Movies/Trendings.vue";
    import TopRated from "../../components/Movies/TopRated.vue";

    defineProps({
        trendings: {
            type: Array,
            required: true
        },
        topRated: {
            type: Array,
            required: true
        }
    })

    const goToDetails = (movieId) => {
        router.visit(`/movies/${movieId}`)
    }
</script>

<template>
    <v-container>
        <v-row>
            <v-col>
                <h1 class="text-h5">Trending Movies</h1>
            </v-col>
        </v-row>
        <Trendings :trendings="trendings" />

        <v-row>
            <v-col>
                <h1 class="text-h5">Top Rated Movies</h1>
            </v-col>
            <TopRated :top-rated="topRated" />

        </v-row>
    </v-container>

</template>

<style scoped>

</style>
