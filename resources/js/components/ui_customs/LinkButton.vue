<script setup>
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    // URL để chuyển hướng
    href: {
        type: String,
        required: true,
    },
    // Variant của Button (vd: default, secondary, ghost, destructive)
    variant: {
        type: String,
        default: 'default',
    },

    size: {
        type: String,
        default: '',
    },
    // Tên của icon (vd: 'CircleChevronLeft')
    icon: {
        type: [Object, Function], // Chú ý: bạn truyền component icon vào đây
        default: null,
    },
    // Tiêu đề của Button
    title: {
        type: String,
        default: '',
    },
    // Cho phép custom class của Button
    buttonClass: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <Link :href="props.href">
        <Button :size="props.size" :variant="props.variant" :class="props.buttonClass">
            <component v-if="props.icon" :is="props.icon" />
            <span v-if="props.title">{{ props.title }}</span>
        </Button>
    </Link>
</template>
