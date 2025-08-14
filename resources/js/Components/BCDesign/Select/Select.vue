<template>
  <div class="bcds-vue-select">
    <label v-if="label" class="bcds-react-aria-Select--Label">
      {{ label }}
      <span v-if="!required" class="optional">(optional)</span>
    </label>
    <div class="bcds-vue-select--wrapper">
      <button
        ref="selectButton"
        type="button"
        :class="`bcds-react-aria-Select--Button ${size} ${isInvalid ? 'invalid' : ''}`"
        :aria-expanded="isOpen"
        :aria-haspopup="true"
        @click="toggleDropdown"
        @keydown="handleKeydown"
      >
        <span class="bcds-react-aria-SelectValue">
          {{ displayValue }}
        </span>
        <svg
          v-if="!isOpen"
          width="20"
          height="20"
          viewBox="0 0 20 20"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M15.7882 9.15569L10.4858 14.236C10.3192 14.3748 10.1527 14.4303 10.0139 14.4303C9.84731 14.4303 9.68074 14.3748 9.54194 14.2637L4.21182 9.15569C3.9342 8.90584 3.9342 8.46167 4.18405 8.21182C4.4339 7.9342 4.87808 7.9342 5.12793 8.18405L10.0139 12.8479L14.872 8.18405C15.1219 7.9342 15.5661 7.9342 15.8159 8.21182C16.0658 8.46167 16.0658 8.90584 15.7882 9.15569Z"
            fill="currentColor"
          />
        </svg>
        <svg
          v-else
          width="20"
          height="20"
          viewBox="0 0 20 20"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M4.21231 13.3146L9.52695 8.2226C9.6939 8.08348 9.86086 8 10.0278 8C10.1948 8 10.3339 8.05565 10.473 8.16695L15.7876 13.259C16.066 13.5372 16.066 13.9546 15.8155 14.2051C15.5651 14.4833 15.1477 14.4833 14.8694 14.2329L10.0278 9.61387L5.13054 14.2885C4.88011 14.539 4.43491 14.539 4.18448 14.2607C3.93405 13.9825 3.93405 13.5651 4.21231 13.3146Z"
            fill="currentColor"
          />
        </svg>
      </button>
      <div
        v-if="isOpen"
        ref="popover"
        class="bcds-react-aria-Select--Popover"
        :style="{ top: popoverTop + 'px' }"
      >
        <div class="bcds-react-aria-Select--ListBox">
          <template v-if="sections && sections.length">
            <div
              v-for="section in sections"
              :key="section.id"
              class="bcds-react-aria-Section"
            >
              <div v-if="section.header" class="bcds-react-aria-Select--Header">
                {{ section.header }}
              </div>
              <div
                v-for="item in section.items"
                :key="item.id || item.label"
                :class="`bcds-react-aria-Select--ListBoxItem ${item.isDestructive ? 'destructive' : ''} ${selectedValue === (item.id || item.label) ? 'selected' : ''}`"
                @click="selectItem(item)"
                @keydown="handleItemKeydown($event, item)"
                tabindex="0"
              >
                <div v-if="item.iconLeft" class="bcds-react-aria-Select--ListBoxItem-icon">
                  <component :is="item.iconLeft" />
                </div>
                <div class="bcds-react-aria-Select--ListBoxItem-Text-container">
                  <div class="bcds-react-aria-Select--ListBoxItem-Text-label">
                    {{ item.label }}
                  </div>
                  <div
                    v-if="item.description"
                    class="bcds-react-aria-Select--ListBoxItem-Text-description"
                  >
                    {{ item.description }}
                  </div>
                </div>
                <div v-if="item.iconRight" class="bcds-react-aria-Select--ListBoxItem-icon">
                  <component :is="item.iconRight" />
                </div>
              </div>
            </div>
          </template>
          <template v-else>
            <div
              v-for="item in items"
              :key="item.id || item.label"
              :class="`bcds-react-aria-Select--ListBoxItem ${item.isDestructive ? 'destructive' : ''} ${selectedValue === (item.id || item.label) ? 'selected' : ''}`"
              @click="selectItem(item)"
              @keydown="handleItemKeydown($event, item)"
              tabindex="0"
            >
              <div v-if="item.iconLeft" class="bcds-react-aria-Select--ListBoxItem-icon">
                <component :is="item.iconLeft" />
              </div>
              <div class="bcds-react-aria-Select--ListBoxItem-Text-container">
                <div class="bcds-react-aria-Select--ListBoxItem-Text-label">
                  {{ item.label }}
                </div>
                <div
                  v-if="item.description"
                  class="bcds-react-aria-Select--ListBoxItem-Text-description"
                >
                  {{ item.description }}
                </div>
              </div>
              <div v-if="item.iconRight" class="bcds-react-aria-Select--ListBoxItem-icon">
                <component :is="item.iconRight" />
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Select',
  props: {
    items: {
      type: Array,
      default: () => []
    },
    sections: {
      type: Array,
      default: () => []
    },
    label: {
      type: String,
      default: null
    },
    placeholder: {
      type: String,
      default: 'Select an item'
    },
    size: {
      type: String,
      default: 'medium',
      validator: (value) => ['small', 'medium'].includes(value)
    },
    modelValue: {
      type: [String, Number, Object],
      default: null
    },
    required: {
      type: Boolean,
      default: false
    },
    isInvalid: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue', 'change'],
  data() {
    return {
      isOpen: false,
      selectedValue: this.modelValue,
      popoverTop: 0
    }
  },
  computed: {
    displayValue() {
      if (this.selectedValue) {
        const allItems = this.sections && this.sections.length
          ? this.sections.flatMap(section => section.items)
          : this.items

        const selectedItem = allItems.find(item => 
          (item.id || item.label) === this.selectedValue
        )
        return selectedItem ? selectedItem.label : this.placeholder
      }
      return this.placeholder
    }
  },
  watch: {
    modelValue(newValue) {
      this.selectedValue = newValue
    }
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside)
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
  },
  methods: {
    toggleDropdown() {
      this.isOpen = !this.isOpen
      if (this.isOpen) {
        this.$nextTick(() => {
          this.calculatePopoverPosition()
        })
      }
    },
    calculatePopoverPosition() {
      if (this.$refs.selectButton) {
        const rect = this.$refs.selectButton.getBoundingClientRect()
        this.popoverTop = rect.bottom + 4
      }
    },
    selectItem(item) {
      const value = item.id || item.label
      this.selectedValue = value
      this.$emit('update:modelValue', value)
      this.$emit('change', { value, item })
      this.isOpen = false
    },
    handleClickOutside(event) {
      if (this.$el && !this.$el.contains(event.target)) {
        this.isOpen = false
      }
    },
    handleKeydown(event) {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault()
        this.toggleDropdown()
      } else if (event.key === 'Escape') {
        this.isOpen = false
      }
    },
    handleItemKeydown(event, item) {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault()
        this.selectItem(item)
      }
    }
  }
}
</script>

<style scoped>
@import './Select.css';

.bcds-vue-select {
  position: relative;
}

.bcds-vue-select--wrapper {
  position: relative;
}

.bcds-react-aria-Select--Popover {
  position: fixed;
  z-index: 1000;
  background: white;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  min-width: 200px;
}

.bcds-react-aria-Select--ListBoxItem.selected {
  background-color: #f0f8ff;
}

.bcds-react-aria-Select--ListBoxItem:hover {
  background-color: #f5f5f5;
  cursor: pointer;
}
</style>