<template>
    <v-container >
        <v-row justify="space-between" style="padding: 15px">
            <h1> Create Validator </h1>
            <v-btn color="deep-orange" @click="goBack">
                <v-icon> mdi-arrow-left </v-icon>
                Return
            </v-btn>
        </v-row>

        <CreateValidatorTemplate @save="saveForm"/>
    </v-container>
</template>

<script setup>
import CreateValidatorTemplate from "../../../components/Validator/CreateEdit/CreateEditValidatorTemplate.vue";
import {router} from "@inertiajs/vue3";
import {templateValidatorService} from "../../../services/crud/TemplateValidatorService";

const goBack = () => {
    router.visit('/validator')
}

const saveForm = async  (data) => {
    try {
        await templateValidatorService.create(data)
    } catch (e) {
        console.log(e)
    }
}

</script>
