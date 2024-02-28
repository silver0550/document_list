<div>
    <div class="my-10 max-w-screen-lg mx-auto">
        <div class="card bg-neutral text-neutral-content">
            <div class="flex justify-around">
                <x-input.form-control
                    class="p-3 w-1/4"
                    label="Partner ID">
                    <x-input.number wire:model.blur="partnerId"/>
                </x-input.form-control>
                <x-input.form-control
                    class="p-3 w-1/4"
                    label="Documentum Type">
                    <x-input.select wire:model.live="documentType">
                        @foreach(\App\Enums\DocumentTypesEnum::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->getReadableText() }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.form-control>
                <x-input.form-control
                    class="p-3 w-1/4"
                    label="Minimum Price">
                    <x-input.number wire:model.blur="minPrice"/>
                </x-input.form-control>
        </div>
        </div>
    </div>
    <div class="my-10 max-w-screen-lg mx-auto">
        <div class="card bg-neutral text-neutral-content">
                <x-document-list-table
                    :columns="['Document ID', 'Document Type', 'Partner Name', 'Total']"
                    :data="$documents"
                />
        </div>
    </div>
</div>
