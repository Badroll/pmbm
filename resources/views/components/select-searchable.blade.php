<div>
    <label for="{{ $id }}" class="block text-sm font-semibold text-gray-700 mb-2">
        {{ $label }} <span class="text-red-500">*</span>
    </label>
    <div x-data="{
            open: false,
            search: '',
            selected: null,
            options: [],
            filtered: [],
            init() {
                this.options = JSON.parse(this.$el.dataset.options)
                this.filtered = this.options

                this.$watch('search', value => {
                    this.filtered = value === ''
                        ? this.options
                        : this.options.filter(o => 
                            o.label.toLowerCase().includes(value.toLowerCase())
                        )
                })

                this.$watch('selected', value => {
                    const el = document.getElementById('{{ $id }}')
                    if (el) {
                        el.value = value ? value.value : ''
                        el.dispatchEvent(new Event('change'))
                    }
                })

                this.$watch('selected', value => {
                    const el = document.getElementById('{{ $id }}')
                    if (el) {
                        el.value = value ? value.value : ''
                        el.dispatchEvent(new Event('change'))
                    }
                })

            }
        }"
        data-options='@json($options)'
        data-select-id="{{ $id }}"
        @update-options.window="
            if ($event.detail.target !== '{{ $id }}') return
            options = $event.detail.options
            filtered = options
            selected = null
            search = ''
        "
        class="relative"
        >

        <input type="hidden" id="{{ $id }}" name="{{ $name }}" value="" required>

        <button type="button"
            @click="open = !open; $nextTick(() => { if(open) $refs.searchInput.focus() })"
            class="w-full flex items-center justify-between px-4 py-3 bg-white border rounded-lg transition"
            :class="open ? 'border-blue-500 ring-2 ring-blue-500' : 'border-gray-300'">
            <span :class="selected ? 'text-gray-900' : 'text-gray-400'"
                  x-text="selected ? selected.label : '{{ $placeholder }}'"></span>
            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.outside="open = false"
             class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">

            <div class="p-2 border-b border-gray-100">
                <input type="text"
                       x-model="search"
                       x-ref="searchInput"
                       placeholder="{{ $searchPlaceholder }}"
                       @click.stop
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <ul class="max-h-52 overflow-y-auto py-1">
                <template x-for="option in filtered" :key="option.value">
                    <li @click="selected = option; open = false; search = ''; filtered = options"
                        class="px-4 py-2 text-sm cursor-pointer transition"
                        :class="selected?.value === option.value
                            ? 'bg-blue-50 text-blue-700 font-medium'
                            : 'text-gray-700 hover:bg-gray-50'">
                        <span x-text="option.label"></span>
                    </li>
                </template>

                <li x-show="filtered.length === 0"
                    class="px-4 py-2 text-sm text-gray-400 text-center italic">
                    Tidak ditemukan
                </li>
            </ul>
        </div>
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>