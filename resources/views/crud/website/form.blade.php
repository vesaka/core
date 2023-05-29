<x-admin-app-layout>
    <h4 class="px-4 py-2">Form</h4>
    <div id="image-form"></div>
    <x-slot name="scripts">
        <script>
            var $model = @json($model),
                    $categories = @json($categories);
        </script>
        @vite('packages/vesaka/core/resources/js/crud/image/image-form.js')
    </x-slot>
</x-admin-app-layout>