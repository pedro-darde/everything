<script setup>
import {computed, ref, watch} from "vue";

    const props  = defineProps({
        movie: {
            type: Object,
            required: true
        }
    })

    const movieRatingRounded = computed(() => {
        return (props.movie.vote_average.toFixed(2))
    });

    const myRating = ref(movieRatingRounded.value)

    watch(() => myRating.value, (newValue) => {
        console.log(newValue)
    })

</script>

<template>
    <v-container>
        <div class="d-flex flex-row justify-lg-space-between">
            <h1 class="font-weight-bold">
                {{ movie.title }}
            </h1>

            <v-rating v-model="myRating" half-increments clearable hover length="10"></v-rating>
        </div>

        <v-sheet :elevation="5"  class="p-2 mb-2">
            <h3> TMBD Info</h3>
            <div class="d-flex flex-column justify-center">
            <span>
                <b>Released: </b>{{ movie.release_date }}
             </span>
                <span>
                <v-icon> mdi-star</v-icon> {{movieRatingRounded }} / 10
            </span>
            <span>
                <v-icon> mdi-clock-time-four-outline</v-icon> {{ movie.extra_info.runtime }} mins
            </span>

            </div>
        </v-sheet>


        <v-img :src="movie.backdrop_url" class="rounded">

        </v-img>
        <div class="d-flex flex-row ga-2 mt-4">
            <v-chip v-for="genre in movie.genders" :key="genre.id">
                {{ genre.name }}
            </v-chip>
        </div>


    </v-container>
</template>

<style scoped>

</style>
