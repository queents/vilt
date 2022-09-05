<template>
    <div v-if="type.name === 'table'">
        <table
            class="w-full text-left divide-y table-auto xl:rtl:text-right filament-tables-table "
            v-if="collection.total"
        >
            <thead class="bg-gray-100 border-t border-b">
            <tr class="bg-tableHead text-main dark:bg-gray-800 dark:text-white">
                <th
                    class="w-4 px-4 py-4 whitespace-nowrap filament-tables-checkbox-cell"
                >
                    <input
                        @change="bulkAll()"
                        v-model="bulkValue"
                        class="block border-gray-300 rounded shadow-sm text-primary-600 focus:border-primary-600 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                        type="checkbox"
                    />
                </th>
                <th class="filament-tables-header-cell" v-for="(item, key) in listRows" :key="key">
                    <button
                        v-if="item.sortable"
                        @click.prevent="reload(1, 'orderBy', item.name)"
                        :class="{
                            'font-bold': orderBy == item.name,
                        }"
                        class="flex items-center w-full px-0 py-2 space-x-1 text-sm font-normal cursor-default text-main dark:text-white whitespace-nowrap rtl:space-x-reverse"
                    >
                        <span v-if="orderBy == item.name">
                            <i class="bx bx-sort-a-z" v-if="desc"></i>
                            <i class="bx bx-sort-z-a" v-else></i>
                        </span>
                        {{ item.label ? item.label : item.name  }}
                    </button>
                    <span v-else class="flex items-center w-full px-4 py-2 space-x-1 text-sm font-normal cursor-default dark:text-white whitespace-nowrap rtl:space-x-reverse">
                        {{ item.label ? item.label : item.name }}
                    </span>
                </th>
                <slot name="th"></slot>

                <th class="w-5">
                    <span class="text-sm font-normal">{{trans('global.actions')}}</span>
                </th>
            </tr>
            </thead>
            <tbody class="divide-y whitespace-nowrap">
            <tr
                class="hover:bg-primary-500/5 dark:hover:bg-primary-500/5 filament-tables-row dark:text-white"
                v-for="(item, key) in collection.data"
                :key="key"
            >
                <th
                    class="w-4 px-4 whitespace-nowrap filament-tables-checkbox-cell rtl:text-right"
                >
                    <input
                        @input="switchValue(item.id)"
                        :checked="bulk[item.id]"
                        class="block border-gray-300 rounded shadow-sm text-primary-600 focus:border-primary-600 focus:ring focus:ring-primary-200 focus:ring-opacity-50 table-row-checkbox"
                        value="1"
                        type="checkbox"
                    />
                </th>
                    <td
                        class="rtl:text-right text-14"
                        v-for="(field, index) in listRows"
                        :key="index"
                    >
                        <ViltText v-if="field.vue === 'ViltText.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltEmail v-if="field.vue === 'ViltEmail.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltTel v-if="field.vue === 'ViltTel.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltTextArea v-if="field.vue === 'ViltTextArea.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltNumber v-if="field.vue === 'ViltNumber.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltSelect v-if="field.vue === 'ViltSelect.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltSwitch v-if="field.vue === 'ViltSwitch.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltColor v-if="field.vue === 'ViltColor.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltDate v-if="field.vue === 'ViltDate.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltDateTime v-if="field.vue === 'ViltDateTime.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltTime v-if="field.vue === 'ViltTime.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltMedia v-if="field.vue === 'ViltMedia.vue'" @popUp="popUp" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltRepeater v-if="field.vue === 'ViltRepeater.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltSchema v-if="field.vue === 'ViltSchema.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltRich v-if="field.vue === 'ViltRich.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltRelation v-if="field.vue === 'ViltRelation.vue'" :row="field" v-model="item[field.name]" view="table"/>
                        <ViltHasOne v-if="field.vue === 'ViltHasOne.vue'" :row="field" v-model="item[field.name]" view="table"/>

                        <slot name="td"></slot>
                    </td>

                    <td
                        v-if="
                            $slots['actions'] ||
                            $attrs.canView ||
                            $attrs.canViewAny ||
                            $attrs.canEdit ||
                            $attrs.canDelete ||
                            $attrs.canDeleteAny
                        "
                        class="px-4 py-3 whitespace-nowrap filament-tables-actions-cell"
                        :class="{ sorting: orderBy == item.field }"
                    >
                        <div class="flex items-center justify-end gap-4 my-4">
                            <slot name="actions" v-bind="item"></slot>
                            <div>
                                <a
                                    v-if="$attrs.canView || $attrs.canViewAny"
                                    @click.prevent="viewItem(item)"
                                    class="inline-flex items-center justify-center text-sm font-normal filament-tables-link text-primary-700 hover:text-primary-500 filament-tables-link-action"
                                    href="#"
                                    role="button"
                                >
                                    <i class="bx bx-show text-[16px]"></i>
                                    <div class="table_tooltip">
                                        {{ trans("global.view") }}
                                    </div>
                                </a>
                            </div>
                            <div>
                                <a
                                    v-if="$attrs.canEdit"
                                    @click.prevent="editItem(item)"
                                    class="inline-flex items-center justify-center text-sm font-normal filament-tables-link text-warning-700 hover:text-warning-600 filament-tables-link-action"
                                    href="#"
                                    role="button"
                                >
                                    <i class="bx bx-edit text-[16px]"></i>
                                    <div class="table_tooltip">
                                        {{ trans("global.edit") }}
                                    </div>
                                </a>
                            </div>
                            <form>
                                <button
                                    v-if="
                                        $attrs.canDelete || $attrs.canDeleteAny
                                    "
                                    @click.prevent="deleteItem(item)"
                                    type="submit"
                                    class="inline-flex items-center justify-center text-sm font-normal filament-tables-link text-danger-600 hover:text-danger-500 filament-tables-link-action"
                                >
                                    <svg
                                        class="w-4 h-4"
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
                                    <i class="bx bx-delete text-[16px]"></i>
                                    <div class="table_tooltip">
                                        {{ trans("global.delete") }}
                                    </div>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="relative overflow-y-auto" v-else>
            <div class="flex items-center justify-center p-4">
                <div
                    class="flex flex-col items-center justify-center flex-1 p-6 mx-auto space-y-6 text-center bg-white filament-tables-empty-state dark:bg-gray-900"
                >
                    <div
                        class="flex items-center justify-center w-16 h-16 rounded-full bg-tableHead dark:bg-dark_bg"
                    >
                        <svg
                            class="w-6 h-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="#3A9252"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </div>

                    <div class="max-w-xs space-y-1">
                        <h2
                            class="font-normal tracking-tight text-md filament-tables-empty-state-heading"
                        >
                            {{ trans("global.empty") }}
                        </h2>

                        <p
                            class="text-sm font-normal text-gray-500 filament-tables-empty-state-description"
                        ></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import ViltText from '$$/ViltText.vue'
