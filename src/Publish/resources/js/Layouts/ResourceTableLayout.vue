<script setup>
import { mdiForwardburger, mdiBackburger, mdiMenu } from '@mdi/js';
import menuAside from '@/menuAside.js';
import menuNavBar from '@/menuNavBar.js';
import { useMainStore } from '@/Stores/main.js';
import { useLayoutStore } from '@/Stores/layout.js';
import { useStyleStore } from '@/Stores/style.js';
import BaseIcon from '@/Components/BaseIcon.vue';
import FormControl from '@/Components/FormControl.vue';
import NavBar from '@/Components/NavBar.vue';
import NavBarItemPlain from '@/Components/NavBarItemPlain.vue';
import AsideMenu from '@/Components/AsideMenu.vue';
import FooterBar from '@/Components/FooterBar.vue';
import { Inertia } from '@inertiajs/inertia';
import { computed } from 'vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { useAttrs, ref, onMounted, watch, toRaw } from 'vue';
import VueEasyLightbox from 'vue-easy-lightbox';
import { storeToRefs } from 'pinia/dist/pinia';
import { useResourceStore } from '@@/Stores/resourceStore';
import { useGetForm } from '@@/Composables/useGetForm';

Inertia.on('navigate', () => {
    layoutStore.isAsideMobileExpanded = false;
    layoutStore.isAsideLgActive = false;
});

const layoutAsidePadding = 'xl:pl-60';

const styleStore = useStyleStore();

const layoutStore = useLayoutStore();

const dashboardMenu = computed(
    () => usePage().props.value.data.menus.dashboard
);

const menuClick = (event, item) => {
    if (item.isToggleLightDark) {
        styleStore.setDarkMode();
    }

    if (item.isLogout) {
        // Add:
        Inertia.post(route('logout'));
    }
};

const props = defineProps({
    url: String,
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
} = storeToRefs(useResourceStore());

let store = useResourceStore();

const attrs = useAttrs();

let { formMake } = useGetForm();
let form = ref({});

let actionModal = ref({});
let modalAction = ref({});

let filterItem = ref({});
let filterObj = ref({});
let filtersObj = ref({});

let create = () => {
    form.value = useForm(formMake());
    edit.value = false;
    createModal.value = true;
};

console.log(attrs);

onMounted(() => {
    for (let i = 0; i < attrs.modals.length; i++) {
        actionModal.value[attrs.modals[i].name] = false;
        modalAction.value[attrs.modals[i].name] = {};
        for (let x = 0; x < attrs.modals[i].rows.length; x++) {
            if (attrs.modals[i].rows[x].type === 'relation') {
                modalAction.value[attrs.modals[i].name][
                    attrs.modals[i].rows[x].field
                ] = [];
            } else {
                modalAction.value[attrs.modals[i].name][
                    attrs.modals[i].rows[x].field
                ] = attrs.modals[i].rows[x].default;
            }
        }
    }
    form.value = useForm(formMake());

    search.value = attrs?.search;
    per_page.value = attrs?.per_page;
    orderBy.value = attrs?.orderBy;
    filters.value = attrs?.filters;
    createModal.value = attrs?.create;
    // last_page.value = collection?.last_page;
});

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
    <div
        :class="{
            dark: styleStore.darkMode,
            'overflow-hidden lg:overflow-visible':
                layoutStore.isAsideMobileExpanded,
        }"
    >
        <div
            :class="[
                layoutAsidePadding,
                { 'ml-60 lg:ml-0': layoutStore.isAsideMobileExpanded },
            ]"
            class="pt-14 min-h-screen w-screen transition-position lg:w-auto bg-gray-50 dark:bg-slate-800 dark:text-slate-100"
        >
            <NavBar
                :menu="menuNavBar"
                :class="[
                    layoutAsidePadding,
                    { 'ml-60 lg:ml-0': layoutStore.isAsideMobileExpanded },
                ]"
                @menu-click="menuClick"
            >
                <NavBarItemPlain
                    display="flex lg:hidden"
                    @click.prevent="layoutStore.asideMobileToggle()"
                >
                    <BaseIcon
                        :path="
                            layoutStore.isAsideMobileExpanded
                                ? mdiBackburger
                                : mdiForwardburger
                        "
                        size="24"
                    />
                </NavBarItemPlain>
                <NavBarItemPlain
                    display="hidden lg:flex xl:hidden"
                    @click.prevent="layoutStore.isAsideLgActive = true"
                >
                    <BaseIcon :path="mdiMenu" size="24" />
                </NavBarItemPlain>
            </NavBar>
            <AsideMenu :menu="dashboardMenu" @menu-click="menuClick" />

            <div class="px-6 pt-6 mx-auto">
                <vue-easy-lightbox
                    ref="lightbox"
                    :visible="visible"
                    :imgs="imgs"
                    :index="index"
                    @hide="visible = !visible"
                ></vue-easy-lightbox>
                <Header
                    :canCreate="attrs.canCreate"
                    :title="attrs.lang.index"
                    :button="attrs.lang.create"
                    @createItem="create"
                >
                    <a
                        v-for="(action, index) in attrs.actions"
                        :key="index"
                        :href="action.url ? action.url : '#'"
                        @click.prevent="
                            !action.url
                                ? action.modal
                                    ? openModal(action.modal)
                                    : fireAction(action.action)
                                : openUrl(action.url)
                        "
                        class="mx-2 relative inline-flex items-center px-8 py-3 overflow-hidden text-white bg-main rounded group active:bg-blue-500 focus:outline-none focus:ring"
                    >
                        <span
                            class="absolute left-0 transition-transform -translate-x-full group-hover:translate-x-4"
                        >
                            <i class="bx-sm mt-2" :class="action.icon"></i>
                        </span>

                        <span
                            class="text-sm font-medium transition-all group-hover:ml-4"
                        >
                            {{ action.label }}
                        </span>
                    </a>
                </Header>

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
            </div>

            <slot />
            <FooterBar>
                Github
                <a
                    href="https://www.github.com"
                    target="_blank"
                    class="text-blue-600"
                    >Docs</a
                >
            </FooterBar>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import MixinVue from '@@/Components/Base/Mixin.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetDialogModal from '@/Jetstream/DialogModal.vue';
import ViltForm from '@@/Rows/ViltForm.vue';

export default defineComponent({
    mixins: [MixinVue],
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
        openUrl(url) {
            window.open(url);
        },
        modalActionRun(modal, action) {
            if (this.selectedID) {
                this.modalAction[modal].id = this.selectedID;
            }
            this.$inertia
                .form(this.modalAction[modal])
                .submit('post', route(action), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.modalAction[modal].reset();
                        this.actionModal[modal] = false;
                        this.success();
                    },
                });
        },
        fireAction(name, id = null) {
            this.$inertia.post(route(name), {
                id: id ? id : this.selectedID,
            });
        },
        openModal(name, id = null) {
            this.selectedID = id;
            this.actionModal[name] = !this.actionModal[name];
        },

        popUp(images) {
            this.visible = true;
            this.imgs = images;
        },
    },
});
</script>
