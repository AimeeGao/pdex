<template>
  <span :class="`inline-alert inline-alert-${variant}`">
    <slot />
  </span>
</template>

<script>
export default {
  name: 'InlineAlert',
  props: {
    /**
     * Inline alert variant - determines color and styling
     */
    variant: {
      type: String,
      default: 'info',
      validator: (value) => ['error', 'warning', 'info', 'success'].includes(value)
    }
  }
}
</script>

<style scoped>
@import './InlineAlert.css';
</style>