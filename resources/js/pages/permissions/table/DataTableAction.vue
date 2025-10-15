<script setup lang="ts">
import EditDialog from './actions/Edit.vue'
import DeleteDialog from './actions/Delete.vue'

const props = defineProps<{
  data: any
}>()

</script>

<template>
    <div class="flex gap-2">
        <EditDialog :permission="props.data" />
        <DeleteDialog :id="props.data.id" />
    </div>
</template>
