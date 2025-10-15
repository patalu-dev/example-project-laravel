import { type User } from '@/types/my-types';
import { ColumnDef } from '@tanstack/vue-table';
import { CheckCircle2, XCircle } from 'lucide-vue-next';
import { h } from 'vue';
import TableActionButtons from './DataTableAction.vue';

export const columns: ColumnDef<User>[] = [
    {
        accessorKey: 'name',
        header: 'Name',
    },
    {
        accessorKey: 'username',
        header: 'Username',
    },
    {
        accessorKey: 'email',
        header: 'Email',
    },
    {
        accessorKey: 'is_active',
        header: 'Trạng thái',
        cell: ({ row }) => {
            const isActive = !!row.original.is_active;

            if (isActive) {
                return h(CheckCircle2, { class: 'text-green-500 w-5 h-5' });
            } else {
                return h(XCircle, { class: 'text-red-500 w-5 h-5' });
            }
        },
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
