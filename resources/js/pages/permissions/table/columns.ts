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
        accessorKey: 'roles', 
        header: 'Roles',
        cell: ({ row }) => {
            const roleNames = row.original.roles.map(role => role.name);
            return h('span', roleNames.join(', '));
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
