<script setup lang="ts">
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import LinkButton from '@/components/ui_customs/LinkButton.vue';
import { Edit, UserPen } from 'lucide-vue-next';
import ResetPassword from './actions/ResetPassword.vue';
import ChangeStatus from './actions/ChangeStatus.vue';
import DeleteDialog from './actions/Delete.vue';

const props = defineProps<{
    data: any;
}>();
</script>

<template>
    <div class="flex gap-2">
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <LinkButton variant="secondary" :href="route('users.roles.edit', data)" :icon="UserPen" size="sm" />
                </TooltipTrigger>
                <TooltipContent>
                    <p>Sync Role</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
        <ResetPassword :user="data" />
        <ChangeStatus :user="data" />
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <LinkButton variant="secondary" :href="route('users.edit', data)" :icon="Edit" size="sm" />
                </TooltipTrigger>
                <TooltipContent>
                    <p>Cập nhật</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <DeleteDialog :id="props.data.id" />
                </TooltipTrigger>
                <TooltipContent>
                    <p>Xóa</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
    </div>
</template>
