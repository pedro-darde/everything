<template>
        <v-container>
            <v-row dense align="center" justify="center">
                <h1 class="text-center"> Welcome to the system of everything </h1>
            </v-row>
            <v-row>
                <v-col v-for="(system, name) in availableSystems" :key="system.id" cols="12" md="4">
                    <v-card color="secondary">
                        <v-card-title>{{ name }}</v-card-title>
                        <v-card-text>
                            <p>{{ system.description }}</p>
                        </v-card-text>
                        <v-card-actions >
                            <v-btn color="primary" @click="navigateToSystem(system.route)">View</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
</template>


<script setup>
import { router } from '@inertiajs/vue3'
    defineProps({
        availableSystems: {
            type: Object
        }
    })

    const navigateToSystem = (systemName) => {
        router.visit(`/${systemName}`)
    }
</script>
