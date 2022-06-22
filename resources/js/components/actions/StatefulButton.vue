<template>
  <b-button
    id="stateful-button"
    :type="elementType"
    :variant="variant"
    :class="elementClass"
    :disabled="loading"
    @click="handleInput"
  >
    <b-spinner v-show="loading" small></b-spinner>
    {{ loading ? loadingText : text }}
  </b-button>
</template>

<script>
export default {
  name: "stateful-button",
  props: {
    variant: {
      default: "primary"
    },
    error: { type: Boolean },
    loading: { type: Boolean },
    text: {
      type: String
    },
    loadingText: {
      type: String,
      default: "Loading..."
    },
    elementClass: {
      type: String
    },
    elementType: {
      type: String,
      default: "submit"
    }
  },
  methods: {
    handleInput(value) {
      this.$emit("click", value);
    }
  }
};
</script>
