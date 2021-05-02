<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                Share
                </h3>
                
            </div>
            <div class="border-t border-gray-200">
                <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                    Blening Plant
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $share->plant->name }}, {{ $share->plant->state }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                    Quantity
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $share->quantity }}MT
                    </dd>
                </div>
                
                </dl>
            </div>
            </div>

            <div class="m-5">
                <x-jet-button wire:click="createModal">
                    {{ __('Create New Waybill') }}
                </x-jet-button>
            </div>

            <x-jet-dialog-modal wire:model="modalVisibility">
                @if($modelId)
                <x-slot name="title">
                    {{ __('Edit Waybill') }}
                </x-slot>
                @else
                <x-slot name="title">
                    {{ __('Create Waybill') }}
                </x-slot>
                @endif

                <x-slot name="content">
                    <div class="mt-4">
                        <x-jet-label for="date" value="{{ __('Date') }}" />
                        <x-jet-input id="date" class="block mt-1 w-full" type="date" wire:model.debounce.800ms="date" />
                        @error('date') <span class="text-xs text-red-600">{{ $message }} </span> @enderror
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="quantity" value="{{ __('Quantity (MT)') }}" />
                        <x-jet-input id="quantity" class="block mt-1 w-full" type="number" wire:model.debounce.800ms="quantity" />
                        @error('quantity') <span class="text-xs text-red-600">{{ $message }} </span> @enderror
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="bags" value="{{ __('Number of Bags') }}" />
                        <x-jet-input id="bags" class="block mt-1 w-full" type="number" wire:model.debounce.800ms="bags" />
                        @error('bags') <span class="text-xs text-red-600">{{ $message }} </span> @enderror
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="driver" value="{{ __('Truck Agent') }}" />
                        <x-jet-input id="driver" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="driver" />
                        @error('driver') <span class="text-xs text-red-600">{{ $message }} </span> @enderror
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="phone" value="{{ __('Driver Phone') }}" />
                        <x-jet-input id="phone" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="phone" />
                        @error('phone') <span class="text-xs text-red-600">{{ $message }} </span> @enderror
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="truck" value="{{ __('Truck number') }}" />
                        <x-jet-input id="truck" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="truck" />
                        @error('truck') <span class="text-xs text-red-600">{{ $message }} </span> @enderror
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
                            Waybill#
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Truck Agent/Driver Phone
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Truck Number
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($waybills as $waybill)
                        <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                            
                            <div >
                                <div class="text-sm font-medium text-gray-900">
                                {{ str_pad($waybill->id,6,'0',STR_PAD_LEFT) }}
                                </div>
                                
                            </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                            
                            <div >
                                <div class="text-sm font-medium text-gray-900">
                                {{ ( Carbon\Carbon::parse($waybill->date)->format('d M Y')) }}
                                </div>
                                
                            </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                            
                            <div >
                                <div class="text-sm font-medium text-gray-900">
                                {{ $waybill->driver }}
                                </div>
                                <div class="text-sm text-gray-500">
                                {{ $waybill->phone }}
                                </div>
                            </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                            
                            <div >
                                <div class="text-sm font-medium text-gray-900">
                                {{ $waybill->truck }}
                                </div>
                                
                            </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('waybill', [$waybill->id]) }}" class="text-yellow-400 hover:text-yellow-600">Print</a>
                            <a href="#" class="text-indigo-600 hover:text-indigo-900" wire:click="editModal({{ $waybill->id }})">Edit</a>
                            <a href="#" class="text-red-600 hover:text-red-900" wire:click="deleteConfirm({{ $waybill->id }})">Delete</a>
                        </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap">No waybill found.</td>
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
