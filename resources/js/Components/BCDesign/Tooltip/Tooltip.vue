<template>
  <div class="bcds-tooltip-wrapper">
    <div
      ref="trigger"
      @mouseenter="showTooltip"
      @mouseleave="hideTooltip"
      @focus="showTooltip"
      @blur="hideTooltip"
    >
      <slot />
    </div>
    <div
      v-if="isVisible"
      ref="tooltip"
      class="bcds-react-aria-Tooltip"
      :style="tooltipStyle"
      role="tooltip"
    >
      <div class="bcds-react-aria-OverlayArrow" :style="arrowStyle">
        <svg
          width="19"
          height="9"
          viewBox="0 0 19 9"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M8.4188 1.41421C9.19985 0.633163 10.4662 0.633165 11.2472 1.41421L18.833 9L0.833008 9L8.4188 1.41421Z"
            fill="currentColor"
          />
        </svg>
      </div>
      <slot name="content">
        {{ content }}
      </slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Tooltip',
  props: {
    content: {
      type: String,
      default: ''
    },
    placement: {
      type: String,
      default: 'top',
      validator: (value) => ['top', 'bottom', 'left', 'right'].includes(value)
    },
    offset: {
      type: Number,
      default: 8
    }
  },
  data() {
    return {
      isVisible: false,
      tooltipStyle: {},
      arrowStyle: {}
    }
  },
  methods: {
    showTooltip() {
      this.isVisible = true
      this.$nextTick(() => {
        this.calculatePosition()
      })
    },
    hideTooltip() {
      this.isVisible = false
    },
    calculatePosition() {
      if (!this.$refs.trigger || !this.$refs.tooltip) return

      const triggerRect = this.$refs.trigger.getBoundingClientRect()
      const tooltipRect = this.$refs.tooltip.getBoundingClientRect()
      const scrollX = window.pageXOffset || document.documentElement.scrollLeft
      const scrollY = window.pageYOffset || document.documentElement.scrollTop

      let top, left, arrowTop, arrowLeft

      switch (this.placement) {
        case 'top':
          top = triggerRect.top + scrollY - tooltipRect.height - this.offset
          left = triggerRect.left + scrollX + (triggerRect.width - tooltipRect.width) / 2
          arrowTop = tooltipRect.height - 1
          arrowLeft = tooltipRect.width / 2 - 9.5
          this.arrowStyle = {
            top: `${arrowTop}px`,
            left: `${arrowLeft}px`,
            transform: 'rotate(0deg)'
          }
          break
        case 'bottom':
          top = triggerRect.bottom + scrollY + this.offset
          left = triggerRect.left + scrollX + (triggerRect.width - tooltipRect.width) / 2
          arrowTop = -8
          arrowLeft = tooltipRect.width / 2 - 9.5
          this.arrowStyle = {
            top: `${arrowTop}px`,
            left: `${arrowLeft}px`,
            transform: 'rotate(180deg)'
          }
          break
        case 'left':
          top = triggerRect.top + scrollY + (triggerRect.height - tooltipRect.height) / 2
          left = triggerRect.left + scrollX - tooltipRect.width - this.offset
          arrowTop = tooltipRect.height / 2 - 4.5
          arrowLeft = tooltipRect.width - 1
          this.arrowStyle = {
            top: `${arrowTop}px`,
            left: `${arrowLeft}px`,
            transform: 'rotate(-90deg)'
          }
          break
        case 'right':
          top = triggerRect.top + scrollY + (triggerRect.height - tooltipRect.height) / 2
          left = triggerRect.right + scrollX + this.offset
          arrowTop = tooltipRect.height / 2 - 4.5
          arrowLeft = -8
          this.arrowStyle = {
            top: `${arrowTop}px`,
            left: `${arrowLeft}px`,
            transform: 'rotate(90deg)'
          }
          break
      }

      this.tooltipStyle = {
        position: 'absolute',
        top: `${top}px`,
        left: `${left}px`,
        zIndex: 1000
      }
    }
  }
}
</script>

<style scoped>
@import './Tooltip.css';

.bcds-tooltip-wrapper {
  display: inline-block;
  position: relative;
}

.bcds-react-aria-Tooltip {
  position: absolute;
  background: #333;
  color: white;
  padding: 8px 12px;
  border-radius: 4px;
  font-size: 14px;
  max-width: 200px;
  word-wrap: break-word;
  z-index: 1000;
}

.bcds-react-aria-OverlayArrow {
  position: absolute;
  width: 19px;
  height: 9px;
}

.bcds-react-aria-OverlayArrow svg {
  fill: #333;
}
</style>