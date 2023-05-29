<x-admin-app-layout>
    <?php if(!isset($type)) {
        $type = \Str::of(\Route::currentRouteName())->between('admin::', '.index');
    } ?>
    <div id="table"></div>
    <x-slot name="scripts">
        <script>
            var type = '<?= $type ?>';
        </script>
        @vite("packages/vesaka/core/resources/js/crud/model/model-list.js")
    </x-slot>
</x-admin-app-layout>