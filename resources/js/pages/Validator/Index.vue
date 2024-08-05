<template>
    <v-container >
        <v-row  style="padding: 15px; gap: 5px" >
            <div class="flex-grow-1">
                <h1> Validator </h1>
            </div>
            <v-btn color="secondary" @click="goToCreate">
                Create
                <v-icon> mdi-plus </v-icon>
            </v-btn>
            <v-btn color="secondary" @click="goToCreateJSON">
                Create by JSON
                <v-icon> mdi-plus </v-icon>
            </v-btn>
            <v-btn color="primary" @click="goToImport">
                Import
                <v-icon> mdi-plus </v-icon>
            </v-btn>

        </v-row>
        <v-divider></v-divider>
        <ListValidatorTemplates
            :templates="templates"
        />
    </v-container>
</template>
<script setup>
    import ListValidatorTemplates from "../../components/Validator/List/ListValidatorTemplates.vue";
    import {router} from "@inertiajs/vue3";

    defineProps({
        templates: Object
    })
    const goToCreate = () => {
        router.visit('/validator/create')
    }
    const goToImport = () => {
        router.visit('/validator/import/csv')
    }

    const goToCreateJSON = () => {
        router.visit('/validator/createByJson')
    }
</script>
