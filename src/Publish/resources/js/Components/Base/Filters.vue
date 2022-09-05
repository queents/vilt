<script setup>
import { ref, watch, useAttrs, defineEmits, onMounted, onUpdated } from 'vue';
import { useTrans } from '@@/Composables/useTrans';
import { useResourceStore } from '@@/Stores/resourceStore';
import { useReload } from '@@/Composables/useReload';
import debounce from 'lodash/debounce';
import { useCookies } from 'vue3-cookies';
import { storeToRefs } from 'pinia';

const props = defineProps({
    setSearch: String,
    showFilter: Boolean,
    rows: Array,
    url: String,
});

const emit = defineEmits([
    'reload',
    'filter',
    'reset',
    'getSearch',
    'changeShowedCols',
]);

const { cookies } = useCookies();

const store = useResourceStore();
let { cols } = storeToRefs(useResourceStore());

const { reload } = useReload();

const { trans } = useTrans();

let search = ref('');
let filters = ref({});
let last_page = ref(1);
let openModal = ref(false);
let showRows = ref(false);

const attrs = useAttrs();
let { lang } = attrs;
let cookieName = `${props.url}-cols`;
function searchFilter() {
    let search = this.search;
    store.currentPage = 1; // reset the current page
    emit('getSearch', search);
}
function resetFilter() {
    emit('reset');
}
function filter(filterBy, filterValue) {
    emit('filter', { filterBy: filterValue });
}

function hideCol(col) {
    store.toggleCol(col);
    cookies.set(cookieName, cols.value);
}

watch(
    search,
    debounce((value) => {
        store.search = search;
        reload();
    }, 300)
);
</script>

<template>
    <div class="flex items-center justify-end w-full gap-2 md:max-w-md">
        <div class="flex-1">
            <div class="filament-tables-search-input">
                <label for="tableSearchQueryInput" class="sr-only">
                    {{ trans('global.search') }}
                </label>

                <div class="relative group">
                    <span
                        class="absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 pointer-events-none w-9 h-9 group-focus-within:text-primary-500"
                    >
                        <svg
                            class="w-5 h-5"
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
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            ></path>
                        </svg>
                    </span>

                    <input
                        @input="searchFilter()"
                        v-model="search"
                        id="tableSearchQueryInput"
                        placeholder="Search"
                        type="search"
                        autocomplete="off"
                        class="block w-full placeholder-gray-400 transition duration-75 border-gray-300 rounded-lg shadow-sm dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800 h-9 pl-9 focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600"
                    />
                </div>
            </div>
        </div>
        <div
            class="relative inline-block filament-tables-filters shrink-0"
            v-if="showFilter"
        >
            <button
                @click.prevent="openModal = !openModal"
                type="button"
                class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-500/5 focus:outline-none filament-tables-icon-button text-primary-500 focus:bg-primary-500/10 hover:bg-gray-300/5 filament-tables-filters-trigger"
            >
                <span class="sr-only"> {{ trans('global.filter') }} </span>

                <i class="bx bx-sm bx-filter-alt"></i>
            </button>

            <div
                class="absolute right-0 z-10 w-screen max-w-sm pl-12 mt-2 transition dark:text-white top-full rtl:right-auto rtl:left-0 rtl:pl-0 rtl:pr-12"
                v-show="openModal"
            >
                <div
                    class="px-6 py-4 space-y-6 bg-white border shadow-xl rounded-xl dark:bg-gray-700 border-1"
                >
                    <button
                        @click.prevent="openModal = !openModal"
                        type="button"
                        class="absolute flex items-center justify-center w-10 h-10 text-gray-500 rounded-full hover:bg-gray-500/5 focus:outline-none filament-tables-icon-button focus:bg-gray-500/10 hover:bg-gray-300/5 top-3 right-3 rtl:right-auto rtl:left-3 shrink-0"
                    >
                        <span class="sr-only"> Close </span>

                        <svg
                            class="w-5 h-5 filament-tables-icon-button-icon"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="#24262d"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>

                    <div
                        class="grid grid-cols-1 gap-6 filament-forms-component-container lg:grid-cols-1"
                    >
                        <div class="col-span-1">
                            <div
                                class="grid grid-cols-1 gap-6 filament-forms-component-container"
                            >
                                <div class="col-span-1">
                                    <div class="filament-forms-field-wrapper">
                                        <div class="space-y-2">
                                            <div
                                                class="flex items-center justify-between space-x-2 rtl:space-x-reverse"
                                            >
                                                <div class="w-full">
                                                    <slot></slot>
                                                    <div
                                                        class="flex items-center justify-end my-2"
                                                    >
                                                        <button
                                                            type="button"
                                                            class="text-sm font-normal hover:underline focus:outline-none focus:underline filament-tables-link text-danger-600 hover:text-danger-500 dark:text-danger-500 dark:hover:text-danger-400"
                                                            @click.prevent="
                                                                resetFilter()
                                                            "
                                                        >
                                                            {{
                                                                trans(
                                                                    'global.filters.reset'
                                                                )
                                                            }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div
                class="relative w-[34px] h-[28px] has_tooltip transition-all ml-2 rtl:ml-0 rtl:mr-2 cursor-pointer hover:opacity-80 rounded flex justify-center items-center bg-main text-white dark:text-white z-[9]"
            >
                <i class="bx bx-sm bx-filter" @click="showRows = !showRows"></i>
                <!--                <div class="table_tooltip">-->
                <!--                    {{ trans("global.filter") }}-->
                <!--                </div>-->
            </div>
        </div>

        <div
            v-show="showRows"
            @click="showRows = !showRows"
            class="fixed z-40 top-0 left-0 w-full inline-block h-screen"
        ></div>
        <div
            v-show="showRows"
            class="absolute right-[45px] rtl:left-[45px] rtl:right-[auto] z-50 top-[270px] w-screen max-w-[15rem] mt-2 transition dark:text-white top-full rtl:right-auto rtl:left-0"
        >
            <div
                class="px-2 py-2 space-y-6 bg-white border shadow-xl rounded-xl dark:bg-gray-700 border-1"
            >
                <div
                    class="grid grid-cols-1 gap-6 filament-forms-component-container lg:grid-cols-1"
                >
                    <div class="col-span-1">
                        <div
                            class="grid grid-cols-1 gap-6 filament-forms-component-container"
                        >
                            <div class="col-span-1">
                                <div class="filament-forms-field-wrapper">
                                    <div class="space-y-2">
                                        <div
                                            class="flex items-center justify-between space-x-2 rtl:space-x-reverse"
                                        >
                                            <div
                                                class="w-full border rounded-md border-[#cbc5c5]"
                                            >
                                                <div
                                                    class="rounded-lg"
                                                    v-for="(row, key) in rows"
                                                    :key="key"
                                                    :class="{
                                                        selected_column:
                                                            cols[row.name],
                                                    }"
                                                >
                                                    <div
                                                        class="py-2 px-2 cursor-pointer"
                                                        v-if="row.list"
                                                    >
                                                        <label
                                                            :for="row.name"
                                                            class="cursor-pointer ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                        >
                                                            <input
                                                                @change="
                                                                    hideCol(
                                                                        row.name
                                                                    )
                                                                "
                                                                :checked="
                                                                    cols[
                                                                        row.name
                                                                    ]
                                                                "
                                                                :id="row.name"
                                                                type="checkbox"
                                                                class="cursor-pointer w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 mr-1 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            />
                                                            {{
                                                                row.label ??
                                                                row.name
                                                            }}</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
