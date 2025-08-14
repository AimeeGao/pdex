<template>
  <div class="textarea-container">
    <div v-if="label" class="text_label">
      <label :for="textareaId">{{ label }}</label>
    </div>
    <textarea
      :id="textareaId"
      class="text_input"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      :rows="rows"
      :cols="cols"
      @input="handleInput"
      @focus="handleFocus"
      @blur="handleBlur"
    ></textarea>
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
  name: 'TextArea',
  props: {
    /**
     * The textarea value
     */
    modelValue: {
      type: String,
      default: ''
    },
    /**
     * Textarea label text
     */
    label: {
      type: String,
      default: null
    },
    /**
     * Placeholder text
     */
    placeholder: {
      type: String,
      default: null
    },
    /**
     * Whether the textarea is disabled
     */
    disabled: {
      type: Boolean,
      default: false
    },
    /**
     * Whether the textarea is required
     */
    required: {
      type: Boolean,
      default: false
    },
    /**
     * Number of rows
     */
    rows: {
      type: Number,
      default: 8
    },
    /**
     * Number of columns
     */
    cols: {
      type: Number,
      default: 60
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
     * Textarea ID for accessibility
     */
    textareaId: {
      type: String,
      default: null
    }
  },
  emits: ['update:modelValue', 'focus', 'blur'],
  computed: {
    generatedId() {
      return this.textareaId || `textarea-${this._uid}`
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
@import './TextArea.css';
</style>