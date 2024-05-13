<template>
    <div class="mt-4">
        <v-form v-model="formValid" class="form" @submit.prevent="submitForm">
            <v-row align="baseline">
                <v-col cols="8">
                    <v-file-input
                        v-model="file"
                        rounded
                        label="File (csv only)"
                        chips
                        @change="validateFileType"
                        :error-messages="fileErrors"
                    ></v-file-input>
                </v-col>
                <v-col cols="2">
                    <v-checkbox label="Bring Values" v-model="bringValues">

                    </v-checkbox>
                </v-col>
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
                        v-if="templatesOnFile.length"
                        type="info"
                        outlined
                        border
                        variant="flat"
                        rounded
                    >
                        <strong>Templates on file:</strong>
                        <ul>
                            <li v-for="template in templatesOnFile" :key="template">
                                {{ template }}
                                <p v-if="bringValues">
                                    {{ JSON.stringify()}}
                                </p>
                            </li>
                        </ul>
                    </v-alert>
                </v-col>
            </v-row>
            <v-row justify="space-between">
                <v-col cols="10"></v-col>
                <v-col cols="2">
                    <v-btn color="primary">
                        Validate file
                    </v-btn>
                </v-col>

            </v-row>
        </v-form>

    </div>
</template>

<script setup>
import {ref, watch} from "vue";
import {useSwal} from "../../../composables/useSwal";
import { axiosInstance } from "../../../shared/axios";
const file = ref(null)
const formValid = ref(false)

const fileErrors = ref(null);
const templatesOnFile = ref([])

const requiredRule = [v => !!v || 'Field is required']
const gettingTemplates = ref(false)
const bringValues = ref(false)
const fileData = ref({})
const validateFileType = async ({ target }) => {
    const [{ type }] = target.files
    if (type !== 'text/csv') {
        fileErrors.value = ['Unsupported file type']
        file.value = null;
        return;
    }
    gettingTemplates.value = true
    const formData = new FormData();
    formData.append('file', target.files[0]);
    formData.append('templateOnly', !bringValues.value)

    const { data } = await axiosInstance.post('/validator/extractor/csv', formData, {
        headers: {
            "Content-Type": "multipart/form-data"
        }
    })

    if (bringValues.value) {
        const templates = data.templates
        templatesOnFile.value = Object.keys(templates)
        fileData.value = Object.keys(templates).reduce((acc, item) => {
            acc[item] = templates[item].itens
            return acc
        }, {})
        console.log(fileData.value)
    } else {
        templatesOnFile.value = data.templates
    }
    gettingTemplates.value = false
}

watch(file, () => {
    if (!file.value) {
        templatesOnFile.value = []
    }
})

const { alert } = useSwal()
const submitForm = () => {
    if (!file.value) {
        alert("Provide a file")
        return
    }

   // @TODO

}
</script>
