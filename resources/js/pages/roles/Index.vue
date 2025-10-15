<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import LoadingSpinner from '@/components/ui_customs/LoadingSpinner.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, useForm } from '@inertiajs/vue3';
import { Plus, Search } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import { columns } from './table/columns';
import DataTable from './table/DataTable.vue';
import { usePage } from '@inertiajs/vue3';

const isLoading = ref(false);
let debounceTimer: any = null;

const props = defineProps<{
    roles: [];
    pagination: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number;
        to: number;
    };
}>();

const page = usePage();
const urlParams = computed(() => page.url.split('?')[1] || '');
const searchParams = new URLSearchParams(urlParams.value);

const formSearch = useForm({
    keyword: searchParams.get('keyword') || '',
});

onMounted(() => {
    const keywordParam = searchParams.get('keyword');

    if (keywordParam) {
        formSearch.keyword = keywordParam;
    }
});

watch(
    () => formSearch.keyword,
    () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            search();
        }, 500); // đợi 0.5 giây sau khi ngừng gõ
    },
);

function search() {
    isLoading.value = true;

    formSearch.get(route('roles.index'), {
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: '/roles',
    },
];
</script>

<template>
    <Head title="Roles" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="flex items-center justify-between">
                <div class="relative w-full max-w-sm items-center">
                    <Input
                        id="search"
                        type="text"
                        placeholder="Tìm kiếm..."
                        class="pl-10"
                        v-model="formSearch.keyword"
                    />
                    <span
                        class="absolute inset-y-0 start-0 flex items-center justify-center px-2"
                    >
                        <Search class="size-6 text-muted-foreground" />
                    </span>
                </div>
                <Form
                    action="/roles"
                    method="post"
                    :resetOnSuccess="['name']"
                    #default="{ processing }"
                >
                    <div class="flex w-full max-w-sm items-center gap-1.5">
                        <Input
                            id="name"
                            name="name"
                            placeholder="Nhập role"
                        />
                        <Button type="submit" :disabled="processing">
                            <Plus />Thêm
                        </Button>
                    </div>
                </Form>
            </div>
            <div class="relative">
                <Transition name="fade-zoom" mode="out-in">
                    <LoadingSpinner v-if="isLoading" key="loading" />
                    <DataTable
                        v-else
                        :columns="columns"
                        :data="roles"
                        :pagination="{
                            ...pagination,
                            routeName: 'roles.index',
                        }"
                        key="table"
                    />
                </Transition>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-zoom-enter-active,
.fade-zoom-leave-active {
    transition: all 0.1s ease;
}

.fade-zoom-enter-from,
.fade-zoom-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

.fade-zoom-enter-to,
.fade-zoom-leave-from {
    opacity: 1;
    transform: scale(1);
}
</style>
