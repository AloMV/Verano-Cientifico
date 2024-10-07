<script setup>
import { computed } from 'vue';
import { defineComponent } from 'vue'

const props = defineProps({
    value: {
        type: [String, Number],
        default: null,
    },
    height: {
        type: String,
        default: 'h-12',
    },
    text: {
        type: String,
        default: 'text-base',
    }
})

const labelClass = computed(() => {
    const base = [
        "bg-gray-300 text-slate-900 dark:bg-gray-600 dark:text-gray-400 border-gray-500 shadow-none cursor-not-allowed rounded-md px-2 py-2.5 border overflow-auto",
        props.height,
        props.text,
    ];
    return base;
});

</script>

<template>
    <div :class="labelClass">
        {{ value }}
    </div>
</template>