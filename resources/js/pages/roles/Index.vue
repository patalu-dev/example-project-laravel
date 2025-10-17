<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import LoadingSpinner from '@/components/ui_customs/LoadingSpinner.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, useForm, usePage } from '@inertiajs/vue3';
import { Plus, Search } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import { columns } from './table/columns';
import DataTable from './table/DataTable.vue';

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
            <div
                class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between"
            >
                <!-- Ô tìm kiếm -->
                <div class="relative w-full items-center md:max-w-sm">
                    <Input
                        id="search"
                        type="text"
                        placeholder="Tìm kiếm..."
                        class="w-full pl-10"
                        v-model="formSearch.keyword"
                    />
                    <span
                        class="absolute inset-y-0 start-0 flex items-center justify-center px-2"
                    >
                        <Search class="size-6 text-muted-foreground" />
                    </span>
                </div>

                <!-- Form thêm role -->
                <Form
                    action="/roles"
                    method="post"
                    :resetOnSuccess="['name']"
                    #default="{ processing }"
                    class="w-full md:flex md:justify-end"
                >
                    <div class="flex w-full items-center gap-1.5 md:w-[400px]">
                        <Input
                            id="name"
                            name="name"
                            placeholder="Nhập role"
                            class="flex-1"
                        />
                        <Button
                            type="submit"
                            :disabled="processing"
                            class="whitespace-nowrap"
                        >
                            <Plus class="mr-1" /> Thêm
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
