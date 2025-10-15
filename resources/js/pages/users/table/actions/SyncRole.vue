<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import LinkButton from '@/components/ui_customs/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { User } from '@/types/my-types';
import { Head, useForm } from '@inertiajs/vue3';
import { CheckCircle, CircleChevronLeft, Save } from 'lucide-vue-next';
import { h } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sync Role',
        href: '#',
    },
];

const props = defineProps<{
    user: User;
    roles: { id: number; name: string }[];
    userRoles: string[];
}>();

const form = useForm({
    roles: [...props.userRoles],
});

const isChecked = (name: string) => form.roles.includes(name);

const toggle = (name: string) => {
    form.roles = isChecked(name) ? form.roles.filter((r) => r !== name) : [...form.roles, name];
};

const submit = () => {
    form.put(route('users.roles.update', props.user.id), {
        onSuccess: () => {
            toast('Thông báo', {
                description: 'Cập nhật thành công',
                icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
                position: 'top-center',
            });
        },
        onError: () => {
            toast('Lỗi', {
                description: 'Không thể cập nhật roles',
            });
        },
    });
};
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div className="flex items-center justify-center">
                <form @submit.prevent="submit" class="mb-6 w-[90vw] md:w-[80vw] lg:w-1/2">
                    <span class="font-bold">{{ `User : ${props.user.name}` }}</span>
                    <Card class="my-3">
                        <CardHeader>
                            <CardTitle>Danh sách Roles</CardTitle>
                        </CardHeader>
                        <CardContent class="grid grid-cols-2 gap-5">
                            <div v-for="role in props.roles" :key="role.id" class="flex items-center space-x-2">
                                <Checkbox :id="role.name" :model-value="form.roles.includes(role.name)"  @update:model-value="() => toggle(role.name)" />
                                <Label :for="role.name">{{ role.name }}</Label>
                            </div>
                        </CardContent>
                    </Card>
                    <div class="flex justify-between">
                        <LinkButton variant="outline" :href="route('users.index')" :icon="CircleChevronLeft" title="Quay lại" />
                        <Button type="submit" :disabled="form.processing"><Save />Lưu thay đổi</Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
