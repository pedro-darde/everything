<template>
    <v-container >
        <v-row justify="space-between" style="padding: 15px">
            <h1> Create Validator </h1>
            <v-btn color="deep-orange" @click="goBack">
                <v-icon> mdi-arrow-left </v-icon>
                Return
            </v-btn>
        </v-row>

        <CreateValidatorTemplate @save="saveForm" :template="template" :is-edit="true"/>
    </v-container>
</template>

<script setup>
import CreateValidatorTemplate from "../../../components/Validator/CreateEdit/CreateEditValidatorTemplate.vue";
import {router} from "@inertiajs/vue3";
import {templateValidatorService} from "../../../services/crud/TemplateValidatorService.js";


defineProps({
    template: Object,
    required: true
})
const goBack = () => {
    router.visit('/validator')
}

const saveForm = async  (data) => {
    try {
        await templateValidatorService.update(data.id, data)
        goBack()
    } catch (e) {
        console.log(e)
    }
}

</script>
