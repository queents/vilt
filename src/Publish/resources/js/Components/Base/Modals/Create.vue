<template>
    <JetDialogModal :show="showModal" @end="close">
        <template #title>
            {{ title }}
        </template>

        <template #content>
            <div v-if="type.name === 'modal'">
                <form
                    class="grid grid-cols-1 gap-3 lg:grid-cols-1"
                    action=""
                    v-if="!edit"
                >
                    <slot name="create-top"></slot>

                    <div
                        v-for="(item, key) in rowFilterCreate"
                        :key="key"
                        class=""
                    >
                        <ViltText v-if="item.vue === 'ViltText.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltEmail v-if="item.vue === 'ViltEmail.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTel v-if="item.vue === 'ViltTel.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTextArea v-if="item.vue === 'ViltTextArea.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltNumber v-if="item.vue === 'ViltNumber.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSelect v-if="item.vue === 'ViltSelect.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSwitch v-if="item.vue === 'ViltSwitch.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltColor v-if="item.vue === 'ViltColor.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltDate v-if="item.vue === 'ViltDate.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltDateTime v-if="item.vue === 'ViltDateTime.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTime v-if="item.vue === 'ViltTime.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltMedia v-if="item.vue === 'ViltMedia.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRepeater v-if="item.vue === 'ViltRepeater.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSchema v-if="item.vue === 'ViltSchema.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRich v-if="item.vue === 'ViltRich.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRelation v-if="item.vue === 'ViltRelation.vue' && item.create" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>

                    </div>
                    <slot name="create"></slot>
                </form>
                <form
                    class="grid grid-cols-1 gap-3 md:grid-cols-1"
                    action=""
                    v-else
                >
                    <slot name="edit-top"></slot>
                    <div
                        v-for="(item, key) in rowFilterEdit"
                        :key="key"
                        class=""
                    >
                        <ViltText v-if="item.vue === 'ViltText.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltEmail v-if="item.vue === 'ViltEmail.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTel v-if="item.vue === 'ViltTel.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTextArea v-if="item.vue === 'ViltTextArea.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltNumber v-if="item.vue === 'ViltNumber.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSelect v-if="item.vue === 'ViltSelect.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSwitch v-if="item.vue === 'ViltSwitch.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltColor v-if="item.vue === 'ViltColor.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltDate v-if="item.vue === 'ViltDate.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltDateTime v-if="item.vue === 'ViltDateTime.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTime v-if="item.vue === 'ViltTime.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltMedia v-if="item.vue === 'ViltMedia.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRepeater v-if="item.vue === 'ViltRepeater.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSchema v-if="item.vue === 'ViltSchema.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRich v-if="item.vue === 'ViltRich.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRelation v-if="item.vue === 'ViltRelation.vue' && item.edit" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                    </div>
                    <slot name="edit"></slot>
                </form>
            </div>
            <div v-else-if="type.name === 'tabs'">
                <form
                    class="grid grid-cols-1 lg:grid-cols-1"
                    action=""
                    v-if="!edit"
                >
                    <slot name="create-top"></slot>
                    <div
                        v-for="(item, key) in rowFilterCreate"
                        :key="key"
                        class=""
                    >
                        <ViltText v-if="item.vue === 'ViltText.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltEmail v-if="item.vue === 'ViltEmail.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTel v-if="item.vue === 'ViltTel.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTextArea v-if="item.vue === 'ViltTextArea.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltNumber v-if="item.vue === 'ViltNumber.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSelect v-if="item.vue === 'ViltSelect.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSwitch v-if="item.vue === 'ViltSwitch.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltColor v-if="item.vue === 'ViltColor.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltDate v-if="item.vue === 'ViltDate.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltDateTime v-if="item.vue === 'ViltDateTime.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTime v-if="item.vue === 'ViltTime.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltMedia v-if="item.vue === 'ViltMedia.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRepeater v-if="item.vue === 'ViltRepeater.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSchema v-if="item.vue === 'ViltSchema.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRich v-if="item.vue === 'ViltRich.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRelation v-if="item.vue === 'ViltRelation.vue' && item.create && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                    </div>
                    <slot name="create"></slot>
                </form>
                <form class="grid grid-cols-1 md:grid-cols-1" action="" v-else>
                    <slot name="edit-top"></slot>
                    <div
                        v-for="(item, key) in rowFilterEdit"
                        :key="key"
                        class=""
                    >
                        <ViltText v-if="item.vue === 'ViltText.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltEmail v-if="item.vue === 'ViltEmail.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTel v-if="item.vue === 'ViltTel.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTextArea v-if="item.vue === 'ViltTextArea.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltNumber v-if="item.vue === 'ViltNumber.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSelect v-if="item.vue === 'ViltSelect.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSwitch v-if="item.vue === 'ViltSwitch.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltColor v-if="item.vue === 'ViltColor.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltDate v-if="item.vue === 'ViltDate.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltDateTime v-if="item.vue === 'ViltDateTime.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltTime v-if="item.vue === 'ViltTime.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltMedia v-if="item.vue === 'ViltMedia.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRepeater v-if="item.vue === 'ViltRepeater.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltSchema v-if="item.vue === 'ViltSchema.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRich v-if="item.vue === 'ViltRich.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                        <ViltRelation v-if="item.vue === 'ViltRelation.vue' && item.edit && item.tab === null" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                    </div>
                    <slot name="edit"></slot>
                </form>
                <Tabs
                    cache-lifetime="0"
                    :options="{ defaultTabHash: type.tabs[0].key }"
                    @changed="changeTab"
                    ref="tabs"
                    nav-class="flex justify-start space-x-2"
                    nav-item-class="py-2 px-4 rounded-lg"
                    nav-item-active-class="bg-main  py-2 px-4 rounded-lg text-white"
                >
                    <Tab
                        v-for="(tab, key) in type.tabs"
                        :key="key"
                        :name="tab.label"
                        :id="tab.key"
                        :is-disabled="
                            (!tab.create && !edit) || (!tab.edit && edit)
                        "
                    >
                        <form
                            class="grid grid-cols-1 lg:grid-cols-1"
                            action=""
                            v-if="!edit"
                        >
                            <slot name="create-top"></slot>
                            <div
                                v-for="(item, key) in rowFilterCreate"
                                :key="key"
                                class=""
                            >
                                <ViltText v-if="item.vue === 'ViltText.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltEmail v-if="item.vue === 'ViltEmail.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltTel v-if="item.vue === 'ViltTel.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltTextArea v-if="item.vue === 'ViltTextArea.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltNumber v-if="item.vue === 'ViltNumber.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltSelect v-if="item.vue === 'ViltSelect.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltSwitch v-if="item.vue === 'ViltSwitch.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltColor v-if="item.vue === 'ViltColor.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltDate v-if="item.vue === 'ViltDate.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltDateTime v-if="item.vue === 'ViltDateTime.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltTime v-if="item.vue === 'ViltTime.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltMedia v-if="item.vue === 'ViltMedia.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltRepeater v-if="item.vue === 'ViltRepeater.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltSchema v-if="item.vue === 'ViltSchema.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltRich v-if="item.vue === 'ViltRich.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltRelation v-if="item.vue === 'ViltRelation.vue' && item.create && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                            </div>
                            <slot name="create"></slot>
                        </form>
                        <form
                            class="grid grid-cols-1 md:grid-cols-1"
                            action=""
                            v-else
                        >
                            <slot name="edit-top"></slot>
                            <div
                                v-for="(item, key) in rowFilterEdit"
                                :key="key"
                                class=""
                            >
                                <ViltText v-if="item.vue === 'ViltText.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltEmail v-if="item.vue === 'ViltEmail.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltTel v-if="item.vue === 'ViltTel.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltTextArea v-if="item.vue === 'ViltTextArea.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltNumber v-if="item.vue === 'ViltNumber.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltSelect v-if="item.vue === 'ViltSelect.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltSwitch v-if="item.vue === 'ViltSwitch.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltColor v-if="item.vue === 'ViltColor.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltDate v-if="item.vue === 'ViltDate.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltDateTime v-if="item.vue === 'ViltDateTime.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltTime v-if="item.vue === 'ViltTime.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltMedia v-if="item.vue === 'ViltMedia.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltRepeater v-if="item.vue === 'ViltRepeater.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltSchema v-if="item.vue === 'ViltSchema.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltRich v-if="item.vue === 'ViltRich.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                                <ViltRelation v-if="item.vue === 'ViltRelation.vue' && item.edit && item.tab === tab.key" :message="form.errors[item.name]" :row="item" v-model="form[item.name]"/>
                            </div>
                            <slot name="edit"></slot>
                        </form>
                    </Tab>
                </Tabs>
            </div>
        </template>

        <template #footer>
            <slot name="footer-button"></slot>

            <div class="flex justify-start gap-2">
                <JetButton
                    :disabled="isDisabledBtn"
                    v-if="type.name === 'tabs' && !this.lastTab"
                    @click.prevent="nextTab()"
                >
                    {{ trans("global.next") }}
                </JetButton>
                <JetButton
                    :disabled="isDisabledBtn"
                    v-if="!edit && this.lastTab && this.type.name === 'tabs'"
                    @click.prevent="saveRecord()"
                >
                    {{ trans("global.save") }}
                </JetButton>
                <JetButton
                    :disabled="isDisabledBtn"
                    v-else-if="!edit && this.type.name === 'modal'"
                    @click.prevent="saveRecord()"
                >
                    {{ trans("global.save") }}
                </JetButton>
                <JetButton
                    :disabled="isDisabledBtn"
                    v-else-if="edit"
                    @click.prevent="updateRecord(form.id)"
                >
                    {{ trans("global.update") }}
                </JetButton>
                <JetSecondaryButton @click="close">
                    {{ trans("global.close") }}
                </JetSecondaryButton>
            </div>
        </template>
    </JetDialogModal>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import { Tabs, Tab } from "vue3-tabs-component";