import ViltTel from '$$/ViltTel.vue'
import ViltTextArea from "$$/ViltTextArea.vue";
import ViltNumber from "$$/ViltNumber.vue";
import ViltSelect from "$$/ViltSelect.vue";
import ViltSwitch from "$$/ViltSwitch.vue";
import ViltColor from "$$/ViltColor.vue";
import ViltEmail from "$$/ViltEmail.vue";
import ViltDate from "$$/ViltDate.vue";
import ViltDateTime from "$$/ViltDateTime.vue";
import ViltTime from "$$/ViltTime.vue";
import ViltMedia from "$$/ViltMedia.vue";
import ViltRepeater from "$$/ViltRepeater.vue";
import ViltSchema from "$$/ViltSchema.vue";
import ViltRich from "$$/ViltRich.vue";
import ViltRelation from "$$/ViltRelation.vue";
import ViltHasOne from "$$/ViltHasOne.vue";

export default defineComponent({
    components: {
        Link,
        ViltTextArea,
        ViltText,
        ViltTel,
        ViltNumber,
        ViltSelect,
        ViltSwitch,
        ViltColor,
        ViltEmail,
        ViltDate,
        ViltDateTime,
        ViltTime,
        ViltMedia,
        ViltRepeater,
        ViltSchema,
        ViltRich,
        ViltRelation,
        ViltHasOne,
    },
    props: {
        rows: Array,
        collection: Object,
        url: String,
        bulk: Object,
        desc: Boolean,
        orderBy: String,
        type: Object,
    },
    data() {
        return {
            bulkValue: false

        };
    },
    computed: {
        listRows() {
            let list = [];
            for (let i = 0; i < this.rows.length; i++) {
                if (this.rows[i].list) {
                    list.push(this.rows[i]);
                }
            }

            return list;
        },
        lang() {
            return this.$page.props.data.trans;
        },
        getMessage() {
            return this.$page.props.data.message;
        },
    },
    methods: {
        money(item) {
            if (localStorage.getItem("lang")) {
                let lang = JSON.parse(localStorage.getItem("lang"));
                if (lang.id === "ar") {
                    return item.toLocaleString("en-US", {
                        style: "currency",
                        currency: "EGP",
                    });
                } else if (lang.id === "en") {
                    return item.toLocaleString("ar-EG", {
                        style: "currency",
                        currency: "EGP",
                    });
                }
            }
        },
        trans(key) {
            return this.lang[key] ? this.lang[key] : key;
        },
        bulkAll() {
            this.$emit("all", this.bulkValue);
        },
        updateData(item) {
            let form = this.$inertia.form(item);
            form.submit("post", this.route(this.url + ".update", item.id), {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: () => {
                    if (typeof this.getMessage === "object") {
                        if (this.getMessage.type === "error") {
                            this.$toast.error(this.getMessage.message, {
                                position: "top",
                                style: {
                                    background: "rgba(210, 45, 61, .8)",
                                    "border-radius": "0.25rem",
                                },
                            });
                        } else if (this.getMessage.type === "success") {
                            this.$toast.success(this.getMessage.message, {
                                position: "top",
                                style: {
                                    background: "#7e3af2",
                                    "border-radius": "0.25rem",
                                },
                            });
                        }
                    } else {
                        this.$toast.success(this.getMessage, {
                            position: "top",
                            style: {
                                background: "#7e3af2",
                                "border-radius": "0.25rem",
                            },
                        });
                    }
                },
            });
        },
        reload(index = 1, type = "main", orderBy = null, dir = false) {
            let getDir = "";
            if (dir) {
                getDir = dir;
            } else {
                if (this.desc) {
                    getDir = "desc";
                } else {
                    getDir = "asc";
                }
            }

            let url = {};
            url.page = index;
            this.search ? (url.search = this.search) : "";
            url.per_page = this.per_page;
            orderBy ? (url.orderBy = orderBy) : "";
            url.orderDirection = getDir;

            this.$emit("reload", url);
        },
        viewItem(item) {
            this.$emit("view", item);
        },
        editItem(item) {
            this.$emit("edit", item);
        },
        deleteItem(item) {
            this.$emit("delete", item);
        },
        transItem(item, key) {
            if (
                item[key].hasOwnProperty("ar") &&
                item[key].hasOwnProperty("en")
            ) {
                if (localStorage.getItem("lang")) {
                    let lang = JSON.parse(localStorage.getItem("lang"));
                    if (lang.id === "ar") {
                        return item[key].en;
                    } else if (lang.id === "en") {
                        return item[key].ar;
                    }
                }
            } else {
                return item[key];
            }
        },
        popUp(images){
            this.$emit('media',images)
        },
        switchValue(id){
            this.$emit('switch', id)
        }
    },
});
</script>
