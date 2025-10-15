<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { User } from '@/types/my-types';
import { useForm } from '@inertiajs/vue3';
import { CheckCircle, LockKeyhole, LockKeyholeOpen } from 'lucide-vue-next';
import { h } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    user: User;
}>();

const form = useForm({
    is_active: props.user.is_active,
});

const handleSubmit = async () => {
    await form.put(route('users.change.status', props.user.id), {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            form.is_active = !form.is_active;
            toast('Thông báo', {
                description: 'Thay đổi thành công',
                icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
                position: 'top-center',
            });
        },
        onError: (errors) => {
            console.error('Failed:', errors);
        },
    });
};
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <Button variant="secondary" size="sm" class="h-8 w-8 p-0" @click="handleSubmit()">
                    <LockKeyhole v-if="form.is_active" />
                    <LockKeyholeOpen v-else />
                </Button>
            </TooltipTrigger>
            <TooltipContent>
                <p>Khóa/mở</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
