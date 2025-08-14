<template>
  <div
    :class="`bcds-react-aria-Tag ${color} ${isDisabled ? 'disabled' : ''}`"
    :id="id"
  >
    <div class="bcds-react-aria-Tag--contents">
      <component v-if="icon" :is="icon" />
      {{ textValue }}
      <button
        v-if="!isDisabled && allowsRemoving"
        type="button"
        :aria-label="`Remove ${textValue}`"
        class="bcds-react-aria-Tag--remove-button"
        @click="handleRemove"
      >
        <svg
          width="14"
          height="15"
          viewBox="0 0 14 15"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g>
            <path
              d="M12.6004 3.03239L11.4684 1.90039L7.00039 6.36839L2.53239 1.90039L1.40039 3.03239L5.86839 7.50039L1.40039 11.9684L2.53239 13.1004L7.00039 8.63239L11.4684 13.1004L12.6004 11.9684L8.13239 7.50039L12.6004 3.03239Z"
              fill="currentColor"
            />
          </g>
        </svg>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Tag',
  props: {
    id: {
      type: String,
      default: null
    },
    textValue: {
      type: String,
      required: true
    },
    icon: {
      type: [String, Object],
      default: null
    },
    color: {
      type: String,
      default: 'blue',
      validator: (value) => [
        'bc-blue',
        'bc-gold',
        'blue',
        'dark',
        'gray',
        'grey',
        'green',
        'red',
        'yellow'
      ].includes(value)
    },
    allowsRemoving: {
      type: Boolean,
      default: false
    },
    isDisabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['remove'],
  methods: {
    handleRemove() {
      this.$emit('remove', { id: this.id, textValue: this.textValue })
    }
  }
}
</script>

<style scoped>
@import './Tag.css';

.bcds-react-aria-Tag--remove-button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 2px;
  margin-left: 4px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.bcds-react-aria-Tag--remove-button:hover {
  opacity: 0.7;
}

.bcds-react-aria-Tag.disabled {
  opacity: 0.6;
  pointer-events: none;
}
</style>