<template>
  <div class="text-field-container">
    <div v-if="label" class="text_label">
      <label :for="inputId">{{ label }}</label>
    </div>
    <input
      :id="inputId"
      class="text_input"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      @input="handleInput"
      @focus="handleFocus"
      @blur="handleBlur"
    />
    <div v-if="errorMessage" class="error-message">
      {{ errorMessage }}
    </div>
    <div v-if="helperText" class="helper-text">
      {{ helperText }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'TextField',
  props: {
    /**
     * The input value
     */
    modelValue: {
      type: String,
      default: ''
    },
    /**
     * Input label text
     */
    label: {
      type: String,
      default: null
    },
    /**
     * Input type
     */
    type: {
      type: String,
      default: 'text',
      validator: (value) => ['text', 'email', 'password', 'number', 'tel', 'url'].includes(value)
    },
    /**
     * Placeholder text
     */
    placeholder: {
      type: String,
      default: null
    },
    /**
     * Whether the input is disabled
     */
    disabled: {
      type: Boolean,
      default: false
    },
    /**
     * Whether the input is required
     */
    required: {
      type: Boolean,
      default: false
    },
    /**
     * Error message to display
     */
    errorMessage: {
      type: String,
      default: null
    },
    /**
     * Helper text to display
     */
    helperText: {
      type: String,
      default: null
    },
    /**
     * Input ID for accessibility
     */
    inputId: {
      type: String,
      default: null
    }
  },
  emits: ['update:modelValue', 'focus', 'blur'],
  computed: {
    generatedId() {
      return this.inputId || `text-field-${this._uid}`
    }
  },
  methods: {
    handleInput(event) {
      this.$emit('update:modelValue', event.target.value)
    },
    handleFocus(event) {
      this.$emit('focus', event)
    },
    handleBlur(event) {
      this.$emit('blur', event)
    }
  }
}
</script>

<style scoped>
@import './TextField.css';
</style>