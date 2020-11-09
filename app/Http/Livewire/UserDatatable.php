<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laratrust\Models\LaratrustRole;


class UserDatatable extends Component
{
    use WithPagination;

    public $searchTerm;
    public $sortColumn = 'name';
    public $sortDirection = "asc";

    public $name = '';
    public $email = '';
    public $editPassword = '';
    public $editId = null;
    public $currentRole = '';
    public $toggleForm = false;
    public $newRole = 'user';

    public $ErrorMessage = '';

    public $formResponseError = '';
    public $formResponseSuccess = '';


    protected $rules = [
        'name' => 'required|min:3',
        'editPassword' => 'password',
        'email' => 'required | email'
    ];

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

    private function computedHeader(){
        return [
            'id' => [
                'label' => 'role',
                'func' => function($value){
                    $user = User::get()->where('id',$value)->first();
                    if($user->hasRole('administrator')){
                        return 'administrator';
                    }else if($user->hasRole('user')){
                        return 'user';
                    }elseif($user->hasRole('superadministrator')){
                        return 'superadministrator';
                    }else{
                        return 'no role';
                    }
                }
            ]
        ];
    }

    public function CloseErrorMsg(){
        $this->ErrorMessage = '';
    }

    public function sort($column){
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function delete($id){
        $this->ErrorMessage = '';
        $user = User::get()->where('id',$id)->first();
        if($user->hasRole(['administrator','superadministrator'])){
            $this->ErrorMessage = 'You cannot delete administrators';
        }else{
            Post::where('user_id',$id)->delete();
            User::destroy($id);
        }

        $this->resetForm();
    }

    public function updateForm($id){
        $this->ErrorMessage = '';
        $user = User::get()->where('id',$id)->first();
        $this->toggleForm = true;
        $this->editId = $id;
        $this->email = $user->email;
        $this->name = $user->name;

        if($user->hasRole('administrator')){
            $this->currentRole = 'administrator';
        }else if($user->hasRole('user')){
            $this->currentRole = 'user';
        }elseif($user->hasRole('superadministrator')){
            $this->currentRole = 'superadministrator';
        }else{
            $this->currentRole = 'no role';
        }


        
    }

    public function checkRole($id){
        $user = User::get()->where('id',$id)->first();
        $hasrole = $user->hasRole('administrator');
        return $hasrole;
    }

    public function resetForm(){
        $this->toggleForm = false;
        $this->name = '';
        $this->email = '';
        $this->editPassword = '';
        $this->formResponseSuccess = '';
        $this->formResponseError = '';
        $this->editId = null;
        $this->currentRole = '';
        $this->newRole = 'user';
    }

    public function save(){

        $this->validate();

        $user = User::get()->where('id',$this->editId)->first();
        $user->name = $this->name;
        $user->email = $this->email;

        if($this->editPassword !== ''){
            $user->password = Hash::make($this->editPassword);
        }

        //dd($this->newRole);

        if($this->newRole == 'administrator'){
            $user->detachRoles(['user','administrator']);
            $user->attachRoles(['administrator']);
        }else if($this->newRole == 'user'){
            $user->detachRoles(['administrator','user']);
            $user->attachRoles(['user']);
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
        
        $this->resetForm();
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
            'headers' => $this->headerConfig(),
            'computedHeader' => $this->computedHeader()
        ]);
    }
}
