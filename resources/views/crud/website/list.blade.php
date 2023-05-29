<x-admin-app-layout>
    <x-slot name="title">Websites</x-slot>
    <div id="website-list"></div>
    <x-slot name="scripts">
        @vite('packages/vesaka/core/resources/js/crud/website/website-list.js')
    </x-slot>
</x-admin-app-layout>