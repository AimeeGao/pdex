<template>
  <fieldset class="radio-group">
    <legend v-if="legend" class="radio-group-legend">{{ legend }}</legend>
    <div
      v-for="option in options"
      :key="option.value"
      class="radio-item"
    >
      <label class="radio" :for="`radio-${option.value}-${_uid}`">
        {{ option.label }}
        <input
          :id="`radio-${option.value}-${_uid}`"
          type="radio"
          :name="`radio-group-${_uid}`"
          :value="option.value"
          :checked="modelValue === option.value"
          :disabled="option.disabled || disabled"
          @change="handleChange(option.value)"
        />
        <span class="dot"></span>
      </label>
    </div>
  </fieldset>
</template>

<script>
export default {
  name: 'RadioGroup',
  props: {
    /**
     * The selected value
     */
    modelValue: {
      type: [String, Number, Boolean],
      default: null
    },
    /**
     * Array of radio options
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
     * Whether all radio buttons are disabled
     */
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue', 'change'],
  methods: {
    handleChange(value) {
      this.$emit('update:modelValue', value)
      this.$emit('change', value)
    }
  }
}
</script>

<style scoped>
@import './RadioGroup.css';
</style>