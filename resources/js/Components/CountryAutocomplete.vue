<template>
  <div class="mb-3">
    <label :for="id" class="form-label">
      {{ label }}
      <span v-if="required" class="text-danger">*</span>
    </label>
    <div class="position-relative">
      <input
        :id="id"
        ref="input"
        type="text"
        :value="modelValue"
        @input="handleInput"
        @focus="showDropdown = true"
        @blur="handleBlur"
        @keydown="handleKeydown"
        class="form-control"
        :class="{ 'is-invalid': error }"
        :placeholder="placeholder"
        autocomplete="off"
      />
      
      <!-- Dropdown -->
      <div 
        v-if="showDropdown && filteredCountries.length > 0"
        class="dropdown-menu show position-absolute w-100"
        style="max-height: 200px; overflow-y: auto; z-index: 1050;"
      >
        <button
          v-for="(country, index) in filteredCountries"
          :key="country.id"
          type="button"
          class="dropdown-item"
          :class="{ active: index === selectedIndex }"
          @mousedown.prevent="selectCountry(country.name)"
        >
          {{ country.name }}
        </button>
        <div v-if="filteredCountries.length === 0" class="dropdown-item-text text-muted">
          No countries found
        </div>
      </div>
    </div>
    
    <div v-if="error" class="invalid-feedback d-block">
      {{ error }}
    </div>
  </div>
</template>

<script>
import { ref, computed, nextTick } from 'vue'

export default {
  name: 'CountryAutocomplete',
  props: {
    id: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    modelValue: {
      type: String,
      default: ''
    },
    countries: {
      type: Array,
      required: true
    },
    placeholder: {
      type: String,
      default: 'Type to search countries...'
    },
    required: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      default: null
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const input = ref(null)
    const showDropdown = ref(false)
    const selectedIndex = ref(-1)
    
    const filteredCountries = computed(() => {
      if (!props.modelValue || props.modelValue.length < 1) {
        return props.countries.slice(0, 10) // Show first 10 countries when no input
      }
      
      return props.countries.filter(country =>
        country.name.toLowerCase().includes(props.modelValue.toLowerCase())
      ).slice(0, 10) // Limit to 10 results
    })
    
    const handleInput = (event) => {
      const value = event.target.value
      emit('update:modelValue', value)
      showDropdown.value = true
      selectedIndex.value = -1
    }
    
    const handleBlur = () => {
      // Delay hiding dropdown to allow for selection
      setTimeout(() => {
        showDropdown.value = false
        selectedIndex.value = -1
      }, 150)
    }
    
    const handleKeydown = (event) => {
      if (!showDropdown.value) return
      
      switch (event.key) {
        case 'ArrowDown':
          event.preventDefault()
          selectedIndex.value = Math.min(selectedIndex.value + 1, filteredCountries.value.length - 1)
          break
          
        case 'ArrowUp':
          event.preventDefault()
          selectedIndex.value = Math.max(selectedIndex.value - 1, -1)
          break
          
        case 'Enter':
          event.preventDefault()
          if (selectedIndex.value >= 0 && filteredCountries.value[selectedIndex.value]) {
            selectCountry(filteredCountries.value[selectedIndex.value].name)
          }
          break
          
        case 'Escape':
          showDropdown.value = false
          selectedIndex.value = -1
          input.value.blur()
          break
      }
    }
    
    const selectCountry = (countryName) => {
      emit('update:modelValue', countryName)
      showDropdown.value = false
      selectedIndex.value = -1
      nextTick(() => {
        input.value.blur()
      })
    }
    
    return {
      input,
      showDropdown,
      selectedIndex,
      filteredCountries,
      handleInput,
      handleBlur,
      handleKeydown,
      selectCountry
    }
  }
}
</script>

<style scoped>
.dropdown-menu {
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.dropdown-item {
  padding: 0.5rem 1rem;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
}

.dropdown-item:hover,
.dropdown-item.active {
  background-color: #e9ecef;
}

.dropdown-item:focus {
  outline: none;
  background-color: #e9ecef;
}
</style>
