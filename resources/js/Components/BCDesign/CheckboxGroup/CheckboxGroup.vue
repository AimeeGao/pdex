<template>
  <fieldset class="checkbox-group">
    <legend v-if="legend" class="checkbox-group-legend">{{ legend }}</legend>
    <div
      v-for="option in options"
      :key="option.value"
      class="checkbox-item"
    >
      <label class="checkbox" :for="`checkbox-${option.value}-${_uid}`">
        {{ option.label }}
        <input
          :id="`checkbox-${option.value}-${_uid}`"
          type="checkbox"
          :value="option.value"
          :checked="modelValue.includes(option.value)"
          :disabled="option.disabled || disabled"
          @change="handleChange(option.value, $event)"
        />
        <span class="checkmark"></span>
      </label>
    </div>
  </fieldset>
</template>

<script>
export default {
  name: 'CheckboxGroup',
  props: {
    /**
     * The selected values array
     */
    modelValue: {
      type: Array,
      default: () => []
    },
    /**
     * Array of checkbox options
     */
    options: {
      type: Array,
      required: true,
      default: () => []
    },
    /**
     * Legend text for the fieldset
     */
    legend: {
      type: String,
      default: null
    },
    /**
     * Whether all checkboxes are disabled
     */
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue', 'change'],
  methods: {
    handleChange(value, event) {
      const newValue = [...this.modelValue]
      
      if (event.target.checked) {
        if (!newValue.includes(value)) {
          newValue.push(value)
        }
      } else {
        const index = newValue.indexOf(value)
        if (index > -1) {
          newValue.splice(index, 1)
        }
      }
      
      this.$emit('update:modelValue', newValue)
      this.$emit('change', newValue)
    }
  }
}
</script>

<style scoped>
@import './CheckboxGroup.css';
</style>