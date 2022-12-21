<x-admin-app-layout>
    <h4 class="px-4 py-2">Form</h4>
    <x-slot name="scripts">
        <script>
            var model = @json($model),
                    categories = @json($categories);
        </script>
    </x-slot>
</x-admin-app-layout>