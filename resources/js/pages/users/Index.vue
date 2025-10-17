<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus, Search } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
// import SearchCard from './components/SearchCard.vue';
import LoadingSpinner from '@/components/ui_customs/LoadingSpinner.vue';
import { usePage } from '@inertiajs/vue3';
import { columns } from './table/columns';
import DataTable from './table/DataTable.vue';

const isLoading = ref(false);
let debounceTimer: any = null;

const props = defineProps<{
    users: [];
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
    (newVal) => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            search();
        }, 500); // đợi 0.5 giây sau khi ngừng gõ
    },
);

function search() {
    isLoading.value = true;

    formSearch.get(route('users.index'), {
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];
</script>

<template>
    <Head title="User" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <!-- <SearchCard :customers="user" @search="search" /> -->
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

                <!-- Nút thêm mới -->
                <Link :href="route('users.create')" class="w-full md:w-auto">
                    <Button class="w-full md:w-auto">
                        <Plus class="mr-2" /> Thêm mới
                    </Button>
                </Link>
            </div>

            <div class="relative">
                <Transition name="fade-zoom" mode="out-in">
                    <LoadingSpinner v-if="isLoading" key="loading" />
                    <DataTable
                        v-else
                        :columns="columns"
                        :data="users"
                        :pagination="{
                            ...pagination,
                            routeName: 'users.index',
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
