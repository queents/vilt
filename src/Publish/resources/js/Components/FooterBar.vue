<script setup>
import { containerMaxW } from "@/config.js";
import BaseLevel from "@/Components/BaseLevel.vue";
import JustboilLogo from "@/Components/JustboilLogo.vue";

const year = new Date().getFullYear();
</script>

<template>
  <footer class="py-2 px-6" :class="containerMaxW">
    <BaseLevel>
      <div class="text-center md:text-left">
        <b>
            &copy;{{ year }},
          <a href="https://www.queentechsolutions.net" target="_blank">QTS</a>.
        </b>
        <slot />
      </div>

    </BaseLevel>
  </footer>
</template>
