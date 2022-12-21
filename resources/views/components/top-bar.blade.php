<nav id="top-bar" class="bg-white border-b">
    <div class="flex flex-row justify-center py-4 pl-1">

        <!-- LOGO -->
        <div class="w-1/4 mr-auto">
            <a href="#" class="flex items-center font-extrabold text-gray-700">
                <img src="{{ asset('images/logo.jpg') }}" class="h-10" alt="Logo">
                    Vesaka</a>
        </div>
        
        <div class="flex-grow h-full"></div>

        <!-- Header Menu -->
        <div class="hidden w-1/4 ml-auto flex-grow flex justify-end lg:items-center lg:w-auto uppercase font-light">
            <a href="#" class="text-gray-800 text-sm font-light hover:text-blue-500">
                <i class="icon ion-ios-notifications-outline mr-3 text-gray-600 text-2xl inline-block"></i>
            </a>
        </div>

        <!-- Avatar Menu -->
        <div class="hidden sm:flex items-center">
            <div class="relative" id="usermenu">
                <div class="flex items-center cursor-pointer text-sm border border-white border-b-0 text-gray-700 rounded-t-lg py-1 px-2">
                    <img src="{{ asset('images/avatar.png') }}" class="rounded-full h-8 mr-2 border border-gray-500 p-px">
                        Vesaka
                        <svg class="fill-current text-gray-600 h-4 w-4 mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                </div>
            </div>
        </div> 
        <!-- END Avatar Menu -->

        <!-- Toggle Menu Svg Icon -->
        <div class="sm:hidden cursor-pointer h-6 w-6 mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" className="fill-current">
                <path fillRule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"></path>
            </svg>
        </div>

    </div>
</nav> 
