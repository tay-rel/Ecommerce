<div class="mb-2 flex ">
    {{--precio--}}
    <div class="sidebar__block">
        <h3 class="sidebar__title font-bold">PRECIO</h3>
        <div class="block__content">
            <div class="block__price">
                <div id="slide-price" wire:ignore class="my-4">
                    <div>
                        <input type="text" class="w-5/12 text-center rounded-md h-8" id="input-with-keypress-0" type="range" min="0" max="50"
                               wire:model.debounce.1000ms="priceMin">
                        <input type="text" class="w-5/12 text-center rounded-md h-8" id="input-with-keypress-1"
                               wire:model.debounce.1000ms="priceMax">
                    </div>
                </div>
            </div>
            <div class="block__input flex justify-between items-center">
                <div>
                    <x-jet-label value="Precio mínimo" />
                    <div class="flex items-center">
                        <x-jet-input wire:model.debounce.500ms="priceMin" type="range" min="0" max="50" />
                        <span
                            class="px-3 ml-3 bg-sky-600 text-white font-semibold rounded">{{ $priceMin}}</span>
                    </div>
                </div>
                <div>
                    <x-jet-label value="Precio máximo" />
                    <div class="flex items-center">
                        <x-jet-input   wire:model.debounce.500ms="priceMax" type="range" min="51" max="100" />
                        <span class="px-3 ml-3 bg-sky-600 text-white font-semibold rounded">{{  $priceMax }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
