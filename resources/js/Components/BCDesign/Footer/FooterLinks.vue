<template>
  <figure class="bcds-footer--links">
    <figcaption v-if="title" class="bcds-footer--links-title">
      {{ title }}
    </figcaption>
    <ul v-if="links && links.length">
      <li
        v-for="(link, index) in links"
        :key="`${titleKey}-${index}`"
      >
        <a :href="link.href">{{ link.text }}</a>
      </li>
    </ul>
  </figure>
</template>

<script>
export default {
  name: 'FooterLinks',
  props: {
    title: {
      type: String,
      required: true
    },
    links: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    titleKey() {
      return this.title.toLowerCase().replace(/\s/g, '-')
    }
  }
}
</script>