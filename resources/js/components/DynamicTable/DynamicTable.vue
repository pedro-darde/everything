<template>
    <div class="p-5">
        <div class="grid grid-cols-3 gap-2 p-2 mb-2">
            <div :class="[showOrderOptions ? 'col-span-2': 'col-span-3']">
                <v-text-field type="text"
                              v-model="searchString"
                              label="Search"
                              variant="solo-filled"
                >
                </v-text-field>
            </div>
            <v-row>
                <v-col cols="8">
                    <v-autocomplete v-model="columnOrder" id="columnOrder" name="columnOrder" item-value="value"
                                    item-title="text" :items="columnOrderOptions" label="Column Order"
                                    variant="solo-filled">
                        <template v-slot:item="{ props, item }">
                            <v-list-item
                                v-bind="props"
                                :title="item?.title"
                            >
                                <template v-slot:subtitle>
                                    {{ item.value }}
                                </template>

                            </v-list-item>
                        </template>
                    </v-autocomplete>
                </v-col>
                <v-col cols="4">
                    <v-autocomplete v-model="orderDirection" id="orderDirection" name="orderDirection"
                                    variant="solo-filled" :items="['asc', 'desc']" label="Direction">
                    </v-autocomplete>
                </v-col>

            </v-row>
        </div>
        <v-data-table-server
            v-model:items-per-page="itemsPerPageServer"
            :headers="tableHeaders"
            :items-length="serverItemsLength"
            :items="serverItems"
            :loading="loading"
            :search="searchString"
            class="elevation-1"
            @update:options="loadMoreItens"
            noDataText="Nenhum item encontrado"
            itemsPerPageText="Registros por página"
        >
            <template v-slot:item="{ item, props }">
                <slot name="item" :item="item" :props="props">
                    <tr>
                        <template v-for="key in Object.keys(item)">
                            <td
                                v-if="hasColOnHeader(key)"
                                class="text-center"
                            >
                                {{ item[key] }}
                            </td>
                        </template>
                        <td align="center">
                            <v-icon
                                v-for="action in allowedCrudActions"
                                @click="emitCrudEvent(action, item.columns)"
                            >
                                {{ getIconClassByCrudOption(action) }}
                            </v-icon>
                        </td>
                    </tr>
                </slot>

            </template>
        </v-data-table-server>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue"
import _ from 'lodash'
import {axiosInstance} from "../../shared/axios";


const loading = ref(true)

const {table, columnDefinitions, modelProps} = defineProps({
    table: {
        type: String,
        required: true
    },
    columnDefinitions: {
        type: Array,
        required: true
    },
    modelProps: {
        type: Object
    },
    showOrderOptions: {
        type: Boolean,
        default: true
    }
})

const serverItemsLength = ref(1)
const itemsPerPageServer = ref(20)
const serverItems = ref([])
const serverPage = ref(0)

const searchString = ref('')
const columnOrder = ref('')
const orderDirection = ref('asc')

const columnOrderOptions = ref(columnDefinitions.map(column => {
    return {
        value: column.columnName,
        text: column.columnDescription,
        extra: column
    }
}))

const tableHeaders = computed(() => {
    const defaultHeaders = columnDefinitions.map(column => {
        return {
            title: column.columnDescription,
            key: column.columnName,
            sortable: true,
            align: 'center',
        }
    });

    defaultHeaders.push({
        title: 'Actions',
        key: 'actions',
        sortable: false,
        align: "center",
        colspan: allowedCrudActions.value.length
    })

    return defaultHeaders
})

const withThrottleLoad = _.throttle(({page, itemsPerPage, sortBy}) => {
    requestMore({page, itemsPerPage, sortBy, searchString: searchString.value})
}, 1000);


const requestMore = async ({page, itemsPerPage, sortBy, searchString}) => {
    const [{
        key,
        order
    }] = sortBy.length ? sortBy : [{key: '', order: ''}]
    loading.value = true
    const { data } = await axiosInstance.get(`${table}/loadMore`, {
        params: {
            page,
            per_page: itemsPerPage,
            search: searchString,
            order_by: key,
            order_direction: order
        }
    })

    const items = data.data
    serverPage.value = page

    if (itemsPerPage === -1) {
        serverItems.value = items;
        serverItemsLength.value = items.length;
        itemsPerPageServer.value = -1;
    } else {
        serverItems.value = items.data;
        serverItemsLength.value = items.total;
        itemsPerPageServer.value = items.per_page
    }
    loading.value = false
}

const loadMoreItens = ({page, itemsPerPage, sortBy}) => {
    if (page === serverPage.value) return
    if (searchString.value) {
        withThrottleLoad({page, itemsPerPage, sortBy})
    } else {
        requestMore({page, itemsPerPage, sortBy})
    }
}


const {
    crudActions
} = modelProps

const allowedCrudActions = computed(() => {
    return Object.keys(crudActions).filter(key => crudActions[key])
})

const emit = defineEmits(['crudEvent', 'loadMore'])
const hasColOnHeader = (col) => {
    return columnDefinitions.find(column => column.columnName === col)
}

const emitCrudEvent = (crudOperation, register) => {
    emit('crudEvent', {
        crudOperation,
        register
    })
}

onMounted(() => {
    requestMore({
        page: 1,
        itemsPerPage: 10,
        sortBy: []
    })
})

const getIconClassByCrudOption = (crudOption) => {
    switch (crudOption) {
        case 'edit':
        case 'update':
            return 'mdi-pencil-box'
        case 'delete':
            return 'mdi-trash-can'
    }
}
const onEditClick = (id) => {
    console.log(id)
}
</script>
