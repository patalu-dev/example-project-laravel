<script setup lang="ts">
import {
    SidebarGroup,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown, LayoutDashboard, Users } from 'lucide-vue-next';
import Collapsible from './ui/collapsible/Collapsible.vue';
import CollapsibleContent from './ui/collapsible/CollapsibleContent.vue';
import CollapsibleTrigger from './ui/collapsible/CollapsibleTrigger.vue';
import SidebarMenuSub from './ui/sidebar/SidebarMenuSub.vue';
import SidebarMenuSubItem from './ui/sidebar/SidebarMenuSubItem.vue';

const page = usePage();

const items = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutDashboard,
        permission: 'users.read',
    },
    {
        title: 'Admin',
        icon: Users,
        permission: 'users.read',
        children: [
            { title: 'Users', href: '/users', permission: 'users.read', },
            { title: 'Roles', href: '/roles', permission: 'users.read', },
            { title: 'Permissions', href: '/permissions', permission: 'users.read', },
        ],
    },
];

const hasPermission = (perm: string) => {
    return page.props.auth.permissions?.includes(perm);
};

</script>

<template>
    <SidebarGroup class="mt-4 px-2 py-0">
        <SidebarMenu>
            <template v-for="item in items" :key="item.title">
                <template
                    v-if="!item.permission || hasPermission(item.permission)"
                >
                    <!-- Nếu có submenu -->
                    <Collapsible v-if="item.children" class="group/collapsible">
                        <SidebarMenuItem>
                            <CollapsibleTrigger asChild>
                                <SidebarMenuButton
                                    class="flex w-full items-center justify-between gap-2"
                                >
                                    <div class="flex items-center gap-2">
                                        <component
                                            :is="item.icon"
                                            class="h-4 w-4"
                                        />
                                        <span
                                            class="truncate text-sm whitespace-nowrap"
                                            >{{ item.title }}</span
                                        >
                                    </div>

                                    <ChevronDown
                                        class="h-4 w-4 transition-transform duration-200 group-data-[state=open]/collapsible:rotate-180"
                                    />
                                </SidebarMenuButton>
                            </CollapsibleTrigger>

                            <CollapsibleContent>
                                <SidebarMenuSub>
                                    <SidebarMenuSubItem
                                        v-for="sub in item.children.filter(
                                            (sub) =>
                                                !sub.permission ||
                                                hasPermission(sub.permission),
                                        )"
                                        :key="sub.title"
                                    >
                                        <Link
                                            :href="sub.href"
                                            class="block w-full rounded-sm py-1 pl-2 text-sm hover:bg-gray-100"
                                            :class="{
                                                'bg-zinc-100 font-semibold text-primary':
                                                    urlIsActive(
                                                        sub.href,
                                                        page.url,
                                                    ),
                                            }"
                                        >
                                            {{ sub.title }}
                                        </Link>
                                    </SidebarMenuSubItem>
                                </SidebarMenuSub>
                            </CollapsibleContent>
                        </SidebarMenuItem>
                    </Collapsible>

                    <!-- Nếu không có submenu -->
                    <SidebarMenuItem v-else>
                        <SidebarMenuButton asChild>
                            <Link
                                :href="item.href"
                                class="flex items-center gap-2"
                            >
                                <component :is="item.icon" class="h-4 w-4" />
                                {{ item.title }}
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </template>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
