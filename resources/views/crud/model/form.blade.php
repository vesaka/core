<x-admin-app-layout>
    <div id="model-form"></div>
    <x-slot name="scripts">
        <script>
            var $model = @json($model),
                    $categories = @json($categories),
                    type = "{{ $type ?? 'model' }}";
        </script>
        @vite('packages/vesaka/core/resources/js/crud/model/model-form.js')
    </x-slot>
</x-admin-app-layout>