<template>
    <JetDialogModal :show="showModal" @end="close">
        <template #title>
            <span class="text-main">{{ title }}</span>
            <hr class="my-4" />
        </template>

        <template #content>
            <div class="print_area">
                <div v-for="(row, key) in rows" :key="key">
                    <div v-if="row.view">
                        <ViltText v-if="row.vue === 'ViltText.vue' " :row="row" v-model="item[row.name]" view="view"/>
                        <ViltEmail v-if="row.vue === 'ViltEmail.vue' " :row="row" v-model="item[row.name]" view="view"/>
                        <ViltTel v-if="row.vue === 'ViltTel.vue' " :row="row" v-model="item[row.name]" view="view"/>
                        <ViltTextArea v-if="row.vue === 'ViltTextArea.vue' " :row="row" v-model="item[row.name]" view="view"/>
                        <ViltNumber v-if="row.vue === 'ViltNumber.vue' " :row="row" v-model="item[row.name]" view="view"/>
                        <ViltSelect v-if="row.vue === 'ViltSelect.vue' " :row="row" v-model="item[row.name]" view="view"/>
                        <ViltSwitch v-if="row.vue === 'ViltSwitch.vue' " :row="row" v-model="item[row.name]" view="view"/>
                        <ViltColor v-if="row.vue === 'ViltColor.vue' " :row="row" v-model="item[row.name]" view="view"/>
                        <ViltDate v-if="row.vue === 'ViltDate.vue'"  :row="row" v-model="item[row.name]" view="view"/>
                        <ViltDateTime v-if="row.vue === 'ViltDateTime.vue'" :row="row" v-model="item[row.name]" view="view"/>
                        <ViltTime v-if="row.vue === 'ViltTime.vue'" :row="row" v-model="item[row.name]" view="view"/>
                        <ViltMedia v-if="row.vue === 'ViltMedia.vue'" @popUp="popUp" :row="row" v-model="item[row.name]" view="view"/>
                        <ViltRepeater v-if="row.vue === 'ViltRepeater.vue'" :row="row" v-model="item[row.name]" view="view"/>
                        <ViltSchema v-if="row.vue === 'ViltSchema.vue'" :row="row" v-model="item[row.name]" view="view"/>
                        <ViltRich v-if="row.vue === 'ViltRich.vue'"  :row="row" v-model="item[row.name]" view="view"/>
                        <ViltRelation v-if="row.vue === 'ViltRelation.vue'"  :row="row" v-model="item[row.name]" view="view"/>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <slot></slot>
            <JetSecondaryButton @click="close">
                {{ trans("global.close") }}
            </JetSecondaryButton>
        </template>
    </JetDialogModal>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetButton from "@/Jetstream/Button.vue";
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
        Link,
        JetDialogModal,
        JetSecondaryButton,
        JetButton,
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
        item: Object,
        rows: Array,
        title: String,
    },
    watch: {
        show(newValue, oldValue) {
            this.showModal = newValue;
        },
    },
    computed: {
        lang() {
            return this.$page.props.data.trans;
        },
    },
    data() {
        return {
            showModal: false,
        };
    },
    methods: {
        trans(key) {
            return this.lang[key] ? this.lang[key] : key;
        },
        close() {
            this.$emit("close", this.showModal);
        },
        popUp(images){
            this.$emit('media', images)
        }
    },
});
</script>
