<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { CheckCircle, Trash2 } from 'lucide-vue-next'
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { h, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner'

const isOpenDialog = ref(false)

const props = defineProps<{
    id: any
}>()

const handleDelete = async (id: number) => {
    await router.delete(route('permissions.destroy', { id: id }), {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            isOpenDialog.value = false;
            toast('Thông báo', {
                description: 'Đã xóa thành công',
                icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
                position: 'top-center',
            })
        },
        onError: (errors) => {
            console.error('Delete failed:', errors);
        }
    });
}
</script>

<template>
    <div>
        <Button variant="secondary" size="sm" class="h-8 w-8 p-0" @click="isOpenDialog = true">
            <Trash2 class="h-4 w-4" />
            <span class="sr-only">Xóa</span>
        </Button>
        <AlertDialog v-model:open="isOpenDialog">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle class="text-red-600">Cảnh báo?</AlertDialogTitle>
                    <AlertDialogDescription class="text-md text-gray-700 my-3">
                        Bạn muốn xóa Permission này không
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Hủy</AlertDialogCancel>
                    <AlertDialogAction @click="handleDelete(props.id)">Thực hiện</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>