
<aside class="w-[260px] min-h-[93vh] ml-6 my-6 bg-[linear-gradient(to_bottom,#0F174A,#0F1964,#0F1A75,#101D8C,#101E98,#4D58C4,#7C87E3)] text-white flex flex-col justify-between p-4 rounded-2xl shadow-2xl">
    
  <div>
      <div class="flex items-center justify-center mb-24 mt-10">
          <img src="{{ asset('assets/logo.svg') }}" alt="logo" class="h-8 w-auto object-contain">
      </div>

      <nav class="space-y-2">
          
          <a href="{{ route('dashboard') }}" 
             class="flex items-center gap-4 px-4 py-3 rounded-md cursor-pointer transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-[#4155C6]' : 'hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100' }}">
              <i class="fas fa-table-columns"></i>
              <span class="text-md font-medium text-white {{ request()->routeIs('dashboard') ? 'font-semibold' : 'font-medium' }}">Dashboard</span>
          </a>

          <a href="{{ route('post') }}" 
             class="flex items-center gap-4 px-4 py-3 rounded-[8px] cursor-pointer transition-all duration-300 {{ request()->routeIs('post') ? 'bg-[#4155C6]' : 'hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100' }}">
              <i class="fas fa-rectangle-list"></i>
              <span class="text-md font-medium text-white {{ request()->routeIs('post') ? 'font-semibold' : 'font-medium' }}">Post</span>
          </a>

          <a href="{{ route('faq') }}" 
             class="flex items-center gap-4 px-4 py-3 rounded-[8px] cursor-pointer transition-all duration-300 {{ request()->routeIs('faq') ? 'bg-[#4155C6]' : 'hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100' }}">
              <i class="fas fa-circle-question"></i>
              <span class="text-[15px] font-medium text-white">Faq</span>
          </a>

          <a href="{{ route('product.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-[8px] cursor-pointer transition-all duration-300 {{ request()->routeIs('product.index') ? 'bg-[#4155C6]' : 'hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100' }}">
              <i class="fas fa-box"></i>
              <span class="text-[15px] font-medium text-white">Product</span>
          </a>

          <a href="{{ route('media') }}" class="flex items-center gap-4 px-4 py-3 rounded-[8px] cursor-pointer transition-all duration-300 hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100">
              <i class="fas fa-folder-open"></i>
              <span class="text-[15px] font-medium text-white">Media</span>
          </a>

          <div class="pt-2">
              <button onclick="this.nextElementSibling.classList.toggle('hidden'); this.nextElementSibling.classList.toggle('flex'); this.querySelector('svg').classList.toggle('rotate-180');" 
                      class="w-full flex items-center justify-between px-4 py-3 rounded-[8px] cursor-pointer transition-all duration-300 hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100">
                  <div class="flex items-center gap-4">
                      <i class="fas fa-id-badge"></i>
                      <span class="text-[15px] font-medium text-white">CRM</span>
                  </div>
                  <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                  </svg>
              </button>
              
              <div class="hidden flex-col gap-1 bg-[#142062] rounded-lg mt-1 py-2">
                  <a href="#" class="pl-[52px] py-2 text-[14px] text-white hover:text-[#958DFF] transition-colors duration-200"> 
                    <i class="fas fa-user-plus mr-3"></i>Inbound</a>
                  <a href="#" class="pl-[52px] py-2 text-[14px] text-white hover:text-[#958DFF] transition-colors duration-200">
                    <i class="fas fa-user mr-3"></i>Members</a>
              </div>
          </div>

          <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-[14px] cursor-pointer transition-all duration-300 hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100">
              <i class="fas fa-handshake-simple"></i>
              <span class="text-[15px] font-medium text-white">Media Partner</span>
          </a>

      </nav>
  </div>

  <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-[14px] cursor-pointer transition-all duration-300 hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100 mt-10">
      <i class="fas fa-gear"></i>
      <span class="text-[15px] font-medium text-white">Settings Config</span>
  </a>

</aside>