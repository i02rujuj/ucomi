@props([
    'id',
    'label',
    'entidad',
])

<div class="sm:flex sm:justify-center sm:items-center mb-4 gap-x-2">
    <div class="sm:w-2/4">
        <label for="{{$id}}" class="block text-sm text-gray-600 mb-1 sm:pl-4 text-left">{{$label}}</label>
    </div>
    <div class="sm:w-full">
        <select id="{{$id}}" name="{{$id}}" class="{{$entidad}} text-sm text-gray-600 border bg-blue-50 rounded-md border-gray-200 w-full py-2 px-2 leading-tight focus:outline-none focus:border-purple-500">
            {{ $slot }}
        </select>
    </div>
</div>