import { ref } from "vue";
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


export default defineComponent({
    components: {
        Tabs,
        Tab,
        Link,
        JetDialogModal,
        JetSecondaryButton,
        JetButton,
        JetInput,
        JetInputError,
        JetLabel,
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
    },
    props: {
        show: Boolean,
        edit: Boolean,
        item: Object,
        title: String,
        url: String,
        rows: Array,
        type: {
            String,
            default: "modal",
        },
    },
    computed: {
        lang() {
            return this.$page.props.data.trans;
        },
        rowFilterCreate() {
            let rows = this.$props.rows;
            let rowValue = [];
            for (let i = 0; i < rows.length; i++) {
                if (
                    this.arraysEqual(
                        rows[i].reactive,
                        this.form[rows[i].row]
                    ) &&
                    rows[i].create
                ) {
                    rowValue.push(rows[i]);
                } else if (!rows[i].reactive && rows[i].create) {
                    rowValue.push(rows[i]);
                }
            }

            return rowValue;
        },
        rowFilterEdit() {
            let rows = this.$props.rows;
            let rowValue = [];
            for (let i = 0; i < rows.length; i++) {
                if (
                    this.arraysEqual(
                        rows[i].reactive,
                        this.form[rows[i].row]
                    ) &&
                    rows[i].edit
                ) {
                    rowValue.push(rows[i]);
                } else if (!rows[i].reactive && rows[i].edit) {
                    rowValue.push(rows[i]);
                }
            }

            return rowValue;
        },
    },
    watch: {
        show(newValue, oldValue) {
            this.showModal = newValue;
        },
        item(newValue, oldValue) {
            this.form = newValue;
        },
    },
    data() {
        return {
            isDisabledBtn: false,
            showModal: false,
            form: this.$inertia.form(this.item),
            index: 0,
            lastTab: false,
        };
    },
    mounted() {
        this.$inertia.on("start", () => (this.isDisabledBtn = true));
        this.$inertia.on("finish", () => (this.isDisabledBtn = false));
    },
    methods: {
        changeTab(selectedTab) {
            let countActive = 0;
            for (let i = 0; i < this.type.tabs.length; i++) {
                if (this.type.tabs[i].key === selectedTab.tab.computedId) {
                    this.index = i;
                }
            }
            if (this.index === this.type.tabs.length - 1) {
                this.lastTab = true;
            } else {
                this.lastTab = false;
            }
        },
        nextTab() {
            this.index += 1;
            this.$refs.tabs.selectTab("#" + this.type.tabs[this.index].key);
        },
        trans(key) {
            return this.lang[key] ? this.lang[key] : key;
        },
        close() {
            this.$emit("close", this.showModal);
        },
        success(id = null) {
            if (this.edit) {
                this.$emit("update", { form: thitrues.form, id: id });
                this.isDisabledBtn = false;
            } else {
                this.$emit("create", this.form);
                this.isDisabledBtn = false;
            }
        },
        saveRecord() {
            this.form.submit("post", this.route(this.url + ".store"), {
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.$emit("success");
                    this.isDisabledBtn = false;
                },
                onError: () => {
                    if (this.type.type === "tabs") {
                        for (let r = 0; r < this.rows.length; r++) {
                            if (
                                Object.keys(this.form.errors)[0] ===
                                this.rows[r].field
                            ) {
                                if (this.$refs.tabs) {
                                    this.$refs.tabs.selectTab(
                                        "#" + this.rows[r].tab
                                    );
                                }

                                break;
                            }
                        }
                    }
                    this.$toast.success(this.trans("global.error.message"), {
                        position: "top",
                        style: {
                            background: "#d41717",
                            "border-radius": "0.25rem",
                        },
                    });
                },
            });
        },
        updateRecord(id) {
            this.form.submit("post", this.route(this.url + ".update", id), {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: () => {
                    this.form.reset();
                    this.$emit("success");
                    this.isDisabledBtn = false;
                },
                onError: () => {
                    if (this.type.type === "tabs") {
                        for (let r = 0; r < this.rows.length; r++) {
                            if (
                                Object.keys(this.form.errors)[0] ===
                                this.rows[r].field
                            ) {
                                if (this.$refs.tabs) {
                                    this.$refs.tabs.selectTab(
                                        "#" + this.rows[r].tab
                                    );
                                }

                                break;
                            }
                        }
                    }
                    this.$toast.success(this.trans("global.error.message"), {
                        position: "top",
                        style: {
                            background: "#d41717",
                            "border-radius": "0.25rem",
                        },
                    });
                },
            });
        },
        arraysEqual(a, b) {
            return JSON.stringify(a) == JSON.stringify(b);
        },
    },
});
</script>
