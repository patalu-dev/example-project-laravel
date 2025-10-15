<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/vue3';
import { CheckCircle, Edit, Save } from 'lucide-vue-next';
import { h, ref } from 'vue';
import { toast } from 'vue-sonner';

const isOpenDialog = ref(false);

const props = defineProps<{
    permission: any;
}>();

const form = useForm({
    name: props.permission.name,
});

const submit = async (id: number) => {
    await form.put(route('permissions.update', id), {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            isOpenDialog.value = false;
            toast('Thông báo', {
                description: 'Cập nhật thành công',
                icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
                position: 'top-center',
            });
        },
    });
};
</script>

<template>
    <div>
        <Button variant="secondary" size="sm" class="h-8 w-8 p-0" @click="isOpenDialog = true">
            <Edit class="h-4 w-4" />
            <span class="sr-only">Sửa</span>
        </Button>
        <Dialog v-model:open="isOpenDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Cập nhật Permisson</DialogTitle>
                    <DialogDescription></DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submit(permission.id)">
                    <div class="flex gap-3">
                        <Input v-model="form.name" />
                        <Button type="submit" :disabled="form.processing"> <Save />Lưu </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
