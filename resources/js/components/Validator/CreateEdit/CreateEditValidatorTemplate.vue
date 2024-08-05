<template>
    <v-sheet :elevation="13"  rounded>
        <v-form v-model="formValid" class="form" @submit.prevent="save">
            <v-text-field label="Name" placeholder="Something" v-model="form.name" :rules="nameRule"></v-text-field>
            <v-sheet :elevation="13"  rounded class="p-3 mt-5" >
                <v-row justify="space-between" class="p-3">
                    <h2> &nbsp </h2>
                    <v-btn @click="addField">
                        Add Field
                        <v-icon> mdi-plus </v-icon>
                    </v-btn>
                </v-row>
                <v-container style="max-height: 520px; overflow: auto" ref="containerFields">

                    <v-row v-for="(field, index) in form.fields" ref="refRow">
                        <h2> Field #{{ index }}</h2>
                        <v-divider style="margin: 1.5rem">
                        </v-divider>
                        <v-row align="center">
                            <v-col cols="11">
                                <v-row>
                                    <v-col cols="6" >
                                        <v-text-field label="Field Name" placeholder="Something" v-model="field.name"  :rules="requiredRule"></v-text-field>
                                    </v-col>
                                    <v-col cols="6" >
                                        <v-text-field label="Field Label" placeholder="Something" v-model="field.label"  :rules="requiredRule"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="5">
                                        <v-select
                                            item-title="name"
                                            item-value="value"
                                            label="Type"
                                            v-model="field.type"
                                            :items="fieldTypes"
                                            :rules="requiredRule"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" md="5">
                                        <v-text-field label="Position" placeholder="Something" v-model="field.position" type="number"  :rules="requiredRule"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="2">
                                        <v-checkbox label="Required" v-model="field.required" ></v-checkbox>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="12">
                                        <v-textarea label="Error Message" v-model="field.error_message"  :rules="requiredRule">

                                        </v-textarea>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="3">
                                        <v-text-field label="Default Value" placeholder="Something" v-model="field.default_value"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="3">
                                        <v-text-field label="Min Length" placeholder="Something" v-model="field.min_length" type="number"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="3">
                                        <v-text-field label="Max Length" placeholder="Something" v-model="field.max_length" type="number"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="3">
                                        <v-text-field label="Pattern" placeholder="Something" v-model="field.pattern"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="10">
                                        <v-autocomplete label="Refers To" placeholder="Something" v-model="field.refers_to" :items="referencesFields" item-title="name" item-value="id"/>
                                    </v-col>
                                    <v-col cols="2" >
                                        <v-checkbox label="Unique" v-model="field.unique"></v-checkbox>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="1">
                                <v-btn v-if="hasToShowRemoveField(field)" @click="removeField(index)">
                                    <v-icon color="error"> mdi-delete </v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>


                    </v-row>
                </v-container>
            </v-sheet>
            <v-row align="end" justify="end">
                <v-btn type="submit" class="mt-5" color="green"> Salvar </v-btn>

            </v-row>
        </v-form>
    </v-sheet>
</template>
<script setup>
import { ref } from 'vue';
const formValid = ref(false)

const emit = defineEmits(['save'])

const refRow = ref(null)
const containerFields = ref(null)
const fieldTypes = [
    {
        name: "Text",
        value: "string"
    },
    {
        name: "Integer",
        value: "int"
    },
    {
        name: "Numeric (Decimal or Float)",
        value: "number"
    },
    {
        name: "Date",
        value: "date"
    },
    {
        name: "Date Time",
        value: "datetime"
    },
    {
        name: "Boolean",
        value: "boolean"
    },
    {
        name: "Huge Text",
        value: "text"
    }
]

const nameRule = [v => !!v || 'Name is required']

const requiredRule = [v => !!v || 'Field is required']

const { template, isEdit, referencesFields } = defineProps({
    template: {
        type: Object,
        required: false,
        default: {
            name: '',
            fields: [{
                name: '',
                label: '',
                type: '',
                position: 0,
                required: false,
                error_message: '',
                default_value: '',
                min_length: "",
                max_length: "",
                pattern: "",
                unique: false,
                refers_to: "",
            }]
        }
    },
    isEdit: {
        type: Boolean,
        default: false
    },
    referencesFields: {
        type: Array,
        default: []
    }
})

const form = ref({
    ...template
})

console.log(referencesFields)


const fieldsToDelete = ref([])
const hasToShowRemoveField = (field) => {
    if (isEdit && field.id) {
        return true
    }
    if (form.value.fields.length === 1) {
        return false
    }
    return form.value.fields.indexOf(field) === form.value.fields.length - 1
}

const removeField = (index) => {
    if (isEdit && form.value.fields[index].id) {
        fieldsToDelete.value.push(form.value.fields[index].id);
    }
    form.value.fields.splice(index, 1);
    if (!form.value.fields.length) {
        addField();
    }
}

const addField = () => {
    form.value.fields.push({
        name: '',
        label: '',
        type: '',
        position: parseInt(form.value.fields[form.value.fields.length -1]?.position ?? '0') + 1,
        required: false,
        error_message: '',
        min_length: "",
        max_length: "",
        pattern: "",
        unique: false,
        refers_to: "",
    })

    setTimeout(() => {
        const lastRow = refRow.value[refRow.value.length - 1].$el
        const containerEl = containerFields.value.$el
        containerEl.scrollTo({
            top: lastRow.offsetTop,
            behavior: 'smooth'
        })
    }, 100)
}

const save = () => {
    if (formValid.value) {
        emit('save', {
            ...form.value,
            ids_fields_delete: fieldsToDelete.value
        })
    }
}

</script>

<style scoped>
    .form {
        padding: 25px !important;
    }
</style>
