
<ul id="aside" class="accordion flex-grow w-1/4 md:w-1/6 font-light bg-gray-900 text-white">
    @foreach($items as $name => $item)
        <x-admin-aside.item :item="$item" :id="Str::random(6)"/>
    @endforeach
</ul>
