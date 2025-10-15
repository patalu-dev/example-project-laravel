<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import LinkButton from '@/components/ui_customs/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { CheckCircle, CircleChevronLeft, Save } from 'lucide-vue-next';
import { h } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Thêm mới',
        href: '#',
    },
];

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: ''
});

function submit() {
    form.post(route('users.store'), {
        onSuccess: () => {
            form.reset();
            toast('Thông báo', {
                description: 'Thêm thành công',
                icon: h(CheckCircle, { class: 'text-green-500 w-5 h-5' }),
                position: 'top-center',
            });
        },
    });
}
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div className="flex items-center justify-center">
                <Card class="mb-6 w-[90vw] md:w-[80vw] lg:w-1/2">
                    <form @submit.prevent="submit" class="flex flex-col gap-6">
                        <CardHeader>
                            <CardTitle>Tạo mới Users</CardTitle>
                            <CardDescription>Nhập thông tin để tạo mới user</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="mt-1 grid gap-4">
                                <div class="grid gap-2">
                                    <Label for="name">Họ tên</Label>
                                    <Input id="name" v-model="form.name" autocomplete="name" placeholder="Nhập name" :tabindex="1" required />
                                    <InputError :message="form.errors.name" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="username">Username</Label>
                                    <Input id="username" v-model="form.username" autocomplete="username" placeholder="Nhập username" required />
                                    <InputError :message="form.errors.username" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="email">Email</Label>
                                    <Input id="email" v-model="form.email" autocomplete="email" placeholder="Nhập email" />
                                    <InputError :message="form.errors.email" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="password">Mật khẩu</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        required
                                        v-model="form.password"
                                        placeholder="Password"
                                    />
                                    <InputError :message="form.errors.password" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="password_confirmation">Nhập lại mật khẩu</Label>
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        required
                                        v-model="form.password_confirmation"
                                        placeholder="Confirm password"
                                    />
                                    <InputError :message="form.errors.password_confirmation" />
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-between px-6 pt-5">
                            <LinkButton variant="outline" :href="route('users.index')" :icon="CircleChevronLeft" title="Quay lại" />
                            <Button type="submit" :disabled="form.processing"> <Save />Lưu </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
