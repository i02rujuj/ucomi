@props([
    'id',
    'label',
    'entidad',
])

<div class="sm:flex sm:items-center mb-4">
    <div class="sm:w-1/4">
        <label for="{{$id}}" class="block text-sm text-gray-600 mb-1 sm:pl-4 text-left">{{$label}}</label>
    </div>
    <div class="sm:w-3/4">
        <select id="{{$id}}" class="{{$entidad}} text-sm text-gray-600 border bg-blue-50 rounded-md appearance-none border-gray-200 w-full py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
            {{ $slot }}
        </select>
    </div>
</div>