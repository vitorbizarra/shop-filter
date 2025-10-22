<div class="space-y-4">
    <div>
        <flux:input 
            wire:model.live="search"
            icon="magnifying-glass"
            placeholder="{{ __('Search Products') }}" />
    </div>

    <div>
        <flux:fieldset>
            <flux:legend>
                {{ __('Brands') }}
            </flux:legend>

            @foreach($this->brands as $brand)
                <flux:checkbox
                    wire:key="brand-{{ $brand->id }}"
                    wire:model.live="selectedBrands"
                    :label="$brand->name . ' (' . $brand->products_count . ')'"
                    :value="$brand->id" />
            @endforeach
        </flux:fieldset>

        @if($this->brands->count() >= $loadedBrands)
            <flux:button
                wire:click="loadMoreBrands"
                variant="ghost"
                size="xs"
                class="mt-2">
                {{ __('Load More') }}
            </flux:button>
        @endif
    </div>

    <div>
        <flux:fieldset>
            <flux:legend>
                {{ __('Categories') }}
            </flux:legend>

            @foreach($this->categories as $category)
                <flux:checkbox
                    wire:key="category-{{ $category->id }}"
                    wire:model.live="selectedCategories"
                    :label="$category->name . ' (' . $category->products_count . ')'"
                    :value="$category->id" />
            @endforeach
        </flux:fieldset>

        @if($this->categories->count() >= $loadedCategories)
            <flux:button
                wire:click="loadMoreCategories"
                variant="ghost"
                size="xs"
                class="mt-2">
                {{ __('Load More') }}
            </flux:button>
        @endif
    </div>
</div>
