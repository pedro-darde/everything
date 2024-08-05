<template>
    <DynamicTable
        :column-definitions="columnDefinitions"
        table="validator"
        :model-props="modelProps"
        @crud-event="emitCrudEvent"
        :show-order-options="false"
    >
        <template v-slot:item="{item, props}">
            <tr>
                <td class="text-center">
                    {{ item.id }}
                </td>
                <td class="text-center">
                    {{ item.name }}
                </td>
                <td class="text-center">
                    {{ formatDate(item.created_at) }}
                </td>
                <td class="text-center">
                    {{ formatDate(item.updated_at) }}
                </td>
                <td class="text-center">
                    <div class="d-flex flex-row ga-1 justify-center flex-wrap">
                        <v-chip v-for="field in item.fields"  :text="field.position + ' - ' +  field.name"/>
                    </div>
                </td>
                <td class="text-center">
                    <v-icon @click="editTemplate(item.id)">
                        mdi-pencil
                    </v-icon>
                    <v-icon @click="deleteTemplate(item.id)">
                        mdi-delete
                    </v-icon>
                    <v-icon @click="importTeste(item.id)">
                        mdi-airplane
                    </v-icon>
                </td>
            </tr>
        </template>
    </DynamicTable>
</template>

<script setup>
import { ref } from 'vue';
import DynamicTable from "../../DynamicTable/DynamicTable.vue";
import { useDateFormatter } from "../../../composables/useDateFormatter";
import {router} from "@inertiajs/vue3";
import {useSwal} from "../../../composables/useSwal";
import {templateValidatorService} from "../../../services/crud/TemplateValidatorService.js";


const { formatDate } = useDateFormatter()
const { confirm } = useSwal()

const props = defineProps({
    templates: Object
})


const modelProps = {
    crudActions: {
        'update': true,
        'delete': true
    }
}

const columnDefinitions = [
    {
        columnName: "id",
        columnDescription: "ID",
        extraProps: {}
    },
    {
        columnName: "name",
        columnDescription: "Name",
        extraProps: {}
    },
    {
        columnName: "created_at",
        columnDescription: "Criado em",
        extraProps: {}
    },
    {
        columnName: "updated_at",
        columnDescription: "Updated at",
        extraProps: {}
    },
    {
        columnName: "fields",
        columnDescription: "Fields",
        extraProps: {}
    }
]

const requesting = ref(false)
const emitCrudEvent = (event) => {
    console.log(event)
}

function editTemplate(id) {
   router.visit(`/validator/${id}`)
}

async function importTeste(id)
{
    await templateValidatorService.import(id, {})
}

async function deleteTemplate(id) {
    const accepted = await confirm('Are you shure ?', 'This action is irreversible')

    if (accepted.isConfirmed) {
        await templateValidatorService.delete(id)
    }
}

</script>
