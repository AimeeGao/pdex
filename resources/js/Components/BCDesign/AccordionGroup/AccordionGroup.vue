<template>
  <div class="accordion-group">
    <div
      v-for="(item, index) in items"
      :key="index"
      class="accordion-item"
    >
      <button
        class="accordion-header"
        :aria-expanded="openItems.includes(index)"
        :aria-controls="`accordion-content-${index}-${_uid}`"
        @click="toggleItem(index)"
      >
        <span>{{ item.title }}</span>
        <svg
          class="accordion-icon"
          :class="{ 'rotated': openItems.includes(index) }"
          width="16"
          height="16"
          viewBox="0 0 16 16"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M8 4L12 8L8 12L4 8L8 4Z"
            fill="currentColor"
          />
        </svg>
      </button>
      <div
        v-show="openItems.includes(index)"
        :id="`accordion-content-${index}-${_uid}`"
        class="accordion-content"
        role="region"
        :aria-labelledby="`accordion-header-${index}-${_uid}`"
      >
        <div class="accordion-content-inner">
          <slot :name="`content-${index}`" :item="item">
            {{ item.content }}
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AccordionGroup',
  props: {
    /**
     * Array of accordion items with title and content
     */
    items: {
      type: Array,
      required: true,
      default: () => []
    },
    /**
     * Whether multiple items can be open at once
     */
    allowMultiple: {
      type: Boolean,
      default: true
    },
    /**
     * Initially open items (array of indices)
     */
    defaultOpen: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      openItems: [...this.defaultOpen]
    }
  },
  emits: ['toggle'],
  methods: {
    toggleItem(index) {
      if (this.openItems.includes(index)) {
        // Close the item
        this.openItems = this.openItems.filter(i => i !== index)
      } else {
        // Open the item
        if (this.allowMultiple) {
          this.openItems.push(index)
        } else {
          this.openItems = [index]
        }
      }
      this.$emit('toggle', { index, isOpen: this.openItems.includes(index), openItems: [...this.openItems] })
    }
  }
}
</script>

<style scoped>
@import './AccordionGroup.css';
</style>