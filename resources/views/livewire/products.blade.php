<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            
            <div class="m-5">
                <x-jet-button wire:click="createModal">
                    {{ __('Create New Product') }}
                </x-jet-button>
            </div>

            <x-jet-dialog-modal wire:model="modalVisibility">
                @if($modelId)
                <x-slot name="title">
                    {{ __('Edit Product') }}
                </x-slot>
                @else
                <x-slot name="title">
                    {{ __('Create Product') }}
                </x-slot>
                @endif

                <x-slot name="content">
                <div class="mt-4">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <x-jet-input id="name" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="name" />
                    @error('name') <span class="text-xs text-red-600">{{ $message }} </span> @enderror
                </div>
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('modalVisibility')" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-jet-secondary-button>

                    @if($modelId)
                    <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-jet-button>
                    @else
                    <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                        {{ __('Create') }}
                    </x-jet-button>
                    @endif
                </x-slot>
            </x-jet-dialog-modal>

            <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($products as $product)
                        <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                            
                            <div >
                                <div class="text-sm font-medium text-gray-900">
                                {{ $product->name }}
                                </div>
                                
                            </div>
                            </div>
                        
                        
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900" wire:click="editModal({{ $product->id }})">Edit</a>
                            <a href="#" class="text-red-600 hover:text-red-900" wire:click="deleteConfirm({{ $product->id }})">Delete</a>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap">No product found.</td>
                        </tr>
                        @endforelse 
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
            </div> 

        </div>
    </div>
</div>

