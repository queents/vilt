<script setup>
import {useAttrs, ref, onMounted, watch, toRaw, computed} from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { useGetForm } from '@@/Composables/useGetForm';

import { storeToRefs } from 'pinia';
import { useResourceStore } from '@@/Stores/resourceStore';
import { useReload } from '@@/Composables/useReload';
import { useCookies } from 'vue3-cookies';

const props = defineProps({
    url: String,
    rows: Object,
});

let {
    createModal,
    viewModal,
    deleteModal,
    bulkModal,
    goNext,
    goBack,
    search,
    per_page,
    orderBy,
    desc,
    last_page,
    edit,
    showFilter,
    filters,
    bulk,
    showBulk,
    bulkActionTitle,
    view,
    photoPreview,
    currentPage,
    cols,
} = storeToRefs(useResourceStore());

const resourceStore = useResourceStore();

resourceStore.setCurrentUrl(props.url);

const attrs = useAttrs();
let { lang } = attrs;

let { formMake } = useGetForm();
let form = ref({});

const { cookies } = useCookies();

let { reload } = useReload();

let create = () => {
    form.value = useForm(formMake());
    edit.value = false;
    createModal.value = true;
};

function reloadList() {
    reload();
}

let filterItem = ref({});
let filterObj = ref({});
let filtersObj = ref({});

onMounted(() => {
    handleCookie();
    form.value = useForm(formMake());

    search.value = attrs?.search;
    per_page.value = attrs?.per_page;
    orderBy.value = attrs?.orderBy;
    filters.value = attrs?.filters;
    createModal.value = attrs?.create;
    // last_page.value = collection?.last_page;
});

function handleCookie() {
    let cookieName = `${props.url}-cols`;
    if (!cookies.get(cookieName)) {
        for (let i = 0, ii = props.rows.length; i < ii; i++) {
            if (props.rows[i].list) {
                cols.value[props.rows[i].name] = true;
            }
        }
        resourceStore.setCols(cols.value);
        cookies.set(cookieName, cols.value);
    } else {
        resourceStore.setCols(cookies.get(cookieName));
    }
}

function setFilter(filter) {
    filterObj.value = filter;
}

function makeFilter(filterBy, filterValue) {
    let filter = {};
    filter[filterBy] = filterValue;
}

function getSendedFilter() {
    const requestedFilter = JSON.parse(
        JSON.stringify(filtersObj.value[filterObj.value.name])
    )[filterObj.value.name];

    const ids = requestedFilter.map((item) => item.id);
    const sendedFilter = {};
    sendedFilter[filterObj.value.name] = ids;
    return sendedFilter;
}

watch(
    filtersObj,
    () => {
        let routeObj = route().params;
        let sendedFilter = getSendedFilter();
        store.setFilters(sendedFilter);

        Inertia.get(route(props.url + '.index'), {
            ...routeObj,
            ...sendedFilter,
        });
    },
    {
        deep: true,
    }
);

