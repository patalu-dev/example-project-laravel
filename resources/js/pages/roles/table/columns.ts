import { type Permission } from '@/types/my-types';
import { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import TableActionButtons from './DataTableAction.vue';

export const columns: ColumnDef<Permission>[] = [
    {
        accessorKey: 'name',
        header: 'Nam',
    },
    {
        accessorKey: 'guard_name',
        header: 'Guard Name',
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            return h(TableActionButtons, {
                data: row.original,
            });
        },
    },
];
