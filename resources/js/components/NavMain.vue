<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
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

const items = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutDashboard,
    },
    {
        title: 'Admin',
        icon: Users,
        children: [
            { title: 'Users', href: '/users' },
            { title: 'Roles', href: '/roles' },
            { title: 'Permissions', href: '/permissions' },
        ],
    },
];

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0 mt-4">
        <SidebarMenu>
            <template v-for="item in items" :key="item.title">
                <!-- Nếu có submenu -->
                <Collapsible
                    v-if="item.children"
                    class="group/collapsible"
                >
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
                                    <span>{{ item.title }}</span>
                                </div>

                                <ChevronDown
                                    class="h-4 w-4 transition-transform duration-200 group-data-[state=open]/collapsible:rotate-180"
                                />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>

                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem
                                    v-for="sub in item.children"
                                    :key="sub.title"
                                >
                                    <Link
                                        :href="sub.href"
                                        class="block w-full text-sm pl-2 py-1 hover:bg-gray-100 rounded-sm"
                                        :class="{
                                            'text-primary': urlIsActive(
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
                        <Link :href="item.href" class="flex items-center gap-2">
                            <component :is="item.icon" class="h-4 w-4" />
                            {{ item.title }}
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
