<template>
  <header class="bcds-header">
    <div class="bcds-header--container">
      <!-- Logo Section -->
      <component
        :is="logoLinkElement || 'a'"
        :href="logoLinkElement ? undefined : '/'"
        :title="logoLinkElement ? undefined : 'Government of British Columbia'"
        class="bcds-header--logo"
      >
        <slot name="logo">
          <SvgBcLogo id="bcgov-logo-header" />
        </slot>
      </component>
      
      <ul v-if="skipLinks" class="bcds-header--skiplinks">
        <li v-for="(link, index) in skipLinks" :key="`skiplink-${index}`">
          <component :is="link" />
        </li>
      </ul>
      
      <!-- Title Section -->
      <template v-if="title">
        <div class="bcds-header--line" />
        <component :is="titleElement" class="bcds-header--title">
          {{ title }}
        </component>
      </template>

      <!-- Navigation Links (Desktop) -->
      <nav class="bcds-header--nav desktop-nav" v-if="navLinks && navLinks.length">
        <a 
          v-for="link in navLinks" 
          :key="link.href"
          :href="link.href"
          class="bcds-header--nav-link"
          :class="{ 'active': link.active }"
        >
          {{ link.label }}
        </a>
      </nav>

      <!-- Mobile Menu Button -->
      <button 
        v-if="navLinks && navLinks.length"
        @click="toggleMobileMenu"
        class="bcds-header--mobile-toggle"
        :aria-expanded="showMobileMenu"
        aria-label="Toggle navigation menu"
      >
        <svg class="hamburger-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      
      <!-- User Menu Section -->
      <slot />
    </div>

    <!-- Mobile Navigation Menu -->
    <div v-if="showMobileMenu && navLinks && navLinks.length" class="bcds-header--mobile-nav">
      <nav class="mobile-nav-content">
        <a 
          v-for="link in navLinks" 
          :key="link.href"
          :href="link.href"
          class="bcds-header--mobile-nav-link"
          :class="{ 'active': link.active }"
          @click="closeMobileMenu"
        >
          {{ link.label }}
        </a>
      </nav>
    </div>
  </header>
</template>

<script>
import { ref } from 'vue'
import SvgBcLogo from '../SvgBcLogo/SvgBcLogo.vue'

export default {
  name: 'Header',
  components: {
    SvgBcLogo
  },
  props: {
    /**
     * Link element that surrounds the logo. Use what's appropriate for your
     * router. Defaults to a generic HTML link element.
     */
    logoLinkElement: {
      type: [String, Object],
      default: undefined
    },
    /**
     * Array of link elements that are not visible until they are focused. Used
     * for accessibility for keyboard users, to let them easily skip to main
     * content, navigation, etc.
     */
    skipLinks: {
      type: Array,
      default: undefined
    },
    /**
     * Header title text that appears to the right of the logo.
     */
    title: {
      type: String,
      default: ""
    },
    /**
     * Desired element that renders the `title` string. Defaults to `<span>`.
     */
    titleElement: {
      type: String,
      default: "span",
      validator: (value) => ["h1", "h2", "h3", "h4", "h5", "h6", "span", "p"].includes(value)
    },
    /**
     * Array of navigation links to display in the header
     * Format: [{ label: 'Link Text', href: '/path', active: boolean }]
     */
    navLinks: {
      type: Array,
      default: () => []
    }
  },
  setup() {
    const showMobileMenu = ref(false)

    const toggleMobileMenu = () => {
      showMobileMenu.value = !showMobileMenu.value
    }

    const closeMobileMenu = () => {
      showMobileMenu.value = false
    }

    return {
      showMobileMenu,
      toggleMobileMenu,
      closeMobileMenu
    }
  }
}
</script>

<style scoped>
@import './Header.css';
</style>