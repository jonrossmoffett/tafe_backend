<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laratrust\Models\LaratrustRole;


class UserDatatable extends Component
{
    use WithPagination;

    public $searchTerm;
    public $sortColumn = 'name';
    public $sortDirection = "asc";

    public $editName = '';
    public $editEmail = '';
    public $editPassword = '';
    public $editId = null;
    public $toggleForm = false;

    public $formResponseError = '';
    public $formResponseSuccess = '';

    private function headerConfig(){
        return [
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'created_at' => [
                'label' => 'created',
                'func' => function($value){
                    return $value->diffForHumans();
                }
            ]
        ];
    }


    public function sort($column){
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function delete($id){
        User::destroy($id);
        $this->resetForm();
    }

    public function updateForm($id){
        $user = User::get()->where('id',$id)->first();
        $this->toggleForm = true;
        $this->editId = $id;
        $this->editEmail = $user->email;
        $this->editName = $user->name;
    }

    public function checkRole($id){
        $user = User::get()->where('id',$id)->first();
        $hasrole = $user->hasRole('administrator');
        return $hasrole;
    }

    public function resetForm(){
        $this->toggleForm = false;
        $this->editName = '';
        $this->editEmail = '';
        $this->editPassword = '';
        $this->formResponseSuccess = '';
        $this->formResponseError = '';
        $this->editId = null;
    }

    public function save(){
        $user = User::get()->where('id',$this->editId)->first();
        $user->name = $this->editName;
        $user->email = $this->editEmail;

        if($this->editPassword !== ''){
            $user->password = Hash::make($this->editPassword);
        }

        if($user->save()){

            if($this->editPassword !== ''){ 
                $this->formResponseSuccess = 'Updated Users Details and changed password';
            }else{
                $this->formResponseSuccess = 'Updated Users Details But did not change password';
            }
            
        }else{
            $this->formResponseError = 'could not update user';
        }
        
        $this->editPassword = '';
        
    }

    private function resultData(){

        return User::where(function ($query){

    
            if($this->searchTerm != ''){
                $query->where('name', 'like' , '%'.$this->searchTerm.'%')
                ->orWhere('email', 'like' , '%'.$this->searchTerm.'%');
            }

        })->orderBy($this->sortColumn,$this->sortDirection)->paginate(10);

        //return User::orderBy($this->sortColumn,$this->sortDirection)->paginate(10);


    }

    public function render()
    {
        return view('livewire.user-datatable', [
            'data' => $this->resultData(),
            'headers' => $this->headerConfig()
        ]);
    }
}