</script>
<template>
    <div>
        <div>
            <div class="flex justify-between p-4">
                <Bulk
                    :bulk="bulk"
                    :show="showBluk"
                    :collection="collection"
                    @close="showBluk = !showBluk"
                >
                    <button
                        v-if="attrs.canDeleteAny"
                        type="button"
                        class="flex items-center w-full h-8 px-3 text-sm font-normal text-danger bg-danger group whitespace-nowrap focus:outline-none"
                        @click="bulkAction('delete')"
                    >
                        <svg
                            class="flex-shrink-0 w-6 h-6 mr-2 -ml-1 rtl:ml-2 rtl:-mr-1 group-hover:text-white group-focus:text-white text-danger-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            ></path>
                        </svg>
                        <span class="truncate">
                                    {{ attrs.lang.bulk }}
                                </span>
                    </button>
                </Bulk>
                <Filters
                    :showFilter="
                                attrs.table.filters &&
                                attrs.table.filters.length
                            "
                    :setSearch="search"
                    :rows="rows"
                    :url="url"
                    @getSearch="searchFilter"
                    @reset="resetFilter"
                    @filter="filter"
                >
                    <ViltForm
                        v-for="(filter, key) in attrs.table.filters"
                        v-model="filtersObj[filter.name]"
                        :rows="filter.rows"
                        :key="key"
                        @update:modelValue="setFilter(filter)"
                    />
                </Filters>
            </div>
        </div>
        <div class="px-6 pt-6 mx-auto">
            <div
                class="bg-white dark:bg-gray-800 dark:border-gray-600 rounded-lg border"
            >
                <div class="relative overflow-y-auto dark:border-gray-800">
                    <List
                        @media="popUp"
                        :canEdit="attrs.canEdit"
                        :type="attrs.table"
                        :canDelete="attrs.canDelete"
                        :canDeleteAny="attrs.canDeleteAny"
                        :canView="attrs.canView"
                        :canViewAny="attrs.canViewAny"
                        @reload="reloadList"
                        @view="viewItem"
                        @sub="subItem"
                        @edit="editItem"
                        @delete="deleteItem"
                        @all="bulkAll"
                        @switch="switchBulk"
                        :rows="rows"
                        :collection="collection"
                        :bulk="bulk"
                        :url="url"
                        :desc="desc"
                    >
                        <template #actions="{ id }">
                            <div
                                v-for="(action, index) in attrs.table.actions"
                                :key="index"
                            >
                                <button
                                    v-if="action.can"
                                    @click.prevent="
                                        !action.url
                                            ? action.modal
                                                ? openModal(action.modal, id)
                                                : fireAction(action.action, id)
                                            : openUrl(action.url)
                                    "
                                    style="padding: 5px 8px"
                                    class="inline-flex items-center justify-center text-sm font-normal filament-tables-link filament-tables-link-action"
                                    :class="
                                        'text-' +
                                        action.type +
                                        '-700 hover:text-' +
                                        action.type +
                                        '-600'
                                    "
                                    role="button"
                                >
                                    <i
                                        class="text-[16px]"
                                        :class="action.icon"
                                    ></i>
                                    <div class="table_tooltip">
                                        {{ action.label }}
                                    </div>
                                </button>
                            </div>
                        </template>
                    </List>
                </div>
                <div class="p-2 border-t" v-if="collection.length">
                    <Pagination :collection="collection" />
                </div>
            </div>
        </div>
        <br />
        <br />
        <CreateModal
            :url="url"
            :title="edit ? lang.edit_title : lang.create_title"
            :show="createModal"
            :edit="edit"
            :item="form"
            :rows="rows"
            :type="attrs.form"
            @close="createModal = !createModal"
            @success="success"
        />
        <ViewModal
            @media="popUp"
            :title="lang.view_title"
            :show="viewModal"
            :item="form"
            :rows="rows"
            @close="viewModal = !viewModal"
        />
        <DeleteModal
            :url="url"
            :title="lang.delete_title"
            :show="deleteModal"
            :item="form"
            @close="deleteModal = !deleteModal"
            @success="success"
        />
        <BulkModal
            :url="url"
            :title="lang.bulk_title"
            :action="bulkActionTitle"
            :bulk="bulk"
            :show="bulkModal"
            @close="bulkModal = !bulkModal"
            @success="success"
        />

    </div>
</template>

<script>
import { defineComponent } from 'vue';
import MixinVue from '@/Components/Base/Mixin.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetDialogModal from '@/Jetstream/DialogModal.vue';
import ViltForm from '@@/Rows/ViltForm.vue';
import ResourceTableLayout from '@@/Layouts/ResourceTableLayout.vue';

export default defineComponent({
    mixins: [MixinVue],
    layout: ResourceTableLayout,
    components: {
        JetDialogModal,
        JetButton,
        JetSecondaryButton,
        ViltForm,
    },
    data() {
        return {
            selectedID: null,
            visible: false,
            imgs: [],
        };
    },

    methods: {
        popUp(images) {
            this.visible = true;
            this.imgs = images;
        },
    },
});
</script>
