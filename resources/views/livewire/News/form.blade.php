<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="mt-5 md:mt-0 md:col-span-3">
                    <div class="px-4 sm:px-0 mb-8">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Add News</h3>
                    </div>
                    <form wire:submit.prevent="{{ ($editMode && $selectedId) ? 'update('.$selectedId.')' : 'store'  }}" method="POST">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                <x-jet-validation-errors class="mb-4" />
                                {{--Title--}}
                                <div>
                                    <x-jet-label for="title" value="{{ __('Title') }}" />
                                    <x-jet-input id="title" class="block mt-1 w-full" type="text" wire:model="formData.title" required autofocus />
                                </div>
                                {{--Url--}}
                                <div>
                                    <x-jet-label for="url" value="{{ __('Url') }}" />
                                    <x-jet-input id="url" class="block mt-1 w-full" type="text" wire:model="formData.url" required />
                                </div>
                                {{--Language--}}
                                <div>
                                    <x-jet-label for="language" value="{{ __('Language') }}" />
                                    <x-jet-input id="language" class="block mt-1 w-full" type="text" wire:model="formData.language" required />
                                </div>
                                {{--Summary--}}
                                <div>
                                    <x-jet-label for="summary" value="{{ __('Summary') }}" />
                                    <textarea id="summary" wire:model="formData.summary" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                                </div>
                                {{--Companies--}}
                                <div>
                                    <x-jet-label for="companies" value="{{ __('Companies') }}" />
                                    <select multiple wire:model="formData.companies" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--Image--}}
                                <div>
                                    <x-jet-label for="image" value="{{ __('Image') }}" />
                                    <input type="file" wire:model="formData.image" id="image" class="mt-1">
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <x-jet-danger-button wire:click="$set('editMode', false)">
                                    {{ __('Back') }}
                                </x-jet-danger-button>

                                <x-jet-button type="submit" class="ml-4" wire:loading.attr="disabled">
                                    Save
                                </x-jet-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>

