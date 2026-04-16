<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACMI - Dashboard CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FB] font-sans">

    <div class="flex">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col px-10 pt-9">
            
            <nav class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-semibold text-black mt-4">Dashboard</h1>
                    <p class="text-black text-base mt-1">
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-4 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 cursor-pointer hover:bg-gray-50 transition-all">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-700">
                        BT
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-black text-sm">Breyole Team</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </nav>

            <div class="grid grid-cols-3 gap-8">
                <div class="bg-white rounded-[10px] border-[1px] border-[#1120B0] shadow-sm overflow-hidden">
                    <div class="p-5 flex justify-between items-center">
                        <p class="text-gray-600 text-lg font-medium">Total Member</p>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-black">
                            <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122Z" />
                        </svg>
                    </div>
                    <div class="border-t-[1px] border-[#1120B0]"></div>
                    <div class="p-6">
                        <h2 class="text-5xl font-bold text-black">0</h2>
                    </div>
                </div>
            
                <div class="bg-white rounded-[10px] border-[1px] border-[#1120B0] shadow-sm overflow-hidden">
                    <div class="p-5 flex justify-between items-center">
                        <p class="text-gray-600 text-lg font-medium">New Member</p>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-black">
                            <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122Z" />
                        </svg>
                    </div>
                    <div class="border-t-[1px] border-[#1120B0]"></div>
                    <div class="p-6">
                        <h2 class="text-5xl font-bold text-black">0</h2>
                    </div>
                </div>
            
                <div class="bg-white rounded-[10px] border-[1px] border-[#1120B0] shadow-sm overflow-hidden">
                    <div class="p-5 flex justify-between items-center">
                        <p class="text-gray-600 text-lg font-medium">Most View</p>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-black">
                            <path d="M18.375 2.25c.621 0 1.125.504 1.125 1.125v17.25c0 .621-.504 1.125-1.125 1.125H15.75c-.621 0-1.125-.504-1.125-1.125V3.375c0-.621.504-1.125 1.125-1.125h2.625ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.625c.621 0 1.125.504 1.125 1.125v10.875c0 .621-.504 1.125-1.125 1.125h-2.625a1.125 1.125 0 0 1-1.125-1.125V8.625ZM3.75 13.125c0-.621.504-1.125 1.125-1.125h2.625c.621 0 1.125.504 1.125 1.125v6.375c0 .621-.504 1.125-1.125 1.125H4.875a1.125 1.125 0 0 1-1.125-1.125v-6.375Z" />
                        </svg>
                    </div>
                    <div class="border-t-[1px] border-[#1120B0]"></div>
                    <div class="p-6">
                        <h2 class="text-5xl font-bold text-black">0</h2>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>