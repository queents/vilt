<script setup>
import { mdiForwardburger, mdiBackburger, mdiMenu } from "@mdi/js";
import menuAside from "@/menuAside.js";
import menuNavBar from "@/menuNavBar.js";
import { useMainStore } from "@/Stores/main.js";
import { useLayoutStore } from "@/Stores/layout.js";
import { useStyleStore } from "@/Stores/style.js";
import BaseIcon from "@/Components/BaseIcon.vue";
import FormControl from "@/Components/FormControl.vue";
import NavBar from "@/Components/NavBar.vue";
import NavBarItemPlain from "@/Components/NavBarItemPlain.vue";
import AsideMenu from "@/Components/AsideMenu.vue";
import FooterBar from "@/Components/FooterBar.vue";
import { Inertia } from '@inertiajs/inertia'
import {computed} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

Inertia.on('navigate', () => {
    layoutStore.isAsideMobileExpanded = false
    layoutStore.isAsideLgActive = false
})

const layoutAsidePadding = "xl:pl-60";

const styleStore = useStyleStore();

const layoutStore = useLayoutStore();

const dashboardMenu = computed(()=> usePage().props.value.data.menus.dashboard);


const menuClick = (event, item) => {
    if (item.isToggleLightDark) {
        styleStore.setDarkMode();
    }

    if (item.isLogout) {
        // Add:
        Inertia.post(route('logout'))
    }
}

</script>

<template>
  <div
    :class="{
      dark: styleStore.darkMode,
      'overflow-hidden lg:overflow-visible': layoutStore.isAsideMobileExpanded,
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
