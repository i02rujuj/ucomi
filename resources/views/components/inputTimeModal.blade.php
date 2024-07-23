@props([
    'label',
    'id',
    'entidad',
])

<div class="sm:flex sm:items-center mb-4 text-left">
    <div class="sm:w-2/4">
        <label for="{{$id}}" class="text-sm text-gray-600 mb-1 sm:pl-4 text-left">{{$label}}</label>
    </div>
    <div class="sm:w-full">
        <input type="time" id="{{$id}}" name="{{$id}}" class="{{$entidad}} w-full text-center text-sm text-gray-600 border bg-blue-50 rounded-md appearance-none border-gray-200 py-2 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
    </div>
</div>