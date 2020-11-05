<div class="grid grid-cols-1 bg-gray-100">

<div class="grid grid-cols-6 grid-flow-col bg-gray-100">
        <input  wire:model="searchTerm" placeholder="Search...." class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-t-lg text-sm focus:outline-none px-2 "/>
</div>

    <table class="w-full bg-gray-200">

        <thead class="bg-gray-300 py-2 px-2">
            @foreach($headers as $key => $value)
            <th class="py-2 px-2" style="cursor: pointer" wire:click="sort('{{$key}}')">
                {{is_array($value) ? $value['label'] : $value}}
            </th>
            @endforeach
            <th>delete</th>
            <th>Edit</th>
        </thead>
        
        <tbody>
            @if(count($data))
                @foreach($data as $item)
                    <tr class="py-2">
                        @foreach($headers as $key => $value)
                            <td class="mx-auto text-center py-2 border-t-2">
                                {{!! is_array($value) ? $value['func']($item->$key) :$item->$key }}
                            </td>
                        @endforeach
                        
                        
                        <td class="text-center py-2 border-t-2" wire:click="delete({{$item->id}})">delete</td>
                        <td class="text-center py-2 border-t-2" wire:click="updateForm({{$item->id}})">Edit</td>
                    </tr>
                @endforeach
            @else
                <h1 class="ml-2 mt-2">No posts found</h1>
            @endif

        </tbody>
    </table>
    {{$data->links()}}

    @if($ErrorMessage !== '')
    <h1 class="text-white p-4 text-lg bg-red-600 rounded-md">{{$ErrorMessage}}</h1>
    @endif

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
