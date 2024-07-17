<a class="group max-w-max relative flex flex-col justify-center items-center hover:rounded-md hover:border hover:p-0.5 hover:border-gray-500 hover:bg-gray-700 hover:text-white" href="#">
    <p class="">{{$text}}</p>
    <div class="invisible group-hover:visible [transform:perspective(50px)_translateZ(0)_rotateX(10deg)] group-hover:[transform:perspective(0px)_translateZ(0)_rotateX(0deg)] absolute bottom-0 mb-6 origin-bottom transform rounded text-white opacity-0 transition-all duration-300 group-hover:opacity-100">
        <div class="flex max-w-xs flex-col items-center">
            <div class="rounded bg-gray-900 p-2 text-xs text-center shadow-lg">
                <img class="" src="{{$image}}" alt="Imagen de miembro">
            </div>
            <div class="clip-bottom h-2 w-4 bg-gray-900"></div>
        </div>
    </div>
</a>

