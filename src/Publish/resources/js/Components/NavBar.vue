<script setup>
import { computed, ref } from "vue";
import {mdiClose, mdiDotsVertical, mdiLogout, mdiThemeLightDark, mdiTranslate} from "@mdi/js";
import { containerMaxW } from "@/config.js";
import BaseIcon from "@/Components/BaseIcon.vue";
import NavBarMenuList from "@/Components/NavBarMenuList.vue";
import NavBarItemPlain from "@/Components/NavBarItemPlain.vue";
import UserAvatarCurrentUser from "@/Components/UserAvatarCurrentUser.vue";
import {useStyleStore} from "@/Stores/style";
import {usePage} from "@inertiajs/inertia-vue3";

defineProps({
  menu: {
    type: Array,
    required: true,
  },
});

const styleStore = useStyleStore();

const menuItem = {
    dark: {
        icon: mdiThemeLightDark,
        label: "Light/Dark",
        isDesktopNoLabel: true,
        isToggleLightDark: true,
    },
    translation: {
        icon: mdiTranslate,
        label: "Change Language",
        isDesktopNoLabel: true,
        isChangeLanguage: true,
    },
    logout: {
        icon: mdiLogout,
        label: "Log out",
        isDesktopNoLabel: true,
        isLogout: true,
    }
}

const emit = defineEmits(["menu-click"]);

const menuClick = (event, item) => {
  emit("menu-click", event, item);
};

const isMenuNavBarActive = ref(false);
</script>

<template>
  <nav
    class="top-0 inset-x-0 fixed bg-gray-50 h-14 z-30 transition-position w-screen lg:w-auto dark:bg-slate-800"
  >
    <div class="flex lg:items-stretch" :class="containerMaxW">
      <div class="flex flex-1 items-stretch h-14">
        <slot />
      </div>
      <div class="flex-none items-stretch flex h-14 lg:hidden">
        <NavBarItemPlain
          @click.prevent="isMenuNavBarActive = !isMenuNavBarActive"
        >
          <BaseIcon
            :path="isMenuNavBarActive ? mdiClose : mdiDotsVertical"
            size="24"
          />
        </NavBarItemPlain>
      </div>
      <div
        class="max-h-screen-menu overflow-y-auto lg:overflow-visible absolute w-screen top-14 left-0 bg-gray-50 shadow-lg lg:w-auto lg:flex lg:static lg:shadow-none dark:bg-slate-800"
        :class="[isMenuNavBarActive ? 'block' : 'hidden']"
      >
        <div class="block lg:flex items-center relative cursor-pointer">
            <UserAvatarCurrentUser class="w-8 h-8 inline-flex" />
        </div>
        <NavBarMenuList :menu="menu" @menu-click="menuClick" />
        <div class="block lg:flex items-center relative cursor-pointer">
                <div
                    @click="menuClick(menuItem, menuItem.dark)"
                    class="flex items-center mx-2"
                >
                    <BaseIcon v-if="menuItem.dark.icon" :path="menuItem.dark.icon" class="transition-colors" />
                    <span
                        class="px-2 transition-colors"
                        :class="{ 'lg:hidden': menuItem.dark.isDesktopNoLabel && menuItem.dark.icon }"
                    >{{ menuItem.dark.label }}</span>
                </div>
                <div
                    @click="menuClick(menuItem, menuItem.translation)"
                    class="flex items-center mx-2"
                >
                    <BaseIcon v-if="menuItem.translation.icon" :path="menuItem.translation.icon" class="transition-colors" />
                    <span
                        class="px-2 transition-colors"
                        :class="{ 'lg:hidden': menuItem.translation.isDesktopNoLabel && menuItem.translation.icon }"
                    >{{ menuItem.translation.label }}</span>
                </div>
                <div
                    @click="menuClick(menuItem, menuItem.logout)"
                    class="flex items-center mx-2"
                >
                    <BaseIcon v-if="menuItem.logout.icon" :path="menuItem.logout.icon" class="transition-colors" />
                    <span
                        class="px-2 transition-colors"
                        :class="{ 'lg:hidden': menuItem.logout.isDesktopNoLabel && menuItem.logout.icon }"
                    >{{ menuItem.logout.label }}</span>
                </div>
            </div>
      </div>
    </div>
  </nav>
</template>
