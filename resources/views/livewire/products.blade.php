<div class="space-y-6">
    @if($this->products->isEmpty())
        <div class="text-center py-12">
            <svg class="w-12 h-12 mx-auto text-zinc-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white mb-2">{{ __('No products found') }}</h2>
            <p class="text-zinc-600 dark:text-zinc-400">{{ __('Try adjusting your filters or search term') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($this->products as $product)
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-300 dark:border-zinc-600 shadow-md hover:shadow-xl p-6 flex flex-col">
                    <div class="aspect-square bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-700 dark:to-zinc-600 rounded-lg mb-4 flex items-center justify-center">
                        <flux:icon name="photo" class="w-16 h-16 text-zinc-400 dark:text-zinc-500" />
                    </div>

                    <flux:heading size="lg" weight="bold">
                        {{ $product->name }}
                    </flux:heading>

                    <flux:text class="text-sm mb-4">
                        {{ $product->brand->name }}
                    </flux:text>

                    @if($product->categories->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($product->categories as $category)
                                <flux:badge size="sm" variant="pill" color="cyan">
                                    {{ $category->name }}
                                </flux:badge>
                            @endforeach
                        </div>
                    @endif

                    @if($product->description)
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 line-clamp-2">
                            {{ $product->description }}
                        </p>
                    @endif

                    <flux:spacer />

                    <div class="flex flex-row gap-2 flex-wrap">
                        <flux:button size="sm" class="flex-1">
                            {{ __('Details') }}
                        </flux:button>

                        <flux:button size="sm" variant="primary" icon="shopping-cart" class="flex-1">
                            {{ __('Add to Cart') }}
                        </flux:button>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $this->products->links() }}
    @endif
</div>
