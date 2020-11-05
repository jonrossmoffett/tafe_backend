<x-app-layout>
    <x-slot name="header">

        <div class="flex row">
            
        <a href="/admin">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users
        </h2>
        </a>

        <a href="/admin/posts" class="ml-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Posts
        </h2>
        </a>

        </div>



    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- <x-jet-welcome /> -->
                <livewire:posts-datatable />
            </div>
        </div>
    </div>

    
    


</x-app-layout>
