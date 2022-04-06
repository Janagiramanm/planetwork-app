<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
        <link href="https://unpkg.com/tailwindcss/dist/tailwind.min.css" rel="stylesheet"> 
        
        
        <!-- <link href="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css" rel="stylesheet"> -->
       

        @livewireStyles

        <!-- Scripts -->
         <script src="{{ mix('js/app.js') }}" defer></script>
         <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9G5CsqGsNlwFR7rIG9qyEJYDTi3yckjI"></script> -->
         <script
            src="https://maps.googleapis.com/maps/api/js?key=@php echo env('GOOGLEMAPAPI') @endphp&callback=initMap&libraries=places&v=weekly"
            async
            ></script>
         <script src="{{ URL::asset('/js/googlemap.js') }}" defer></script>
    <!-- <script src="{{ URL::asset('/js/geocode.js') }}" defer></script> -->
         <!-- <script src="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js"></script>-->
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> 
       
       
        

        <!-- Select2 -->
      
        <script type="text/javascript" src="https://unpkg.com/moment"></script>
    
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />
        
        <div class=" grid grid-cols-12 min-h-screen bg-gray-100">
            <div class="col-span-12 row-span-1 bg-gray-100 fixed">  @livewire('navigation-menu') </div>
            <div class="col-span-2 h-screen sticky fixed top-0">
                         
                       <div class="bg-gray-800 shadow-xl h-16 fixed bottom-0 mt-14 md:relative md:h-screen z-10 w-full">

                                <div class="md:mt-12 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
                                    <ul class="list-reset flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-2 text-center md:text-left ">
                                        <li class="mr-3 mt-2 flex-1">
                                            <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                                 <i class="fas fa-tachometer-alt pr-0 md:pr-3"></i></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Dashboard</span>
                                            </x-jet-nav-link>
                                          
                                        </li>
                                        <li class="mr-3 mt-4 flex-1">
                                            <x-jet-nav-link href="{{ route('customers') }}" :active="request()->routeIs('customers')">
                                                 <i class="fas fa-address-card pr-0 md:pr-3"></i></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Customers</span>
                                            </x-jet-nav-link>
                                        </li>
                                        <li class="mr-3 mt-2 flex-1">
                                            <x-jet-nav-link href="{{ route('jobs') }}" :active="request()->routeIs('jobs')">
                                                 <i class="fas fa-user-md pr-0 md:pr-3"></i></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block pl-2">jobs</span>
                                            </x-jet-nav-link>
                                           
                                        </li>
                                        <li class="mr-3 mt-2 flex-1">
                                            <x-jet-nav-link href="{{ route('users') }}" :active="request()->routeIs('users')">
                                                 <i class="fas fa-users pr-0 md:pr-3"></i></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Users</span>
                                            </x-jet-nav-link>
                                        </li>
                                        <li class="mr-3 mt-2 flex-1">
                                            <x-jet-nav-link href="{{ route('leaves') }}" :active="request()->routeIs('leaves')">
                                            <i class="fas fa-envelope-open-text pr-0 md:pr-3"></i></i></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Leaves</span>
                                            </x-jet-nav-link>
                                        </li>
                                        <li class="my-px">
											<span class="flex font-medium text-sm text-gray-400 px-4 my-4 uppercase bg-indigo-400 w-60 p-3">Reports</span>
										</li>
                                        <li class="mr-3 mt-2 flex-1">
                                            <x-jet-nav-link href="{{ route('attendance') }}" :active="request()->routeIs('attendance')">
                                            <i class="fas fa-envelope-open-text pr-0 md:pr-3"></i></i></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Attendance</span>
                                            </x-jet-nav-link>
                                        </li>
                                        <li class="mr-3 mt-2 flex-1">
                                            <x-jet-nav-link href="{{ route('reports') }}" :active="request()->routeIs('reports')">
                                            <i class="fas fa-envelope-open-text pr-0 md:pr-3"></i></i></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Work Report</span>
                                            </x-jet-nav-link>
                                        </li>
									
										<li class="my-px">
											<span class="flex font-medium text-sm text-gray-400 px-4 my-4 uppercase bg-indigo-400 w-60 p-3">Masters</span>
										</li>
                                        <ul class="list-reset flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-2 text-center md:text-left">
                                           <li class="mr-3 flex-1">
                                                <x-jet-nav-link href="{{ route('roles') }}" :active="request()->routeIs('roles')">
                                                        <i class="fas fa-user-tag pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Roles</span>
                                                </x-jet-nav-link>
                                            </li>
											<li class="mr-3 flex-1">
                                                <x-jet-nav-link href="{{ route('cities') }}" :active="request()->routeIs('cities')">
                                                        <i class="fas fa-city pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Cities</span>
                                                </x-jet-nav-link>
                                            </li>
											<li class="mr-3 flex-1">
                                                <x-jet-nav-link href="{{ route('tasks') }}" :active="request()->routeIs('tasks')">
                                                        <i class="fas fa-tasks pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Tasks</span>
                                                </x-jet-nav-link>
                                            </li>
                                            <li class="mr-3 flex-1">
                                                <x-jet-nav-link href="{{ route('holidays') }}" :active="request()->routeIs('holidays')">
                                                <i class="fas fa-mug-hot pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Holidays</span>
                                                </x-jet-nav-link>
                                            </li>
                                        </ul>
                                       
                                    </ul>
                                </div>
                        </div>
            </div> 
            <div class="col-span-10 ">  
                @if (isset($header))
                    <header class="bg-white shadow mt-16 w-full">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif
                <main class="">
                    {{ $slot }}
                </main>
            </div>
    

           
          
        @stack('modals')
        @stack('styles')
        @stack('scripts')
        @stack('before-livewire-scripts')
        @stack('js');
        @livewireScripts
       
       
    </body>
</html>
