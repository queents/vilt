<script setup>
import AsideMenuItem from "@/Components/AsideMenuItem.vue";
import {computed} from "vue";

const emit = defineEmits(["menu-click"]);

const menuClick = (event, item) => {
  emit("menu-click", event, item);
};

</script>

<template>
  <ul>
      <VueDraggableNext class="dragArea list-group w-full" v-model="orderingMenu" @change="log">
          <AsideMenuItem
              v-for="(item, index) in orderingMenu"
              :key="index"
              :item="item"
              :is-dropdown-list="isDropdownList"
              @menu-click="menuClick"
          />
      </VueDraggableNext>

  </ul>
</template>

<script>
import {VueDraggableNext} from 'vue-draggable-next'

export default {
    components: {
        VueDraggableNext
    },
    props: {
        isDropdownList: Boolean,
        menu: {
            type: Array,
            required: true,
        },
    },
    data(){
        return {
            orderingMenu: []
        }
    },
    computed: {
        finalMenu(){
            {
                let realMenu = [];
                let orderingMenu = [];
                let menu = this.menu;
                for(let i=0; i<Object.keys(menu).length; i++){
                    if(Object.keys(menu)[i] === 'main'){
                        if(menu[Object.keys(menu)[i]].menu.length){
                            for(let r=0; r<menu[Object.keys(menu)[i]].menu.length; r++){
                                if(!localStorage.getItem('orderingMenu')) {
                                    orderingMenu.push(menu[Object.keys(menu)[i]].menu[r].label);
                                    menu[Object.keys(menu)[i]].menu[r].ordering = i;
                                }
                                else {
                                    menu[Object.keys(menu)[i]].menu[r].ordering = JSON.parse(localStorage.getItem('orderingMenu')).indexOf(menu[Object.keys(menu)[i]].menu[r].label);
                                }
                                realMenu.push(menu[Object.keys(menu)[i]].menu[r]);
                            }
                        }
                    }
                    else {
                        if(!localStorage.getItem('orderingMenu')) {
                            orderingMenu.push(menu[Object.keys(menu)[i]].label);
                            menu[Object.keys(menu)[i]].ordering = i;
                        }
                        else {
                            menu[Object.keys(menu)[i]].ordering = JSON.parse(localStorage.getItem('orderingMenu')).indexOf(menu[Object.keys(menu)[i]].label);
                        }

                        realMenu.push(menu[Object.keys(menu)[i]]);
                    }
                }

                if(!localStorage.getItem('orderingMenu')){
                    localStorage.setItem('orderingMenu', JSON.stringify(orderingMenu));
                }
                return realMenu.sort((a, b) => a.ordering - b.ordering);
            }
        }
    },
    methods: {
        log(log){
            let ordering = JSON.parse(localStorage.getItem('orderingMenu'));
            let oldValue = ordering[log.moved.oldIndex];
            let newValue = ordering[log.moved.newIndex];
            ordering[log.moved.newIndex] = oldValue;
            ordering[log.moved.oldIndex] = newValue;

            localStorage.setItem('orderingMenu', JSON.stringify(ordering));
        }
    },
    mounted() {
        this.orderingMenu = this.finalMenu;
    }
}
</script>
