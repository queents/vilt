<script setup>
import { useLayoutStore } from "@/Stores/layout.js";
import AsideMenuLayer from "@@/Components/AsideMenuLayer.vue";
import OverlayLayer from "@/Components/OverlayLayer.vue";

defineProps({
  menu: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["menu-click"]);

const menuClick = (event, item) => {
  emit("menu-click", event, item);
};

const layoutStore = useLayoutStore();

</script>

<template>
  <AsideMenuLayer
    :menu="menu"
    :class="[
      layoutStore.isAsideMobileExpanded ? 'left-0 rtl:right-0' : '-left-60 lg:left-0 rtl:-right-60 rtl:lg:right-0',
      { 'lg:hidden xl:flex': !layoutStore.isAsideLgActive },
    ]"
    @menu-click="menuClick"
  />
  <OverlayLayer
    v-show="layoutStore.isAsideLgActive"
    z-index="z-30"
    @overlay-click="layoutStore.isAsideLgActive = false"
  />
</template>
