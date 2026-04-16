<div class="w-[260px] h-[90vh] ml-6 my-9 bg-[linear-gradient(to_bottom,#0F174A,#0F1964,#0F1A75,#101D8C,#101E98,#4D58C4,#7C87E3)] text-white flex flex-col justify-between p-5 rounded-3xl shadow-xl pt-3">
  <div>
    <div class="flex items-center justify-center gap-3 mb-7">
      <img src="{{ asset('assets/logo.svg') }}" alt="logo" class="w-40 h-30">
    </div>

    <div class="space-y-3 text-sm">
      
      <a href="{{ route('dashboard') }}" 
         class="flex items-center gap-6 px-3 py-2 rounded-lg cursor-pointer transition-all {{ request()->routeIs('dashboard') ? 'bg-[#4155C6]' : 'hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100' }}">
        <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
        <p class="text-base text-white">Dashboard</p>
      </a>

      <a href="{{ route('post') }}" 
         class="flex items-center gap-6 px-3 py-2 rounded-lg cursor-pointer transition-all {{ request()->routeIs('post') ? 'bg-[#4155C6]' : 'hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100' }}">
        <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
        <p class="text-base text-white">Post</p>
      </a>

      <a href="{{ route('faq') }}" 
         class="flex items-center gap-6 px-3 py-2 rounded-lg cursor-pointer transition-all {{ request()->routeIs('faq') ? 'bg-[#4155C6]' : 'hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100' }}">
        <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
        <p class="text-base text-white">Faq</p>
      </a>

      <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100 rounded-lg cursor-pointer transition-all">
        <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
        <p class="text-base text-white">Product</p>
      </div>

      <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100 rounded-lg cursor-pointer transition-all">
        <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
        <p class="text-base text-white">Media</p>
      </div>

      <div>
          <div onclick="toggleCRM()" id="crmBtn" class="flex items-center justify-between px-3 py-2 hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100 rounded-lg cursor-pointer transition-colors duration-200">
            <div class="flex items-center gap-6">
              <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
              <p class="text-base text-white">CRM</p>
            </div>
            <span id="arrow" class="transition-transform duration-200">⌄</span>
          </div>
        
          <div id="crmMenu" class="hidden text-sm bg-[#142062] rounded-lg overflow-hidden mt-1">
            <div class="px-12 py-2 hover:text-[#958DFF] cursor-pointer transition-colors duration-200">Inbound</div>
            <div class="px-12 py-2 hover:text-[#958DFF] cursor-pointer transition-colors duration-200">Members</div>
          </div>
      </div>
    
      <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100 rounded-lg cursor-pointer transition-all">
        <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
        <p class="text-base text-white">Media Partner</p>
      </div>
    
    </div>
  </div>

  <div class="flex items-center gap-6 px-3 py-2 hover:bg-[#4155C6]/50 opacity-90 hover:opacity-100 rounded-lg cursor-pointer transition-all">
    <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
    <p class="text-base text-white">Settings Config</p>
  </div>

</div>

<script>
function toggleCRM() {
  const menu = document.getElementById('crmMenu');
  const arrow = document.getElementById('arrow');
  if(menu && arrow) {
      menu.classList.toggle('hidden');
      arrow.classList.toggle('rotate-180');
  }
}
</script>