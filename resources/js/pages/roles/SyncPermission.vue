<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import LinkButton from '@/components/ui_customs/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { CheckCircle, CircleChevronLeft, CircleX, Save } from 'lucide-vue-next';
import { h } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    role: { id: number; name: string };
    permissions: Record<string, { name: string }[]>;
    rolePermissions: string[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gán Permission',
        href: '#',
    },
];

const form = useForm({
    permissions: [...props.rolePermissions],
});

const isChecked = (name: string) => form.permissions.includes(name);

const toggle = (name: string) => {
    if (isChecked(name)) {
        form.permissions = form.permissions.filter((p) => p !== name);
    } else {
        form.permissions.push(name);
    }
};

const submit = async (id: number) => {
    if (form.permissions.length === 0) {
        showErrorMessage('Vui lòng chọn ít nhất một quyền.');
        return;
    }

    await form.put(route('roles.permissions.update', id), {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            toast('Thông báo', {
                description: 'Gán thành công',
                icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
                position: 'top-center',
            });
        },
        onError: (errors) => {
            toast('Lỗi!', {
                description: 'Có lỗi xảy ra, vui lòng thử lại.',
                icon: h(CircleX, { class: 'text-red-500 w-5 h-5' }),
                position: 'top-center',
            });
            console.error(errors);
        },
    });
};

const formatPermissionName = (name: string) => {
    const parts = name.split('.');
    if (parts.length > 1) {
        const lastPart = parts[1];
        return lastPart.charAt(0).toUpperCase() + lastPart.slice(1);
    }
    return name;
};

const showErrorMessage = (description: string) => {
    toast(h('div', { class: 'text-red-500' }, 'Lỗi!'), {
        description: description,
        icon: h(CircleX, { class: 'text-red-500 w-5 h-5' }),
        position: 'top-center',
    });
};
</script>

<template>
    <Head title="Gán Permission" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div className="flex items-center justify-center">
                <form @submit.prevent="submit(props.role.id)" class="mb-6 w-[90vw] md:w-[80vw] lg:w-1/2">
                    <span class="font-bold">{{ `Role : ${props.role.name}` }}</span>
                    <Card v-for="(group, groupName) in props.permissions" :key="groupName" class="my-3">
                        <CardHeader>
                            <CardTitle class="capitalize">{{ groupName }}</CardTitle>
                        </CardHeader>
                        <CardContent class="grid grid-cols-2 gap-3">
                            <div v-for="permission in group" :key="permission.name" class="flex items-center space-x-2">
                                <Checkbox
                                    :id="permission.name"
                                    :model-value="form.permissions.includes(permission.name)"
                                    @update:model-value="() => toggle(permission.name)"
                                />
                                <Label :for="permission.name">{{ formatPermissionName(permission.name) }}</Label>
                            </div>
                        </CardContent>
                    </Card>
                    <div class="flex justify-between">
                        <LinkButton variant="outline" :href="route('roles.index')" :icon="CircleChevronLeft" title="Quay lại" />
                        <Button type="submit" :disabled="form.processing"><Save />Lưu</Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
