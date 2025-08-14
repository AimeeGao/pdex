<template>
  <label class="switch" :for="switchId">
    <span v-if="label" class="switch-label">{{ label }}</span>
    <input
      :id="switchId"
      type="checkbox"
      :checked="modelValue"
      :disabled="disabled"
      @change="handleChange"
    />
    <span class="slider round"></span>
  </label>
</template>

<script>
export default {
  name: 'Switch',
  props: {
    /**
     * Whether the switch is checked/on
     */
    modelValue: {
      type: Boolean,
      default: false
    },
    /**
     * Switch label text
     */
    label: {
      type: String,
      default: null
    },
    /**
     * Whether the switch is disabled
     */
    disabled: {
      type: Boolean,
      default: false
    },
    /**
     * Switch ID for accessibility
     */
    switchId: {
      type: String,
      default: null
    }
  },
  emits: ['update:modelValue', 'change'],
  computed: {
    generatedId() {
      return this.switchId || `switch-${this._uid}`
    }
  },
  methods: {
    handleChange(event) {
      const checked = event.target.checked
      this.$emit('update:modelValue', checked)
      this.$emit('change', checked)
    }
  }
}
</script>

<style scoped>
@import './Switch.css';
</style>