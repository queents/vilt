<script setup>
import { useMainStore } from "@/Stores/main";
import UserAvatar from "@/Components/UserAvatar.vue";
import { usePage } from '@inertiajs/inertia-vue3'
import {computed} from "vue";


</script>

<template>
  <UserAvatar :username="usePage().props.value.user.name" api="initials" />
</template>
