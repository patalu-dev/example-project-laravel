<script setup lang="ts" generic="TData, TValue">
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';

import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { router } from '@inertiajs/vue3';
import {
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
} from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    pagination: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number;
        to: number;
        routeName: string;
    };
}>();

// Tạo table với server-side pagination
const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    // Không sử dụng client-side pagination
    manualPagination: true,
    pageCount: props.pagination.last_page,
    state: {
        pagination: {
            pageIndex: props.pagination.current_page - 1,
            pageSize: props.pagination.per_page,
        },
    },
});

// Computed để hiển thị thông tin pagination
const paginationInfo = computed(() => {
    const { current_page, total, from, to } = props.pagination;
    return `${to} / ${total} kết quả`;
});

// Hàm điều hướng đến trang cụ thể
const goToPage = (page: number) => {
    if (page >= 1 && page <= props.pagination.last_page) {
        // Lấy tất cả query parameters hiện tại
        const urlParams = new URLSearchParams(window.location.search);

        // Cập nhật tham số page
        urlParams.set('page', page.toString());

        // Chuyển đổi thành object
        const queryParams = Object.fromEntries(urlParams.entries());

        router.get(route(props.pagination.routeName), queryParams, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        });
    }
};

// Các hàm điều hướng
const goToFirstPage = () => goToPage(1);
const goToPreviousPage = () => goToPage(props.pagination.current_page - 1);
const goToNextPage = () => goToPage(props.pagination.current_page + 1);
const goToLastPage = () => goToPage(props.pagination.last_page);

// Tính toán các trang hiển thị
const visiblePages = computed(() => {
    const { current_page, last_page } = props.pagination;
    const delta = 2; // Số trang hiển thị xung quanh trang hiện tại
    const pages: number[] = [];

    const start = Math.max(1, current_page - delta);
    const end = Math.min(last_page, current_page + delta);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});
</script>

<template>
    <div>
        <!-- Table -->
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <TableHead
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TableRow
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                            :data-state="
                                row.getIsSelected() ? 'selected' : undefined
                            "
                        >
                            <TableCell
                                v-for="cell in row.getVisibleCells()"
                                :key="cell.id"
                            >
                                <div class="flex h-[16px] items-center">
                                    <FlexRender
                                        :render="cell.column.columnDef.cell"
                                        :props="cell.getContext()"
                                    />
                                </div>
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell
                                :colspan="props.columns.length"
                                class="h-24 text-center"
                            >
                                Không có dữ liệu
                            </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center justify-between py-4">
            <!-- Thông tin pagination -->
            <div class="text-sm text-muted-foreground">
                {{ paginationInfo }}
            </div>

            <!-- Navigation buttons -->
            <div class="flex items-center space-x-2">
                <!-- First page -->
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="props.pagination.current_page <= 1"
                    @click="goToFirstPage"
                >
                    <ChevronsLeft class="h-4 w-4" />
                </Button>

                <!-- Previous page -->
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="props.pagination.current_page <= 1"
                    @click="goToPreviousPage"
                >
                    <ChevronLeft class="h-4 w-4" />
                    Trước
                </Button>

                <!-- Page numbers -->
                <div class="flex items-center space-x-1">
                    <Button
                        v-for="page in visiblePages"
                        :key="page"
                        :variant="
                            page === props.pagination.current_page
                                ? 'default'
                                : 'outline'
                        "
                        size="sm"
                        @click="goToPage(page)"
                    >
                        {{ page }}
                    </Button>
                </div>

                <!-- Next page -->
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="
                        props.pagination.current_page >=
                        props.pagination.last_page
                    "
                    @click="goToNextPage"
                >
                    Sau
                    <ChevronRight class="h-4 w-4" />
                </Button>

                <!-- Last page -->
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="
                        props.pagination.current_page >=
                        props.pagination.last_page
                    "
                    @click="goToLastPage"
                >
                    <ChevronsRight class="h-4 w-4" />
                </Button>
            </div>
        </div>
    </div>
</template>
