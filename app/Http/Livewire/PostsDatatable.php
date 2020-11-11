<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laratrust\Models\LaratrustRole;

class PostsDatatable extends Component
{
    use WithPagination;

    public $searchTerm;
    public $sortColumn = 'title';
    public $sortDirection = "asc";

    public $editTitle = '';
    public $editDescription = '';
    public $editStatus= '';
    public $editId = null;
    public $toggleForm = false;

    public $formResponseError = '';
    public $formResponseSuccess = '';

    public $usersName = '';
    public $uid = '';

    private function headerConfig(){
        return [
            'id' => 'id',
            'description' => 'Description',
            'title' => 'Title',
            'created_at' => [
                'label' => 'created',
                'func' => function($value){
                    return $value->diffForHumans();
                }
            ],
            'user_id' => [
                'label' => 'owner',
                'func' => function($value){
                    if($user = User::get()->where('id',$value)->first()){
                        return $user->name;
                    }else{
                        return 'User Deleted';
                    }
                }
            ],
            'status' => [
                'label' => 'status',
                'func' => function($value){
                    if($value == true){
                        return 'Solved';
                    }else{
                        return 'Unsolved';
                    }
                }
            ]
        ];
    }


    public function sort($column){
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function delete($id){
        Post::destroy($id);
        $this->resetForm();
    }

    public function updateForm($id){
        $post = Post::get()->where('id',$id)->first();
        $this->toggleForm = true;
        $this->editId = $id;
        $this->editTitle = $post->title;
        $this->editDescription = $post->description;
        $this->editStatus = $post->status;
    }

    public function checkRole($id){
        $user = Post::get()->where('id',$id)->first();
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
        $post = Post::get()->where('id',$this->editId)->first();
        $post->title = $this->editTitle;
        $post->description = $this->editDescription;
        $post->status = $this->editStatus;

        if($post->save()){
                $this->formResponseSuccess = 'Updated form';
        }else{
            $this->formResponseError = 'could not update user';
        }

        
    }



    private function resultData(){

        

        return Post::where(function ($query){

            if($this->searchTerm != ''){
                $query->where('title', 'like' , '%'.$this->searchTerm.'%')
                ->orWhere('description', 'like' , '%'.$this->searchTerm.'%');
            }

        })->orderBy($this->sortColumn,$this->sortDirection)->paginate(10);

        //return User::orderBy($this->sortColumn,$this->sortDirection)->paginate(10);


    }

    public function render()
    {
        return view('livewire.posts-datatable', [
            'data' => $this->resultData(),
            'headers' => $this->headerConfig()
        ]);
    }
}
