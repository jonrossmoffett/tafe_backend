<div>

    <div class="pt-2 relative mx-auto text-gray-600">
        <input wire:model="searchTerm" class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
          type="search" name="search" placeholder="Search">
        <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
          <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
            viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
            width="512px" height="512px">
            <path
              d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
          </svg>
        </button>
      </div>


    <input wire:model="searchTerm" placeholder="Search...." class=""/>
    <table class="w-full bg-gray-500">

        <thead class="bg-gray-200 p-4">
            @foreach($headers as $key => $value)
            <th style="cursor: pointer" wire:click="sort('{{$key}}')">
                {{is_array($value) ? $value['label'] : $value}}
            </th>
            @endforeach
            <th>delete</th>
            <th>Edit</th>
        </thead>
        
        <tbody>
            @if(count($data))
                @foreach($data as $item)
                    <tr>
                        @foreach($headers as $key => $value)
                            <td class="mx-auto text-center">
                                {{!! is_array($value) ? $value['func']($item->$key) :$item->$key }}
                            </td>
                        @endforeach
                        
                        
                        <td class="text-center" wire:click="delete({{$item->id}})">delete</td>
                        <td class="text-center" wire:click="updateForm({{$item->id}})">Edit</td>
                    </tr>
                @endforeach
            @else
                <h1 class="ml-2 mt-2">No posts found</h1>
            @endif

        </tbody>
    </table>
    {{$data->links()}}

    <h1 class="text-white p-4 text-lg bg-red-600 rounded-md">{{$ErrorMessage}}</h1>

    @if($toggleForm)

    <div class=" w-full flex justify-content-center">
        <form wire:submit.prevent="save" class="grid grid-cols-1 mx-auto mt-4 mb-4 bg-gray-100 px-4 py-4 rounded-md ">
            <div class="flex justify-between">
                <label class="col-6 mt-2">Name</label>
                <button class="bg-red-600 text-gray-100 w-10" type="button" wire:click="resetForm">X</button>
            </div>
            <input wire:model="editName" type="text" name="Name" class="bg-grey-200 mt-2" >
            <label class="col-6 mt-4">Email</label>
            <input wire:model="editEmail" type="text" name="Email" class="bg-grey-200 mt-2 " >
            <label class="col-6 mt-4">Password</label>
            <input wire:model="editPassword" type="text" name="Password" class="bg-grey-200 mt-2" >
            <p class="font-red-500" >{{$formResponseError}}</p>
            <h2 class="text-green-500 bg-success w-full mt-2">{{$formResponseSuccess}}</h2>
            <button class="mt-4 bg-gray-500 text-gray-200" type="submit"> Submit</button>
        </form>
    </div>


    @endif


</div>
