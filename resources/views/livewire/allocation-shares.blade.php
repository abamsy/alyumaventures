<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                Allocation
                </h3>
                
            </div>
            <div class="border-t border-gray-200">
                <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                    Date
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ ( Carbon\Carbon::parse($allocation->date)->format('d M Y')) }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                    Product
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $allocation->product->name }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                    Quantity
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $allocation->quantity }} MT
                    </dd>
                </div>
                </dl>
            </div>
            </div>

            <div class="m-5">
                <x-jet-button wire:click="createModal">
                    {{ __('Create New Share') }}
                </x-jet-button>
            </div>

            <x-jet-dialog-modal wire:model="modalVisibility">
                @if($modelId)
                <x-slot name="title">
                    {{ __('Edit Share') }}
                </x-slot>
                @else
                <x-slot name="title">
                    {{ __('Create Share') }}
                </x-slot>
                @endif

                <x-slot name="content">
                    <div class="mt-4">
                        <x-jet-label for="plant_id" value="{{ __('Blending Plant') }}" />
                        <select id="plant_id" name="plant_id" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" wire:model.debounce.800ms="plant_id">
                            <option value="">--Please choose blending plant--</option>
                            @foreach ($plants as $plant)
                            <option value="{{ $plant->id }}">{{ $plant->name }}</option>
                            @endforeach
                        </select>
                        @error('plant_id') <span class="text-xs text-red-600">{{ $message }} </span> @enderror
                    </div>
                    <div class="mt-4">
                        <x-jet-label for="quantity" value="{{ __('Quantity') }}" />
                        <x-jet-input id="quantity" class="block mt-1 w-full" type="number" wire:model.debounce.800ms="quantity" />
                        @error('quantity') <span class="text-xs text-red-600">{{ $message }} </span> @enderror
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
                            Blending Plant
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($shares as $share)
                        <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                            
                            <div >
                            <div class="text-sm font-medium text-gray-900">
                                {{ $share->plant->name }}
                                </div>
                               {{ $share->plant->state }}
                                </div>
                            </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                            
                            <div >
                                <div class="text-sm font-medium text-gray-900">
                                {{ $share->quantity }}
                                </div>
                                
                            </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($share->quantity == $share->waybills()->sum('quantity'))
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Not completed
                            </span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('swaybills', [$share->id]) }}" class="text-yellow-400 hover:text-yellow-600">View</a>
                            <a href="{{ route('share', [$share->id]) }}" class="text-green-400 hover:text-green-600">Print</a>
                            <a href="#" class="text-indigo-600 hover:text-indigo-900" wire:click="editModal({{ $share->id }})">Edit</a>
                            <a href="#" class="text-red-600 hover:text-red-900" wire:click="deleteConfirm({{ $share->id }})">Delete</a>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap">No share found.</td>
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

