<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import DeleteDialog from './actions/Delete.vue';
import EditDialog from './actions/Edit.vue';
import { Puzzle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    data: any;
}>();
</script>

<template>
    <div class="flex gap-2">
        <Link :href="route('roles.permissions.edit', props.data.id)" as="button">
            <Button variant="secondary" size="sm" class="h-8 w-8 p-0">
                <Puzzle />
            </Button>
        </Link>
        <EditDialog :role="props.data" />
        <DeleteDialog :id="props.data.id" />
    </div>
</template>
