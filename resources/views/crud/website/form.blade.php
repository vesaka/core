<x-admin-app-layout>
    <div id="website-form"></div>
    <x-slot name="scripts">
        <script>
            var $model = @json($model),
                    $categories = @json($categories);
        </script>
        @vite('packages/vesaka/core/resources/js/crud/website/website-form.js')
    </x-slot>
</x-admin-app-layout>