<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <title>Sidebar ACMI</title>
</head>

<body class="bg-white">
  <!-- sidebar -->
  <div class="w-[260px] h-[90vh] ml-6 my-9 bg-[linear-gradient(to_bottom,#0F174A,#0F1964,#0F1A75,#101D8C,#101E98,#111FA9)] text-white flex flex-col justify-between p-5 rounded-3xl shadow-xl pt-3">

    <div>
      <!-- logo -->
      <div class="flex items-center justify-center gap-3 mb-7">
        <img src="{{ asset('assets/logo.svg') }}" alt="logo" class="w-40 h-30">
       
      </div>
      <div class="space-y-3 text-sm">

        <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6] rounded-lg cursor-pointer">
          <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
          <p class="text-base">Dashboard</p>
        </div>

        <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6] rounded-lg cursor-pointer">
          <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
          <p class="text-base">Post</p>
        </div>

        <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6] rounded-lg cursor-pointer"> 
          <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
          <p class="text-base">FaQ</p>
        </div>

        <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6] rounded-lg cursor-pointer">
          <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
          <p class="text-base">Product</p>
        </div>

        <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6] rounded-lg cursor-pointer">
          <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
          <p class="text-base">Media</p>
        </div>

        <div>
            <div onclick="toggleCRM()" id="crmBtn" class="flex items-center justify-between px-3 py-2 hover:bg-[#4155C6] rounded-lg cursor-pointer transition-colors duration-200">
              <div class="flex items-center gap-6">
                <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
                <p class="text-base">CRM</p>
              </div>
              <span id="arrow" class="transition-transform duration-200">⌄</span>
            </div>
          
            <div id="crmMenu" class="hidden text-sm bg-[#142062] rounded-lg overflow-hidden">
              <div class="px-6 py-2 hover:text-[#958DFF] cursor-pointer transition-colors duration-200">
                Inbound
              </div>
            
              <div class="px-6 py-2 hover:text-[#958DFF] cursor-pointer transition-colors duration-200">
                Members
              </div>
            </div>
          </div>
      
              <!-- media partner -->
              <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6] rounded-lg cursor-pointer">
                <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
                <p class="text-base">Media Partner</p>
              </div>
      
            </div>
          </div>

    <!-- set config -->
    <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6] rounded-lg cursor-pointer">
      <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
      <p class="text-base">Settings Config</p>
    </div>

  </div>

</body>
</html>