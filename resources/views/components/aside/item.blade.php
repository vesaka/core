@php 
$children = isset($item['children']) ? $item['children'] : [];
$hasChildren = !empty($children);
$params = isset($item['route-params']) ? $item['route-params'] : [];
$anchor = isset($item['anchor']) ? '/#' . $item['anchor'] : '';
@endphp
<li class="accordion-item w-full cursor-pointer" {{ $hasChildren ? 'v-collapsable data-expanded="yes" data-role="toggle"' : ''}}>
    <a href="{{ !$hasChildren ? route($item['route'], $params) . $anchor : 'javascript:void(0)' }}"
       class="w-full flex flex-wrap  py-2 {{ $hasChildren ? 'pb-4 px-6' : 'px-6 hover:bg-gray-600' }}">
        <div class="accordion-header flex-grow">
            <i class="icon ion-ios-{{ $item['icon'] }}"></i>
            {{ trans($item['label']) }}
        </div>
        <span class="{{ $hasChildren ? 'transform transition-all self-end float-right' : 'hidden' }}" data-role="icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
        </span>
    </a>
    @if($hasChildren)
    <ul id="{{ $id }}" class="bg-gray-700 overflow-y-hidden pl-6 h-0 transition-all ease-in-out duration-500" data-role="content">
        @foreach($children as $_name => $_item)
        <x-admin-aside.item :item="$_item" :name="$_name"/>
        @endforeach
    </ul>
    @endif
</li>