<template>
    <v-container >
        <v-row justify="space-between" style="padding: 15px">
            <h1> Create new by JSON </h1>
            <v-btn color="deep-orange" @click="goBack">
                <v-icon> mdi-arrow-left </v-icon>
                Return
            </v-btn>
        </v-row>
        <div class="mt-4">
            <v-form v-model="formValid" class="form" @submit.prevent="submitForm">
                <v-row align="baseline">
                    <v-col cols="8">
                        <v-file-input
                            v-model="file"
                            rounded
                            label="File (json only)"
                            chips
                            @change="validateFileType"
                            :error-messages="fileErrors"
                        ></v-file-input>
                    </v-col>
                </v-row>
                <v-row v-if="validationErrors">
                    <v-alert
                        type="error"
                        outlined
                        border
                        variant="flat"
                        rounded
                    >

                        <p v-for="error in validationErrors">{{ error }} </p>
                    </v-alert>
                </v-row>
                <v-row>
                    <v-col cols="12" v-if="gettingTemplates" >
                        <v-skeleton-loader
                            :loading="gettingTemplates"
                            type="list-item-two-line"
                        >
                            <v-list-item
                                lines="two"
                                subtitle="Subtitle"
                                title="Title"
                                rounded
                            ></v-list-item>
                        </v-skeleton-loader>
                    </v-col>

                    <v-col>
                        <v-alert
                            v-if="fileData"
                            type="info"
                            color="inherit"
                            outlined
                            border
                            variant="flat"
                            rounded
                        >
                            <strong>File data</strong>
                            <vue-json-pretty :data="fileData" showLength />

                        </v-alert>
                    </v-col>
                </v-row>

                <v-row justify="space-between">
                    <v-col cols="10"></v-col>
                    <v-col cols="2">
                        <v-btn color="primary" type="submit">
                            Create template
                        </v-btn>
                    </v-col>

                </v-row>
            </v-form>

        </div>
    </v-container>

</template>

<script setup>
import {ref, watch} from "vue";
import {useSwal} from "../../../composables/useSwal";
import { axiosInstance } from "../../../shared/axios";
import VueJsonPretty from 'vue-json-pretty';
import 'vue-json-pretty/lib/styles.css';
import {router} from "@inertiajs/vue3";
const file = ref(null)
const formValid = ref(false)

const fileErrors = ref(null);
const templatesOnFile = ref([])
const validationErrors = ref(null)

const requiredRule = [v => !!v || 'Field is required']
const gettingTemplates = ref(false)
const fileData = ref(null)
const validateFileType = async ({ target }) => {
    const [{ type }] = target.files
    console.log(type)
    if (type !== 'application/json') {
        fileErrors.value = ['Unsupported file type']
        file.value = null;
        return;
    }
    gettingTemplates.value = true
    const formData = new FormData();
    formData.append('file', target.files[0]);


    try {
        const { data } = await axiosInstance.post('/validator/extractor/json', formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        })

        fileData.value = data.data
        gettingTemplates.value = false
    } catch (e) {
        gettingTemplates.value = false
        fileErrors.value = e.response.data.message
    }


}

watch(file, () => {
    if (!file.value) {
        templatesOnFile.value = []
        fileErrors.value = null
        validationErrors.value = null;
    }
})

const { alert,  } = useSwal()

const goBack = () => {
    router.visit('/validator')
}
const submitForm = async () => {
    console.log("aqui")
    if (!fileData.value) return
    try {
      await axiosInstance.post('/validator/createByJson', fileData.value)
    } catch (e) {
        const errors = e.response.data.errors
        validationErrors.value = errors
        // alert
        // if (e.response.)
    }
}
</script>
