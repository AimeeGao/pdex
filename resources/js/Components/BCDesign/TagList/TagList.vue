<template>
  <div :class="`bcds-react-aria-TagList ${orientation}`">
    <Tag
      v-for="item in items"
      :key="item.id || item.textValue"
      v-bind="item"
      @remove="handleTagRemove"
    />
    <div v-if="items.length === 0 && renderEmptyState" class="empty-state">
      <slot name="empty">
        No tags available
      </slot>
    </div>
  </div>
</template>

<script>
import Tag from '../Tag/Tag.vue'

export default {
  name: 'TagList',
  components: {
    Tag
  },
  props: {
    items: {
      type: Array,
      required: true
    },
    orientation: {
      type: String,
      default: 'horizontal',
      validator: (value) => ['horizontal', 'vertical'].includes(value)
    },
    renderEmptyState: {
      type: Boolean,
      default: false
    }
  },
  emits: ['tagRemove'],
  methods: {
    handleTagRemove(tagData) {
      this.$emit('tagRemove', tagData)
    }
  }
}
</script>

<style scoped>
@import './TagList.css';

.empty-state {
  color: #666;
  font-style: italic;
  padding: 8px;
}
</style>