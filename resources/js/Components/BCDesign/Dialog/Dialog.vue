<template>
  <Teleport to="body">
    <div
      v-if="isOpen"
      class="dialog-overlay"
      @click="handleOverlayClick"
    >
      <div
        class="dialog"
        :class="{ 'dialog-large': size === 'large', 'dialog-small': size === 'small' }"
        role="dialog"
        :aria-labelledby="titleId"
        :aria-describedby="descId"
        @click.stop
      >
        <div class="dialog-header">
          <h2 v-if="title" :id="titleId" class="dialog-title">{{ title }}</h2>
          <button
            v-if="showCloseButton"
            class="dialog-close"
            aria-label="Close dialog"
            @click="handleClose"
          >
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M18 6L6 18M6 6L18 18"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </button>
        </div>
        <div :id="descId" class="dialog-content">
          <slot />
        </div>
        <div v-if="$slots.footer" class="dialog-footer">
          <slot name="footer" />
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script>
export default {
  name: 'Dialog',
  props: {
    /**
     * Whether the dialog is open
     */
    isOpen: {
      type: Boolean,
      default: false
    },
    /**
     * Dialog title
     */
    title: {
      type: String,
      default: null
    },
    /**
     * Dialog size
     */
    size: {
      type: String,
      default: 'medium',
      validator: (value) => ['small', 'medium', 'large'].includes(value)
    },
    /**
     * Whether to show the close button
     */
    showCloseButton: {
      type: Boolean,
      default: true
    },
    /**
     * Whether clicking the overlay closes the dialog
     */
    closeOnOverlayClick: {
      type: Boolean,
      default: true
    },
    /**
     * Title ID for accessibility
     */
    titleId: {
      type: String,
      default: null
    },
    /**
     * Description ID for accessibility
     */
    descId: {
      type: String,
      default: null
    }
  },
  emits: ['close'],
  computed: {
    generatedTitleId() {
      return this.titleId || `dialog-title-${this._uid}`
    },
    generatedDescId() {
      return this.descId || `dialog-desc-${this._uid}`
    }
  },
  watch: {
    isOpen(newValue) {
      if (newValue) {
        document.body.style.overflow = 'hidden'
        this.$nextTick(() => {
          const dialog = this.$el.querySelector('.dialog')
          if (dialog) dialog.focus()
        })
      } else {
        document.body.style.overflow = ''
      }
    }
  },
  beforeUnmount() {
    document.body.style.overflow = ''
  },
  methods: {
    handleClose() {
      this.$emit('close')
    },
    handleOverlayClick() {
      if (this.closeOnOverlayClick) {
        this.handleClose()
      }
    }
  }
}
</script>

<style scoped>
@import './Dialog.css';
</style>