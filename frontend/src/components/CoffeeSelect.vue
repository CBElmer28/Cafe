<template>
  <div class="relative w-full" ref="selectRef">
    <!-- Trigger Button -->
    <button
      type="button"
      @click="toggleDropdown"
      class="w-full flex justify-between items-center p-3 bg-coffee-100/50 dark:bg-coffee-900/50 border border-coffee-200/10 dark:border-coffee-800/10 rounded-xl text-coffee-900 dark:text-coffee-100 outline-none text-left focus:border-caramel-500 focus:ring-4 focus:ring-caramel-500/15 transition-all duration-300 select-none cursor-pointer"
    >
      <span class="text-sm font-semibold">{{ selectedLabel }}</span>
      <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 text-coffee-400" :class="{ 'rotate-180 text-caramel-500': isOpen }"></i>
    </button>

    <!-- Options Dropdown List -->
    <transition name="dropdown">
      <ul
        v-if="isOpen"
        class="absolute left-0 right-0 mt-2 z-50 max-h-60 overflow-y-auto rounded-xl bg-white dark:bg-coffee-900 shadow-premium-lg border border-coffee-200/20 dark:border-coffee-800/30 py-1 focus:outline-none"
      >
        <li
          v-for="option in options"
          :key="option.value"
          @click="selectOption(option)"
          class="px-4 py-2.5 text-sm cursor-pointer transition-colors duration-200 select-none flex items-center justify-between"
          :class="[
            option.value === modelValue
              ? 'bg-caramel-500/10 text-caramel-600 font-bold dark:bg-caramel-500/20 dark:text-caramel-400'
              : 'text-coffee-800 dark:text-coffee-200 hover:bg-coffee-100 dark:hover:bg-coffee-800 hover:text-caramel-600 dark:hover:text-caramel-400'
          ]"
        >
          <span>{{ option.label }}</span>
          <i v-if="option.value === modelValue" class="fa-solid fa-check text-xs text-caramel-500"></i>
        </li>
      </ul>
    </transition>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue';

export default {
  props: {
    modelValue: {
      type: [String, Number],
      required: true,
    },
    options: {
      type: Array,
      required: true,
    },
    placeholder: {
      type: String,
      default: 'Selecciona una opción',
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const isOpen = ref(false);
    const selectRef = ref(null);

    const toggleDropdown = () => {
      isOpen.value = !isOpen.value;
    };

    const selectOption = (option) => {
      emit('update:modelValue', option.value);
      isOpen.value = false;
    };

    const selectedLabel = computed(() => {
      const selected = props.options.find(opt => opt.value === props.modelValue);
      return selected ? selected.label : props.placeholder;
    });

    const handleClickOutside = (e) => {
      if (selectRef.value && !selectRef.value.contains(e.target)) {
        isOpen.value = false;
      }
    };

    onMounted(() => {
      document.addEventListener('click', handleClickOutside);
    });

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside);
    });

    return {
      isOpen,
      selectRef,
      selectedLabel,
      toggleDropdown,
      selectOption,
    };
  }
};
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
  transition: opacity 0.2s ease, transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
