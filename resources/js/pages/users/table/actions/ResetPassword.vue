<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { User } from '@/types/my-types';
import { router } from '@inertiajs/vue3';
import { CheckCircle, RectangleEllipsis } from 'lucide-vue-next';
import { h } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    user: User;
}>();

const handleSubmit = () => {
    router.put(
        route('users.reset.password', props.user.id),
        { id: props.user.id },
        {
            preserveScroll: true,
            onSuccess: () => {
                toast('Thông báo', {
                    description: 'Thay đổi thành công',
                    icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
                    position: 'top-center',
                });
            },
        },
    );
};
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <Button variant="secondary" size="sm" class="h-8 w-8 p-0" @click="handleSubmit()">
                    <RectangleEllipsis />
                </Button>
            </TooltipTrigger>
            <TooltipContent>
                <p>Reset mật khẩu</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
