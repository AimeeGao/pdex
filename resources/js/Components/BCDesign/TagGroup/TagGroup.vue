<template>
  <div class="bcds-react-aria-TagGroup">
    <label v-if="label" class="bcds-react-aria-TagGroup--Label">
      {{ label }}
    </label>
    <slot />
    <div
      v-if="description"
      class="bcds-react-aria-TagGroup--Text-description"
    >
      {{ description }}
    </div>
    <div
      v-if="errorMessage"
      class="bcds-react-aria-TagGroup--Text-error"
    >
      {{ errorMessage }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'TagGroup',
  props: {
    label: {
      type: String,
      default: null
    },
    description: {
      type: String,
      default: null
    },
    errorMessage: {
      type: String,
      default: null
    }
  }
}
</script>

<style scoped>
@import './TagGroup.css';
</style>